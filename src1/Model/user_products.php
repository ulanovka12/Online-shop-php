<?php

namespace Model;

class user_products extends Model
{

    private int $id;
    private int $user_id;
    private string $product_id;

    private int $amount;

    public function getByProductId(int $userId, int $productId): self|null
    {
        $stmt = $this->pdo->prepare("SELECT * FROM user_products WHERE product_id = :productId AND user_id = :userId");

        $stmt->execute(['productId' => $productId, 'userId' => $userId]);

        $data = $stmt->fetch();

        if ($data === false) {
            return null;
        }

        $obj = new self();

        $obj->id = $data['id'];
        $obj->user_id = $data['user_id'];
        $obj->product_id = $data['product_id'];
        $obj->amount = $data['amount'];

        return $obj;
    }

    public function getByProduct(int $userId,int $productId,int $amount): self|null
    {

        $stmt = $this->pdo->prepare("INSERT INTO user_products (user_id, product_id, amount) VALUES (:userId, :productId, :amount) RETURNING user_id, product_id, amount");
        $stmt->execute(['userId' => $userId, 'productId' => $productId, 'amount' => $amount]);

        $data = $stmt->fetch();

        $obj = new self();

        $obj->id = $data['id'];
        $obj->user_id = $data['user_id'];
        $obj->product_id = $data['product_id'];
        $obj->amount = $data['amount'];

        return $obj;
    }

    public function getUpdateProduct(int $userId,int $productId, int $newAmount):self|null
    {

        $stmt = $this->pdo->prepare("UPDATE user_products SET amount = :amount WHERE user_id = :userId and product_id = :productId");

        $stmt->execute(['userId' => $userId, 'productId' => $productId, 'amount' => $newAmount]);

        $data = $stmt->fetch();

        $obj = new self();

        $obj->id = $data['id'];
        $obj->user_id = $data['user_id'];
        $obj->product_id = $data['product_id'];
        $obj->amount = $data['amount'];

        return $obj;
    }


    public function getId(): int
    {
        return $this->id;
    }

    public function getUserId(): int
    {
        return $this->user_id;
    }

    public function getProductId(): string
    {
        return $this->product_id;
    }


    public function getAmount(): int
    {
        return $this->amount;
    }


}