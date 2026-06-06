<?php

namespace Model;

class Order_products extends Model
{
    private int $id;
    private int $orderId;
    private int $productId;
    private int $amount;


    public function create1 (int $orderId, int $productId, int $amount): self|null
    {
        $stmt = $this->pdo->prepare("INSERT INTO order_products (order_id, product_id, amount) VALUES (:orderId, :productId, :amount)");
        $stmt->execute([
            'orderId' => $orderId,
            'productId' => $productId,
            'amount' => $amount
        ]);

        $lastId = $this->pdo->lastInsertId();

        if (!$lastId) {
            return null;
        }
        $stmt = $this->pdo->prepare("SELECT * FROM order_products WHERE id = :id");
        $stmt->execute(['id' => $lastId]);
        $data = $stmt->fetch();

        if (!$data) {
            return null;
        }
        return $this->hydrate($data);
    }

    private function hydrate(array $data): self
    {
        $this->id = $data['id'] ?? null;
        $this->orderId = $data['order_id'] ?? null;
        $this->productId = $data['product_id'] ?? null;
        $this->amount = $data['amount'] ?? null;

        return $this;
    }

    public function getAllByOrderId(int $orderId): array
    {
        $stmt = $this->pdo->prepare("SELECT * FROM order_products WHERE order_id = :orderId");
        $stmt->execute(['orderId' => $orderId]);
        $orderProducts = $stmt->fetchAll();
        return $orderProducts;
    }
    public function getId(): int
    {
        return $this->id;
    }

    public function getOrderId(): int
    {
        return $this->orderId;
    }

    public function getProductId(): int
    {
        return $this->productId;
    }

    public function getAmount(): int
    {
        return $this->amount;
    }
}