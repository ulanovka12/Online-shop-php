<?php

namespace Controller;

use Model\Order;
use Model\Product;
use Model\User_products;

class CartController
{
    private Order $order;
    private Product $product;
    private User_products $userProducts;

    public function __construct()
    {
        $this->userProducts = new User_products();
        $this->product = new Product();
        $this->order = new Order();
    }

    public function cart()
    {
        if (session_status() !== PHP_SESSION_ACTIVE) {
            session_start();
        }

        if (!isset($_SESSION['userId'])) {
            header('Location: /login');
            exit();
        }

        $userId = $_SESSION['userId'];

        $userProducts = $this->order->getAllByUserId($userId);
//        print_r($userId);
//        print_r($userProducts);
        $products = [];

        foreach ($userProducts as $userProduct) {
            $productId = $userProduct->getId();

            $product = $this->product->getOneById($productId);
//            print_r($product);

            if ($product !== null) {
                $product->setAmount($userProduct->getAmount());
                $products[] = $product;
            } else {
                error_log("продукт не найден " . $productId);
            }
        }
        require_once '../Views/cart.php';

        return $products;
    }

}