<?php

namespace Controller;

use Model\Product;
use Model\User_products;

class ProductController extends BaseController
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
        if ($this->check()) {
            header("Location: /login");
            exit();
        }
        require_once '../Views/add_product_form.php';
    }

    public function product()
    {
        // Проверяем, что это POST-запрос и передан product_id
        if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !isset($_POST['product_id'])) {
            header("Location: /catalog");
            exit();
        }

        $userId = $this->getCurrentUserId() ?? 1;  // если нет сессии – используем гостя (1)
        $productId = (int)$_POST['product_id'];
        $amount = 1; // всегда добавляем одну единицу

        // Проверяем, есть ли уже этот товар у пользователя
        $existing = $this->user_productsModel->getUserProduct($productId, $userId);

        if ($existing === null) {
            // Если нет – вставляем новую запись с количеством 1
            $this->user_productsModel->insertUserProduct($userId, $productId, $amount);
        } else {
            // Если есть – увеличиваем количество на 1
            $newAmount = $existing->getAmount() + 1;
            $this->user_productsModel->updateUserProduct($newAmount, $userId, $productId);
        }

        header("Location: /catalog");
        exit();
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
        // Сессия
        if (session_status() !== PHP_SESSION_ACTIVE) {
            session_start();
        }
        $userId = $_SESSION['userId'] ?? 1; // если нет сессии – гость

        // Получаем все товары
        $products = $this->productModel->getAll();

        // Получаем корзину пользователя
        $userProducts = $this->user_productsModel->getByUserId($userId);

        // Превращаем корзину в массив [product_id => amount]
        $amounts = [];
        foreach ($userProducts as $up) {
            $amounts[$up->getProductId()] = $up->getAmount();
        }

        // Проставляем количество каждому товару
        foreach ($products as $product) {
            $productId = $product->getId();
            $amount = $amounts[$productId] ?? 0;
            $product->setAmount($amount);
        }

        // Передаём в представление
        require_once '../Views/catalog_page.php';
    }
}