<?php

namespace Service;

use Model\Order;
use Model\Order_products;
use Model\Product;
use Model\User_products;

use DTO\CartCreateDTO;
use Service\Auth\AuthCookieService;
use Service\Auth\AuthInterface;
use Service\Auth\AuthSessionService;

class CartService
{
    private User_products $user_productsModel;
    private AuthInterface $AuthService;
    private Product $productModel;

    public function __construct()
    {
        $this->user_productsModel = new User_products();
        $this->AuthService = new AuthSessionService();
        $this->productModel = new Product();
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

    public function getSum():int
    {
        $user = $this->AuthService->getCurrentUser();

        $userProducts = $this->user_productsModel->getAllUserProductByUserId($user->getId());

        $total = 0;
        foreach ($userProducts as $userProduct) {
            $product = $this->productModel->getOneById($userProduct->getProductId());
            $total += $product->getPrice() * $userProduct->getAmount();
        }
        return $total;
    }
}