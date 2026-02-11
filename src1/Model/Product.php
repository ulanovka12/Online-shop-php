<?php

namespace Model;

class Product extends Model
{
    public function getByCatalog(): array
    {

        $stmt = $this->pdo->query("SELECT * FROM products");
        $products = $stmt->fetchAll();

        return $products;
    }

    public function getByProductId(int $userId, int $productId)
    {
        $stmt = $this->pdo->prepare("SELECT * FROM user_products WHERE product_id = :productId AND user_id = :userId");
        $stmt->execute(['productId' => $productId, 'userId' => $userId]);
        $data = $stmt->fetch();

        return $data;
    }

    public function getByProduct(int $userId,int $productId,int $amount): array
    {

        $stmt = $this->pdo->prepare("INSERT INTO user_products (user_id, product_id, amount) VALUES (:userId, :productId, :amount)");
        $stmt->execute(['userId' => $userId, 'productId' => $productId, 'amount' => $amount]);

        $data = $stmt->fetch();

        return $data;
    }
    public function getUpdateProduct(int $userId,int $productId, int $newAmount):array
    {

        $stmt = $this->pdo->prepare("UPDATE user_products SET amount = :amount WHERE user_id = :userId and product_id = :productId");
        $stmt->execute(['userId' => $userId, 'productId' => $productId, 'amount' => $newAmount]); // Используем $newAmount
        $data = $stmt->fetch();
        return $data;
    }

    public function ValidateProductData(int $productId): array
    {
        $stmt = $this->pdo->prepare("SELECT * FROM products WHERE id = :productId");
        $stmt->execute(['productId' => $productId]);
        $productData = $stmt->fetch();

        if ($productData === false) {
            return [];
        }
        return $productData;
    }
}