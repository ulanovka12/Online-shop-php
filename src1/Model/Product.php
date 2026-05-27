<?php

namespace Model;

class Product extends Model
{
    private int $id;
    private string $name;
    private string $description;
    private int $price;
    private string $image_url;

    public function getAll(): array|null
    {
        $stmt = $this->pdo->query("SELECT * FROM products");
        $products = $stmt->fetchAll();

        if ($products === null) {
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

    public function ValidateProductData(int $productId): ?self
    {
        $stmt = $this->pdo->prepare("SELECT * FROM products WHERE id = :productId");
        $stmt->execute(['productId' => $productId]);
        $productData = $stmt->fetch();


        if ($productData === null || $productData === false) {
            return null;
        }

        $obj = new self();

        $obj->id = $productData['id'];
        $obj->name = $productData['name'];
        $obj->description = $productData['description'];
        $obj->price = $productData['price'];
        $obj->image_url = $productData['image_url'];

        return $obj;
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

    public function getPrice(): int
    {
        return $this->price;
    }

    public function getImageUrl(): string
    {
        return $this->image_url;
    }
}