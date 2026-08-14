<?php

namespace Service;

use Model\User_products;
use DTO\CartCreateDTO;

class CartService
{
    private User_products $user_productsModel;

    public function __construct()
    {
        $this->user_productsModel = new User_products();
    }


    public function addProduct(CartCreateDTO $data): int
    {
        $newAmount = $data->getAmount();
        // Проверяем, есть ли уже запись
        $existing = $this->user_productsModel->getUserProduct($data->getProductId(), $data->getUserId());

        if ($existing === null) {
            // Вставляем новую запись с количеством 1
            $this->user_productsModel->insertUserProduct($data->getUserId(), $data->getProductId(), $data->getAmount());
        } else {
            // Увеличиваем количество на 1
            $newAmount = $existing->getAmount() + $data->getAmount();
            $this->user_productsModel->updateUserProduct($newAmount, $data->getUserId(), $data->getProductId());
        }
        return $newAmount;
    }


    // Уменьшить на 1 (или удалить, если станет 0)
    public function decreaseProductFromCart(CartCreateDTO $data): int
    {
        $newAmount = $data->getAmount();
        // Получаем текущую запись
        $existing = $this->user_productsModel->getUserProduct($data->getProductId(), $data->getUserId());

        if ($existing) {
            $newAmount = $existing->getAmount() - $data->getAmount();
            if ($newAmount > 0) {
                $this->user_productsModel->updateUserProduct($newAmount, $data->getUserId(), $data->getProductId());
            } else {
                $this->user_productsModel->deleteUserProducts($data->getUserId(), $data->getProductId());
            }
        }
        return $newAmount;
    }
}