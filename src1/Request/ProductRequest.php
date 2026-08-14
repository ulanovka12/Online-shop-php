<?php

namespace Request;

use Model\Product;

class ProductRequest
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

    public function validateProduct()
    {
        $errors = [];

        if (isset($this->data['product_id'])) {

            $productId = (int)$this->data['product_id'];

            $productData = $this->productModel->ValidateProductData($productId);


            if ($productData === false) {
                $errors['product_id'] = 'Продукт не найден';
            }
            if (isset($this->data['amount'])) {
                $amount = (int)$this->data['amount'];
                if ($amount <= 0) {
                    $errors['amount'] = 'Количество товара должно быть больше 0.';
                }
            }
        } else {
            $errors['product_id'] = 'id продукта должен быть указан';
        }
        return $errors;
    }



}