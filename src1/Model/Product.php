<?php

namespace Model;

class Product extends Model
{
    private int $id;
    private string $name;
    private string $description;
    private string $price;
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
    public function getAll(): array|false
    {
        $stmt = $this->pdo->query("SELECT * FROM products");
        $productsData = $stmt->fetchAll();

        if ($productsData === false) {
            return false;
        }

        $products = [];
        foreach ($productsData as $productData) {
            $obj = new self();

            // Заполняем свойства объекта
            $obj->id = (int)$productData['id'];
            $obj->name = (string)$productData['name'];
            $obj->price = (float)$productData['price'];
            $obj->description = (string)$productData['description'];

            $products[] = $product;
        }

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

}