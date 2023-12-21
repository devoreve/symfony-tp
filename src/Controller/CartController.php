<?php

namespace App\Controller;

use App\Entity\Post;
use App\Service\Cart\CartInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CartController extends AbstractController
{
    #[Route(path: '/cart', name: 'app_cart_index')]
    public function index(): Response
    {
        return $this->render('cart/index.html.twig');
    }

    #[Route(path: '/cart/add/{slug}', name: 'app_cart_add', methods: ['POST'])]
    public function add(Post $post, CartInterface $cart): Response
    {
        if ($post->isPremium()) {
            $cart->add($post->getId(), ['quantity' => 1, 'id' => $post->getId()]);
        }

        return $this->redirectToRoute('app_post_show', ['slug' => $post->getSlug()]);
    }
}
