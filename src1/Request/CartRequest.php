<?php

namespace Request;

class CartRequest
{

    public function __construct(private array $data)
    {}

    public function getProductId():int
    {
        return (int)($this->data['product_id'] ?? 0);
    }
    public function getAmount():int
    {
        return (int)($this->data['amount'] ?? 0);
    }

    public function CartValidate(): array
    {
        $errors = [];

        if ($this->getProductId() <= 0) {
            $errors['product_id'] = 'Неверный ID товара';
        }

        if ($this->getAmount() <= 0) {
            $errors['amount'] = 'Количество должно быть больше нуля';
        }
        return $errors;
    }
}