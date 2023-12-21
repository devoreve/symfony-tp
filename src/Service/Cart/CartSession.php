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

    /**
     * @param int $key
     * @param array $data
     * @return void
     */
    public function add(int $key, array $data): void
    {
        $this->items[$key] = $data;
        $this->session->set(self::CART_NAME, $this->items);
    }

    /**
     * @param int $key
     * @return void
     */
    public function remove(int $key): void
    {
        unset($this->items[$key]);
        $this->session->set(self::CART_NAME, $this->items);
    }

    /**
     * @return void
     */
    public function clear(): void
    {
        $this->items = [];
        $this->session->set(self::CART_NAME, $this->items);
    }

    /**
     * @return array
     */
    public function getItems(): array
    {
        return $this->items;
    }

    /**
     * @param int $key
     * @return array|null
     */
    public function get(int $key): ?array
    {
        return $this->items[$key] ?? null;
    }
}