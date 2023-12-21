<?php

namespace App\Service\Cart;
interface CartInterface
{
    public function add(int $key, array $data): void;
    public function remove(int $key): void;
    public function clear(): void;
    public function getItems(): array;
    public function get(int $key): ?array;
}