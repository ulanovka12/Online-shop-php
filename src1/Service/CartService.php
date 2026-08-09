<?php

namespace Service;

use Model\User_products;

class CartService
{
    private User_products $user_productsModel;
    private AuthService $authService;

    public function __construct()
    {
        $this->user_productsModel = new User_products();
        $this->authService = new AuthService();
    }


    public function addProduct(int $productId, int $userId, int $amount)
    {
        // Проверяем, есть ли уже запись
        $existing = $this->user_productsModel->getUserProduct($productId, $userId);

        if ($existing === null) {
            // Вставляем новую запись с количеством 1
            $this->user_productsModel->insertUserProduct($userId, $productId, $amount);
        } else {
            // Увеличиваем количество на 1
            $newAmount = $existing->getAmount() + $amount;
            $this->user_productsModel->updateUserProduct($newAmount, $userId, $productId);
        }
    }

    // Уменьшить на 1 (или удалить, если станет 0)

    public function decreaseProductFromCart(int $productId, int $userId, int $amount)
    {
        error_log("=== decreaseProductFromCart ===");
        error_log("productId: $productId, userId: $userId, amount: $amount");

        $existing = $this->user_productsModel->getUserProduct($productId, $userId);
        error_log("existing: " . ($existing ? 'found' : 'null'));

        if ($existing) {
            $oldAmount = $existing->getAmount();
            $newAmount = $oldAmount - $amount;
            error_log("oldAmount: $oldAmount, newAmount: $newAmount");

            if ($newAmount > 0) {
                $result = $this->user_productsModel->updateUserProduct($newAmount, $userId, $productId);
                error_log("update result: " . ($result ? 'true' : 'false'));
            } else {
                $result = $this->user_productsModel->deleteUserProducts($userId, $productId);
                error_log("delete result: " . ($result ? 'true' : 'false'));
            }
        } else {
            error_log("Запись не найдена, ничего не делаем");
        }
    }
//    public function decreaseProductFromCart(int $productId, int $userId, int $amount)
//    {
//        // Получаем текущую запись
//        $existing = $this->user_productsModel->getUserProduct($productId, $userId);
//
//        if ($existing) {
//            $newAmount = $existing->getAmount() - $amount;
//            if ($newAmount > 0) {
//                $this->user_productsModel->updateUserProduct($newAmount, $userId, $productId);
//            } else {
//                $this->user_productsModel->deleteUserProducts($userId, $productId);
//            }
//        }
//    }

}