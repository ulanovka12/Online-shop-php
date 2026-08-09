<?php

namespace Model;

use PDO;


abstract class Model
{
    protected PDO $pdo;

    public function __construct()
    {
        $this->pdo = new PDO('pgsql:host=postgres; port=5432;dbname=mydb', 'king', 'qwerty');
    }

    abstract protected function getTableName(): string;

}