<?php

namespace Model;

class Order_products extends Model
{
    private int $id;
    private int $order_id;
    private int $product_id;
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
        $this->order_id = $data['order_id'] ?? null;
        $this->product_id = $data['product_id'] ?? null;
        $this->amount = $data['amount'] ?? null;

        return $this;
    }

    public function getAllByOrderId(int $orderId): array
    {
        $stmt = $this->pdo->prepare("SELECT * FROM order_products WHERE order_id = :orderId");
        $stmt->execute(['orderId' => $orderId]);

        $orderProductsData = $stmt->fetchAll();

        $objects = [];

        foreach ($orderProductsData as $data) {
            $obj = new self();

            $obj->id = $data['id'];
            $obj->order_id = $data['order_id'];
            $obj->product_id = $data['product_id'];
            $obj->amount = $data['amount'];

            $objects[] = $obj;
        }
        return $objects;
    }

    public function deleteByUserId(int $orderId)
    {
        $stmt = $this->pdo->prepare("DELETE FROM order_products WHERE order_id = :orderId");
        $stmt->execute(['orderId' => $orderId]);
    }

    public function getByAllOrderId(int $orderId): ?array
    {
    $stmt = $this->pdo->prepare("SELECT * FROM order_products WHERE order_id = :$orderId");
    $stmt->execute(['orderId' => $orderId]);

    $orderProductsData = $stmt->fetchAll();

        $objects = [];

        foreach ($orderProductsData as $data) {
            $obj = new self();

            $obj->id = $data['id'];
            $obj->order_id = $data['order_id'];
            $obj->product_id = $data['product_id'];
            $obj->amount = $data['amount'];

            $objects[] = $obj;
        }
        return $objects;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getOrderId(): int
    {
        return $this->order_id;
    }

    public function getProductId(): int
    {
        return $this->product_id;
    }

    public function getAmount(): int
    {
        return $this->amount;
    }
}