<?php

namespace Model;

class Reviews extends Model
{
    private int $id;
    private string $name;
    private string $description;
    private int $product_id;
    private int $user_id;
    //получаю все отзывы
    public function getByProductId(int $productId): array
    {
        $stmt = $this->pdo->prepare(
            "SELECT * FROM reviews WHERE product_id = :product_id"
        );
        $stmt->execute(['product_id' => $productId]);
        $rows = $stmt->fetchAll();

        $reviews = [];
        foreach ($rows as $row) {
            $review = new self();
            $review->id = $row['id'];
            $review->product_id = $row['product_id'];
            $review->user_id = $row['user_id'];
            $review->name = $row['name'];
            $review->description = $row['description'];
            $reviews[] = $review;
        }
        return $reviews;
    }

    //Создаю новый отзыв
    public function create(int $productId, int $userId, string $name, string $description): bool
    {
        $stmt = $this->pdo->prepare(
            "INSERT INTO reviews (product_id, user_id, name, description) 
             VALUES (:product_id, :user_id, :name, :description)"
        );
        return $stmt->execute([
            'product_id'   => $productId,
            'user_id'      => $userId,
            'name'         => $name,
            'description'  => $description
        ]);
    }
    public function getId(): int
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function getProductId(): int
    {
        return $this->product_id;
    }

    public function getUserId(): int
    {
        return $this->user_id;
    }

}