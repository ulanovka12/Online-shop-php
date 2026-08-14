<?php

namespace Request;

class CartRequest
{

    public function __construct(private array $data)
    {}

    public function getProductId()
    {
        return $this->data['productId'];
    }
    public function getAmount()
    {
        return $this->data['amount'];
    }

    public function CartValidate(): array
    {
        $this->data = [];

        if ($this->data <= 0) {
            $this->data['productId'] = 'Неверный ID товара';
        }

        if ($this->data <= 0) {
            $this->data['amount'] = 'Количество должно быть больше нуля';
        }
        return $this->data;
    }
}