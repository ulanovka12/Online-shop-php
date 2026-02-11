<?php

namespace Model;


class Order extends Model
{
    public function create(string $contactName, string $contactPhone, string $comment, string $address)
    {
        $this->pdo->prepare("INSERT INTO orders (contact_name, contact_phone, comment, address, user_id) VALUES (:contact_name, :contact_phone, :comment, :address, :user_id)");
    }

}

