<?php

namespace App\Model;

class CustomerModel extends AbstractModel
{
    /**
     * @return array
     */
    public function findAll(): array
    {
        $query = $this->connection->prepare(
            'SELECT customerNumber, customerName, contactFirstName, contactLastName, phone, country
            FROM customers'
        );

        $query->execute();

        return $query->fetchAll();
    }

    /**
     * @param int $id
     * @return array|false
     */
    public function find(int $id): array|false
    {
        $query = $this->connection->prepare('SELECT * FROM customers WHERE customerNumber = ?');
        $query->execute([$id]);

        return $query->fetch();
    }
}