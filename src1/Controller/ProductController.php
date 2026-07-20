<?php

namespace Controller;

use Model\Product;
use Model\User_products;

class ProductController
{

    private Product $productModel;
    private User_Products $user_productsModel;

    public function __construct()
    {
        $this->productModel = new Product();
        $this->user_productsModel = new User_products();
    }

    public function getProducts()
    {
        if (session_status() !== PHP_SESSION_ACTIVE) {
            session_start();
        }
        if (!isset($_SESSION['userId'])) {
            header("Location: /login");
            exit();
        }
        require_once '../Views/add_product_form.php';
    }

    public function product()
    {

        if (session_status() !== PHP_SESSION_ACTIVE) {
            session_start();
        }

        $errors = $this->validateProduct($_POST); // Передаем $_POST в метод

        if (empty($errors)) {

            $userId = $_SESSION['userId'];
            $productId = $_POST['product_id'];
            $amount = $_POST['amount'];

            $data = $this->user_productsModel->getByProductId($userId, $productId);

            if ($data === null) {
                $this->user_productsModel->getByProduct($userId,$productId,$amount);
            } else {
                $newAmount = $amount + $data->getAmount();

                $this->user_productsModel->getUpdateProduct($userId, $productId, $newAmount);

            }
            header("Location: /catalog");
            exit();
        }
        require_once '../Views/add_product_form.php';
    }

    private function validateProduct($data)
    {
        $errors = [];

        if (isset($data['product_id'])) {

            $productId = (int)$data['product_id'];

            $productData = $this->productModel->ValidateProductData($productId);


            if ($productData === false) {
                $errors['product_id'] = 'Продукт не найден';
            }
            if (isset($data['amount'])) {
                $amount = (int)$data['amount'];
                if ($amount <= 0) {
                    $errors['amount'] = 'Количество товара должно быть больше 0.';
                }
            }
        } else {
            $errors['product_id'] = 'id продукта должен быть указан';
        }
        return $errors;
    }

    public function catalog()
    {
        if (session_status() !== PHP_SESSION_ACTIVE) {
            session_start();
        }

        if (!isset($_SESSION['userId'])) {
            header('Location: /login');
            exit();
        }

        $products = $this->productModel->getAll();

        require_once '../Views/catalog_page.php';
    }
}