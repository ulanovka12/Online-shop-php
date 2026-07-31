<?php

namespace Controller;

use Model\Product;
use Model\User_products;

class CartController
{
    private Product $product;
    private User_products $user_productsModel;

    public function __construct()
    {
        $this->product = new Product();
        $this->user_productsModel = new User_products();
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

        $userProducts = $this->user_productsModel->getByUserId($userId);
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
            $this->user_productsModel->getUpdateProduct($userId, $productId, $amount);
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
            $this->user_productsModel->deleteByProductId($userId, $productId);
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
        $this->user_productsModel->deleteByUserId($userId);
        header('Location: /cart');
        exit();
    }

    public function addProduct(int $productId, int $userId)
    {
        // Проверяем, есть ли уже запись
        $existing = $this->user_productsModel->getUserProduct($productId, $userId);

        if ($existing === null) {
            // Вставляем новую запись с количеством 1
            $this->user_productsModel->insertUserProduct($userId, $productId, 1);
        } else {
            // Увеличиваем количество на 1
            $newAmount = $existing->getAmount() + 1;
            $this->user_productsModel->updateUserProduct($newAmount, $userId, $productId);
        }
    }

    // Уменьшить на 1 (или удалить, если станет 0)
    public function decreaseProductFromCart()
    {
        // Запускаем сессию
        if (session_status() !== PHP_SESSION_ACTIVE) {
            session_start();
        }

        // Проверяем, что это POST-запрос и передан product_id
        if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !isset($_POST['product_id'])) {
            header("Location: /catalog");
            exit();
        }

        $userId = $_SESSION['userId'] ?? 1;   // или фиксированное значение
        $productId = (int)$_POST['product_id'];

        // Получаем текущую запись
        $existing = $this->user_productsModel->getUserProduct($productId, $userId);

        if ($existing) {
            $newAmount = $existing->getAmount() - 1;
            if ($newAmount > 0) {
                $this->user_productsModel->updateUserProduct($newAmount, $userId, $productId);
            } else {
                $this->user_productsModel->deleteUserProducts($userId, $productId);
            }
        }

        header("Location: /catalog");
        exit();
    }

}