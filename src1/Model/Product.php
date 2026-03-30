<?php

namespace Model;

class Product extends Model
{
    private int $id;
    private string $name;
    private string $description;
    private string $price;
    private string $image_url;
//    public function getAll(): array|false
//    {
//
//        $stmt = $this->pdo->query("SELECT * FROM products");
//        $products = $stmt->fetchAll();
//
//        //Должен быть какой-то цикл, чтобы преобразовать из массива в объект
//        foreach ($products as $product) {
//        }
//
//        return $products;
//    }
    public function getAll(): self|null
    {
        $stmt = $this->pdo->query("SELECT * FROM products");
        $products = $stmt->fetchAll();

        if ($products === false) {
            return null;
        }

//        $products = [];

        foreach ($products as $row) {
            $obj = new self();
            $obj->image_url = (string) $row['image_url'];
            $obj->id = (int)$row['id'];
            $obj->name = (string)$row['name'];
            $obj->price = (int)$row['price'];
            $obj->description = (string)$row['description'];
        }

        return $obj;
    }


    public function getByProductId(int $userId, int $productId): array
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

    public function getPrice(): string
    {
        return $this->price;
    }
    public function getImage_url(): string
    {
        return $this->price;
    }

}