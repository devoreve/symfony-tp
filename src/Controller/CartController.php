<?php

namespace App\Controller;

use App\Entity\Post;
use App\Repository\PostRepository;
use App\Service\Cart\CartInterface;
use Doctrine\Common\Collections\Collection;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CartController extends AbstractController
{
    #[Route(path: '/cart', name: 'app_cart_index')]
    public function index(CartInterface $cart, PostRepository $postRepository): Response
    {
        // Récupération des articles correspondant aux id stockés dans le panier
        $posts = $postRepository->findBy(['id' => array_keys($cart->getItems())]);

        // Calcul du montant total de tous les posts
//        $total = array_sum(array_map(fn (Post $post) => $post->getPrice(), $posts));
        $total = array_reduce($posts, fn (int $total, Post $post) => $total + $post->getPrice(), 0);

        return $this->render('cart/index.html.twig', [
            'posts' => $posts,
            'total' => $total
        ]);
    }

    #[Route(path: '/cart/add/{slug}', name: 'app_cart_add', methods: ['POST'])]
    public function add(Post $post, CartInterface $cart): Response
    {
        if ($post->isPremium()) {
            $cart->add($post->getId(), ['quantity' => 1, 'id' => $post->getId()]);
        }

        return $this->redirectToRoute('app_post_show', ['slug' => $post->getSlug()]);
    }

    #[Route(path: '/cart/remove/{slug}', name: 'app_cart_remove')]
    public function removeFromCart(Post $post, CartInterface $cart): Response
    {
        $cart->remove($post->getId());

        return $this->redirectToRoute('app_cart_index');
    }
}
