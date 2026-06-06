<?php

namespace Controller;

use Model\Product;
use Model\User_products;

class CartController
{
    private Product $product;
    private User_products $userProducts;

    public function __construct()
    {
        $this->userProducts = new User_products();
        $this->product = new Product();
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

        $userProducts = $this->userProducts->getByUserId($userId);
//        print_r($userId);
//        print_r($userProducts);
        $products = [];

        foreach ($userProducts as $userProduct) {
            $productId = $userProduct['product_id'];

            $product = $this->product->ForGetCart($productId);
//            print_r($product);

            if ($product) {
                $product->setAmount($userProduct['amount']);
                $products[] = $product;
            }
        }
        require_once '../Views/cart.php';

        return $products;
    }

}