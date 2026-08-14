<?php

namespace Request;

use Service\AuthService;
use Model\Reviews;

class ReviewsRequest
{
    public function __construct(private array $data)
    {}

    public function getProductId(): int
    {
        return $this->data['product_id'];
    }
    public function getDescription(): string
    {
        return $this->data['description'];
    }

    public function validate(){

        if (!isset($this->data['product_id'])) {
            return null;
        }

        $id = (int) $this->data['product_id'];
        if ($id <= 0) {
            return null;
        }

        return $id;
    }
    public function validateDescription(){
        if (!isset($this->data['description'])) {
            return null;
        }

        $description = trim($this->data['description']);
        if (mb_strlen($description) < 3) {
            return null;
        }

        return $description;
    }

}