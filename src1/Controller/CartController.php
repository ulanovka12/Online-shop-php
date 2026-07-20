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
        $this->product = new Product();
        $this->userProducts = new User_products();
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
            $productId = $userProduct->getProductId();

            $product = $this->product->getOneById($productId);
//            print_r($product);

            //проверяем найден ли продукт
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

    public function updateCart()
    {
        if (session_status() !== PHP_SESSION_ACTIVE) {
            session_start();
        }

        if (!isset($_SESSION['userId'])) {
            header('Location: /login');
            exit();
        }

        $userId = $_SESSION['userId'];
        $productId = (int) ($_POST['productId'] ?? 0);
        $amount = (int) ($_POST['amount'] ?? 0);

        if ($productId > 0 && $amount > 0) {
            $this->userProducts->getUpdateProduct($userId, $productId, $amount);
        }

        header('Location: /cart');
        exit();
    }

    public function removeFromCart()
    {
        if (session_status() !== PHP_SESSION_ACTIVE) {
            session_start();
        }

        if (!isset($_SESSION['userId'])) {
            header('Location: /login');
            exit();
        }

        $userId = $_SESSION['userId'];
        $productId = (int) ($_POST['productId'] ?? 0);

        if ($productId > 0) {
            $this->userProducts->deleteByProductId($userId, $productId);
        }
        header('Location: /cart');
        exit();
    }

    public function clearCart()
    {
        if (session_status() !== PHP_SESSION_ACTIVE) {
            session_start();
        }

        if (!isset($_SESSION['userId'])) {
            header('Location: /login');
            exit();
        }
        $userId = $_SESSION['userId'];
        $this->userProducts->deleteByUserId($userId);
        header('Location: /cart');
        exit();
    }
}