<?php

namespace Model;


class Order extends Model
{
    public function create(
        string $contactName,
        string $contactPhone,
        string $comment,
        string $address,
        int $userId
    ){
        $stmt = $this->pdo->prepare(
            "INSERT INTO orders (contact_name, contact_phone, comment, address, user_id) 
                   VALUES (:name, :phone, :comment, :address, :user_id) RETURNING id"
        );

        $stmt->execute([
            'name' => $contactName,
            'phone' => $contactPhone,
            'comment' => $comment,
            'address' => $address,
            'user_id' => $userId
        ]);

        $data = $stmt->fetch();

        return $data['id'];
    }

    public function getAllByUserId(int $userId): array
    {
        $stmt = $this->pdo->prepare("SELECT * FROM user_products WHERE user_id = :userId");
        $stmt->execute(['userId' => $userId]);
        $userProducts = $stmt->fetchAll();

        return $userProducts;
    }

    public function create1 (int $orderId, int $productId, int $amount)
    {
        $stmt = $this->pdo->prepare("INSERT INTO order_products (order_id, product_id, amount) VALUES (:orderId, :productId, :amount)");
        $stmt->execute([
            'orderId' => $orderId,
            'productId' => $productId,
            'amount' => $amount
        ]);
    }

    public function deleteByUserId(int $userId)
    {
        $stmt = $this->pdo->prepare("DELETE FROM user_products WHERE user_id = :userId");
        $stmt->execute(['userId' => $userId]);
    }
}

