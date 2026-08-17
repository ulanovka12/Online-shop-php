<?php

namespace Service;

use Model\Order;
use Model\order_products;
use Model\User_products;

use DTO\OrderCreateDTO;

class OrderService
{
    private Order $OrderModel;
    private user_products $user_productsModel;
    private order_products $order_productsModel;

    private AuthService $AuthService;

    public function __construct()
    {
        $this->OrderModel = new Order();
        $this->user_productsModel = new user_products();
        $this->order_productsModel = new order_products();
        $this->AuthService = new AuthService();
    }

    public function createOrder(OrderCreateDTO $data):int
    {

        $user = $this->AuthService->getCurrentUser();
        // Создаём заказ
        $order = $this->OrderModel->create(
            $data->getContactName(),
            $data->getContactPhone(),
            $data->getComment(),
            $data->getAddress(),
            $user->getId()
        );

        $orderId = $order->getId();

        // Получаем товары из корзины пользователя
        $userProducts = $this->user_productsModel->getAllUserProductByUserId($user->getId());

        foreach ($userProducts as $userProduct) {
            $this->order_productsModel->create1(
                $orderId,
                $userProduct->getProductId(),
                $userProduct->getAmount()
            );
        }

        // Очищаем корзину
        $this->user_productsModel->deleteByUserId($user->getId());

        return $orderId;
    }
}