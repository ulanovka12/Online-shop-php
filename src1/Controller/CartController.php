<?php

namespace Controller;

use Model\Cart;

class CartController
{
    private Cart $cartModel;

    public function __construct()
    {
        $this->cartModel = new Cart();
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

//        require_once '../Model/Cart.php';
//        $cartModel = new Cart();

        $userProducts = $this->cartModel->getByUserId($userId);

        print_r($userId);
        print_r($userProducts);

        $products = [];

        foreach ($userProducts as $userProduct) {
            $productId = $userProduct['product_id'];

            // Получаем информацию о продукте
            $product = $this->cartModel->ForGetCart($productId);
            print_r($product);

            if ($product) {
                $product['amount'] = $userProduct['amount'];
                $products[] = $product;
            }
        }

        echo "Products: ";
        print_r($products);

        require_once '../Views/cart.php';

        return $products;
    }

}