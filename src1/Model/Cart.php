<?php

namespace Model;

class Cart extends Model
{

    public function getByCart(int $userId): array
    {
        $stmt = $this->pdo->prepare("SELECT * FROM user_products WHERE user_id = :userId");
        $stmt->execute(['userId' => $userId]);
        $userProducts = $stmt->fetchAll();

        return $userProducts;
    }

    public function ForGetCart(int $productId): array
    {
        // Используем подготовленный запрос
        $stmt = $this->pdo->prepare("SELECT * FROM products WHERE id = :productId");
        $stmt->execute(['productId' => $productId]);
        $product = $stmt->fetch();

        return $product;
    }

}