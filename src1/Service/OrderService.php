<?php

namespace Service;

use Model\Order;
use Model\order_products;
use Model\User_products;

class OrderService
{
    private Order $OrderModel;
    private user_products $user_productsModel;
    private order_products $order_productsModel;

    public function __construct()
    {
        $this->OrderModel = new Order();
        $this->user_productsModel = new user_products();
        $this->order_productsModel = new order_products();
    }

    public function createOrder(int $userId, array $data): int
    {


        // Создаём заказ
        $order = $this->OrderModel->create(
            $data['contact_name'],
            $data['contact_phone'],
            $data['comment'],
            $data['address'],
            $userId
        );

        $orderId = $order->getId();

        // Получаем товары из корзины пользователя
        $userProducts = $this->user_productsModel->getAllUserProductByUserId($userId);

        foreach ($userProducts as $userProduct) {
            $this->order_productsModel->create1(
                $orderId,
                $userProduct->getProductId(),
                $userProduct->getAmount()
            );
        }

        // Очищаем корзину
        $this->user_productsModel->deleteByUserId($userId);

        return $orderId;
    }
}