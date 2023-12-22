<?php

namespace App\Controller;

use App\Entity\Order;
use App\Entity\Post;
use App\Repository\PostRepository;
use App\Service\Cart\CartInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class OrderController extends AbstractController
{
    #[Route('/order', name: 'app_order')]
    public function index(): Response
    {
        return $this->render('order/index.html.twig', [
            'controller_name' => 'OrderController',
        ]);
    }

    #[Route(path: '/order/validate', name: 'app_order_validate')]
    public function validate(CartInterface $cart, PostRepository $postRepository, EntityManagerInterface $manager): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        // Récupération des articles du panier et calcul du total
        $posts = $postRepository->findBy(['id' => array_keys($cart->getItems())]);
        $total = array_reduce($posts, fn (int $total, Post $post) => $total + $post->getPrice(), 0);

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
}
