<?php

namespace Request;

use Model\Product;

class AddProductRequest
{
    private Product $productModel;

    public function __construct(private array $data)
    {
        $this->productModel = new Product();
    }

    public function getProductId(): int
    {
        return $this->data['product_id'];

    }

    public function getAmount(): int
    {
        return $this->data['amount'];
    }

    public function validate(): array
    {
        $errors = [];

        if (!isset($this->data['product_id'])) {
            $errors['product_id'] = 'Введите ID продукта';
        } else {
            $productId = $this->data['product_id'];
            if (!is_numeric($productId)) {
                $errors['product_id'] = 'ID продукта должен содержать только цифры';
            } else {
                if ($this->data['product_id'] <= 0) {
                    $errors['product_id'] = 'ID продукта должен быть положительным числом';
                } else {
                    $product = $this->productModel->getOneById($this->data['product_id']);
                    if (!$product) {
                        $errors['product_id'] = 'Продукт с таким ID не найден';
                    }
                }
            }
        }
        if (!isset($this->data['amount'])) {
            $errors['amount'] = 'Введите количество';
        } else {
            $amount = $this->data['amount'];
            if (!is_numeric($amount)) {
                $errors['amount'] = 'Количество должно быть числом';
            } else {
                $amountInt = (int)$amount;
                if ($amountInt <= 0) {
                    $errors['amount'] = 'Количество должно быть больше нуля';
                }
            }
        }
        return $errors;
    }
}