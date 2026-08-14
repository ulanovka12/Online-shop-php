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
        if ($this->authService->check()) {
            header("Location: /login");
            exit();
        }
        require_once '../Views/add_product_form.php';
    }

    public function product(ProductRequest $request)
    {
//        // Проверяем, что это POST-запрос и передан product_id
//        if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !isset($_POST['product_id'])) {
//            header("Location: /catalog");
//            exit();
//        }

        $user = $this->authService->getCurrentUser();
//        var_dump($user);
        if (!$user) {
            header('Location: /login');
            exit;
        }
        $amount = 1; // всегда добавляем одну единицу

        $request->validateProduct();

        // Проверяем, есть ли уже этот товар у пользователя

        $existing = $this->user_productsModel->getUserProduct($request->getProductId(), $user->getId());


        if ($existing === null) {
            // Если нет – вставляем новую запись с количеством 1
            $this->user_productsModel->insertUserProduct($user->getId(), $request->getProductId(), $request->getAmount());
        } else {
            // Если есть – увеличиваем количество на 1
            $newAmount = $existing->getAmount() + 1;
            $this->user_productsModel->updateUserProduct($newAmount, $user->getId(), $request->getProductId());
        }

        header("Location: /catalog");
        exit();
    }

    public function catalog()
    {

        $user = $this->authService->getCurrentUser() ?? 1; // если нет сессии – гость

        // Получаем все товары
        $products = $this->productModel->getAll();

        if (!$user) {
            $this->authService->check();
            header("Location: /login");
            exit(); // редирект на логин
        } else {
            $userId = is_object($user) ? $user->getId() : (int)$user;
            $userProducts = $this->user_productsModel->getAllUserProductByUserId($userId);
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