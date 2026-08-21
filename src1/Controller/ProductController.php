<?php

namespace Controller;

use Model\Product;
use Model\User_products;
use Request\ProductRequest;

class ProductController extends BaseController
{

    private Product $productModel;
    private User_Products $user_productsModel;

    public function __construct()
    {
        parent::__construct();
        $this->productModel = new Product();
        $this->user_productsModel = new User_products();
    }

    public function getProducts()
    {
        if (!$this->authService->check()) {
            header("Location: /login");
            exit();
        }
        require_once '../Views/add_product_form.php';
    }

    public function product(ProductRequest $request)
    {

        $user = $this->authService->getCurrentUser();

        if (!$user) {
            header('Location: /login');
            exit;
        }

       $errors = $request->validateProduct();

        if (!empty($errors)) {
            header("Location: /catalog");
            exit();
        }

        $amount = $request->getAmount();

        // Проверяем, есть ли уже этот товар у пользователя
        $existing = $this->user_productsModel->getUserProduct($request->getProductId(), $user->getId());


        if ($existing === null) {
            // Если нет – вставляем новую запись с количеством 1
            $this->user_productsModel->insertUserProduct($user->getId(), $request->getProductId(), $amount);
        } else {
            // Если есть – увеличиваем количество на 1
            $newAmount = $existing->getAmount() + $amount;
            $this->user_productsModel->updateUserProduct($newAmount, $user->getId(), $request->getProductId());
        }

        header("Location: /catalog");
        exit();
    }

    public function catalog()
    {

        $user = $this->authService->getCurrentUser(); // если нет сессии – гость

        // Получаем все товары

        $products = $this->productModel->getAll();

        if ($user) {
            $userProducts = $this->user_productsModel->getAllUserProductByUserId($user->getId());
        } else {
            $userProducts = [];
        }

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