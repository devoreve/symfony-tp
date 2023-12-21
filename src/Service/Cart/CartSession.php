<?php

namespace App\Service\Cart;

use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Session\Session;

class CartSession implements CartInterface
{
    private readonly Session $session;
    private array $items;

    private const CART_NAME = 'cart';

    public function __construct(RequestStack $requestStack)
    {
        $this->session = $requestStack->getSession();
        $this->items = $this->session->get(self::CART_NAME, []);
    }
}