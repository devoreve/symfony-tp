<?php

namespace App\Controller;

use App\Entity\Order;
use App\Entity\Post;
use App\Repository\OrderRepository;
use App\Repository\PostRepository;
use App\Service\Cart\CartInterface;
use Doctrine\ORM\EntityManagerInterface;
use Stripe\Price;
use Stripe\StripeClient;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class OrderController extends AbstractController
{
    #[Route('/order', name: 'app_order')]
    public function index(): Response
    {
        return $this->render('order/index.html.twig', [
            'controller_name' => 'OrderController',
        ]);
    }

    #[Route(path: '/order/{id}/checkout', name: 'app_checkout')]
    public function checkout(): Response
    {
        // Page qui affichera le formulaire
        return $this->render('order/checkout.html.twig');
    }

    #[Route(path: '/order/process/payment', name: 'app_order_proceed_payment')]
    public function proceedPayement(): Response
    {
        return $this->render('order/result.html.twig');
    }

    #[Route(path: '/order/checkout', name: 'app_create_checkout_session')]
    public function createCheckoutSession(UrlGeneratorInterface $urlGenerator, OrderRepository $orderRepository): Response
    {
        // Chargement de la bibliothèque de Stripe
        $stripe = new StripeClient($_ENV['STRIPE_SECRET_KEY']);

        // Récupération des produits de la commande
        $order = $orderRepository->find(1);

        $prices = [];

        // Création des tarifs des produits
        foreach ($order->getPosts() as $post) {
            $product = $stripe->products->create(['name' => $post->getSlug()]);

            $prices[] = $stripe->prices->create([
                'product' => $product,
                'unit_amount' => $post->getPrice(),
                'currency' => 'EUR',
            ]);
        }

        $returnUrl = 'http://127.0.0.1:8000/order/process/payment?session_id={CHECKOUT_SESSION_ID}';
        $returnUrl = $urlGenerator->generate('app_order_proceed_payment', [], UrlGeneratorInterface::ABSOLUTE_URL) . '?session_id={CHECKOUT_SESSION_ID}';

        // Création de la session checkout
        $checkoutSession = $stripe->checkout->sessions->create([
            'ui_mode' => 'embedded',
            'line_items' => array_map(fn(Price $price) => ['price' => $price, 'quantity' => 1], $prices),
            'mode' => 'payment',
            'return_url' => $returnUrl
        ]);

        return new JsonResponse(['clientSecret' => $checkoutSession->client_secret]);
    }

    #[Route(path: '/order/validate', name: 'app_order_validate')]
    public function validate(CartInterface $cart, PostRepository $postRepository, EntityManagerInterface $manager): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        // Récupération des articles du panier et calcul du total
        $posts = $postRepository->findBy(['id' => array_keys($cart->getItems())]);
        $total = array_reduce($posts, fn(int $total, Post $post) => $total + $post->getPrice(), 0);

        // Création de la commande
        $order = new Order();
        $order->setUser($this->getUser());
        $order->setTotal($total);

        // Création des détails de la commande
        foreach ($posts as $post) {
            $order->addPost($post);
        }

        // Enregistrement en base de données
        $manager->persist($order);
        $manager->flush();

        // On vide le cache
        $cart->clear();

        return new Response("La commande n°{$order->getId()} a été créée");
    }

    #[Route(path: '/order/status', name: 'app_order_status', methods: ['POST'])]
    public function getStatus(OrderRepository $orderRepository, EntityManagerInterface $manager): Response
    {
        $stripe = new StripeClient($_ENV['STRIPE_SECRET_KEY']);
        $content = json_decode(file_get_contents('php://input'));
        $session = $stripe->checkout->sessions->retrieve($content->session_id);

        $order = $orderRepository->find(1);

        if ($session->status === 'complete') {
            $order->setStatus(Order::ORDER_SUCCESS);
        } elseif ($session->status === 'open') {
            $order->setStatus(Order::ORDER_DENIED);
        }

        $manager->persist($order);
        $manager->flush();

        return new JsonResponse([
            'status' => $session->status,
            'customer_email' => $session->customer_details->customer_email
        ]);
    }
}
