<?php

namespace DTO;

class CartCreateDTO
{

    public function __construct
    (
        private readonly int $user_id,
        private readonly int $product_id,
        private readonly int $amount
    )
    {

    }
    public function getUserId(): int
    {
        return $this->user_id;
    }

    public function getProductId(): int
    {
        return $this->product_id;
    }

    public function getAmount(): int
    {
        return $this->amount;
    }


}