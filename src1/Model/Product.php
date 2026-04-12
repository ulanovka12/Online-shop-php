<?php

namespace Model;

class Product extends Model
{
    private int $id;
    private string $name;
    private string $description;
    private int $price;
    private string $image_url;

    private int $amount;

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
    public function getAll(): array|null
    {
        $stmt = $this->pdo->query("SELECT * FROM products");
        $products = $stmt->fetchAll();

        if ($products === false) {
            return null;
        }

        $product = [];

        foreach ($products as $row) {

            $obj = new self();

            $obj->id = $row['id'];
            $obj->name = $row['name'];
            $obj->description = $row['description'];
            $obj->price = $row['price'];
            $obj->image_url = $row['image_url'];

            $product[] = $obj;
        }

        return $product;
    }


    public function getByProductId(int $userId, int $productId): self|null
    {
        $stmt = $this->pdo->prepare("SELECT * FROM user_products WHERE product_id = :productId AND user_id = :userId");
        $stmt->execute(['productId' => $productId, 'userId' => $userId]);

        $data = $stmt->fetch();

        $obj = new self();

        $obj->id = $data['id'];
        $obj->name = $data['name'];
        $obj->description = $data['description'];
        $obj->price = $data['price'];
        $obj->image_url = $data['image_url'];

        return $obj;
    }

    public function getByProduct(int $userId,int $productId,int $amount): self|null
    {

        $stmt = $this->pdo->prepare("INSERT INTO user_products (user_id, product_id, amount) VALUES (:userId, :productId, :amount)");
        $stmt->execute(['userId' => $userId, 'productId' => $productId, 'amount' => $amount]);

        $data = $stmt->fetch();

        if($data === null){
            return null;
        }
        $obj = new self();

        $obj->amount = $data['amount'];
        $obj->id =(int) $data['id'];
        $obj->name = $data['name'];
        $obj->description = $data['description'];
        $obj->price = $data['price'];
        $obj->image_url = $data['image_url'];
        return $obj;
    }
    public function getUpdateProduct(int $userId,int $productId, int $newAmount):self|null
    {

        $stmt = $this->pdo->prepare("UPDATE user_products SET amount = :amount WHERE user_id = :userId and product_id = :productId");
        $stmt->execute(['userId' => $userId, 'productId' => $productId, 'amount' => $newAmount]); // Используем $newAmount
        $data = $stmt->fetch();

        if($data === false){
            return null;
        }

        $obj = new self();
        $obj->id = $data['id'];
        $obj->name = $data['name'];
        $obj->description = $data['description'];
        $obj->price = $data['price'];
        $obj->image_url = $data['image_url'];
        return $obj;
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
        return $this->image_url;
    }
    public function getAmount(): int
    {
        return $this->amount;
    }

}