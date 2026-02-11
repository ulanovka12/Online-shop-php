<?php

//namespace Controller;
//
//use Model\Product;
//
//class ProductController
//{
//
//    private Product $productModel;
//
//    public function __construct()
//    {
//        $this->productModel = new Product();
//    }
//
//    public function getProducts()
//    {
//        if (session_status() !== PHP_SESSION_ACTIVE) {
//            session_start();
//        }
//        if (!isset($_SESSION['userId'])) {
//            header("Location: ./login");
//            exit();
//        }
//        require_once '../Views/add_product_form.php';
//    }
//
//    public function product()
//    {
//        if (session_status() !== PHP_SESSION_ACTIVE) {
//            session_start();
//        }
//
//        $errors = $this->validateProduct($_POST);
//
//        if (empty($errors)) { // Исправлено: блок должен выполняться только если ошибок НЕТ
//            $userId = $_SESSION['userId'];
//            $productId = $_POST['product_id'];
//            $amount = $_POST['amount'];
//
//            $productId = (int)$productId;
//            $data = $this->productModel->getByProductId($userId, $productId);
//
//            if ($data === false) {
//                $this->productModel->getByProduct($userId, $productId, $amount);
//            } else {
//                $newAmount = $data['amount'] + $amount;
//                $this->productModel->getUpdateProduct($userId, $productId, $newAmount);
//            }
//            header("Location: /catalog");
//            exit();
//        } else {
//            // Если есть ошибки, показываем форму снова
//            require_once '../Views/add_product_form.php';
//            print_r($errors);
//        }
//    }
//
//    private function validateProduct($data)
//    {
//        $errors = [];
//
//        if (isset($data['product_id'])) {
//            $productId = (int)$data['product_id'];
//            $productData = $this->productModel->ValidateProductData($productId);
//
//            if ($productData === false) {
//                $errors['product_id'] = 'Продукт не найден';
//            }
//
//            if (isset($data['amount'])) {
//                $amount = (int)$data['amount'];
//                if ($amount <= 0) {
//                    $errors['amount'] = 'Количество товара должно быть больше 0.';
//                }
//            }
//        } else {
//            $errors['product_id'] = 'id продукта должен быть указан';
//        }
//        return $errors;
//    }
//
//    public function catalog()
//    {
//        if (session_status() !== PHP_SESSION_ACTIVE) {
//            session_start();
//        }
//
//        if (!isset($_SESSION["userId"])) {
//            header('Location: /login');
//            exit();
//        }
//
//        $products = $this->productModel->getByCatalog();
//
//        require_once '../Views/catalog_page.php';
//    }
//}

namespace Controller;

use Model\Product;

class ProductController
{

    private Product $productModel;

    public function __construct()
    {
        $this->productModel = new Product();
    }

    public function getProducts()
    {
        if (session_status() !== PHP_SESSION_ACTIVE) {
            session_start();
        }
        if (!isset($_SESSION['userId'])) {
            header("Location: ./login");
            exit();
        }
        require_once '../Views/add_product_form.php';
    }

    public function product()
    {

        if (session_status() !== PHP_SESSION_ACTIVE) {
            session_start();
        }

//        var_dump([
//            'session_id' => session_id(),
//            'cookie' => $_COOKIE['PHPSESSID'] ?? null,
//            'session' => $_SESSION ?? null,
//        ]);
//        exit;

        $errors = $this->validateProduct($_POST); // Передаем $_POST в метод

        if (empty($errors)) {

            $userId = $_SESSION['userId'];
            $productId = $_POST['product_id'];
            $amount = $_POST['amount'];

            $productId = (int)$productId;
            $data = $this->productModel->getByProductId($userId, $productId);

            if ($data === false) {
                $this->productModel->getByProduct($userId,$productId,$amount);
            } else {
                $newAmount = $data['amount'] + $amount;

                $this->productModel->getUpdateProduct($userId, $productId, $newAmount);
            }
            header("Location: /catalog");
            exit();
        }
        require_once '../Views/add_product_form.php';
//        // Если есть ошибки, можно вернуть их или обработать
//        //return $errors;
//        print_r($errors);
//        exit();
    }

    private function validateProduct($data) // параметр $data
    {
        $errors = [];

        if (isset($data['product_id'])) {

            $productId = (int)$data['product_id'];
            $productData = $this->productModel->ValidateProductData($productId);


            //            print_r($productData);
            // Изменил имя переменной чтобы не конфликтовало

            $productData = (int)$productData;

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

        if (!isset($_SESSION["userId"])) {
            header('Location: /login');
            exit();
        }

//        require_once '../Model/Product.php';
//        $productModel = new Product();

        $products =$this->productModel->getByCatalog();

        require_once '../Views/catalog_page.php';
    }
}