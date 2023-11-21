<?php

namespace App\Model;

use PDO;

class AbstractModel
{
    protected PDO $connection;

    public function __construct()
    {
        $this->connection = new PDO(
            'mysql:host=db.3wa.io;dbname=cedricleclinche_classicmodels;charset=UTF8',
            'cedricleclinche',
            'eb094434df8b9e10f67b5c650f7bed6c',
            [
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
            ]
        );
    }
}