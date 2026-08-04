<?php

namespace Controller;

use Model\Product;
use Model\User_products;
use Service\AuthService;

class CartController extends BaseController
{
    private Product $product;
    private User_products $user_productsModel;


    public function __construct()
    {
        parent::__construct();
        $this->product = new Product();
        $this->user_productsModel = new User_products();

    }

    public function cart()
    {
        if ($this->authService->check()) {
            header('Location: /login');
            exit();
        }

        $user = $this->authService->getCurrentUser();

        $userProducts = $this->user_productsModel->getAllUserProductByUserId($user->getId());
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
        if ($this->authService->check()) {
            header('Location: /login');
            exit();
        }

        $user = $this->authService->getCurrentUser();
        $productId = (int) ($_POST['productId'] ?? 0);
        $amount = (int) ($_POST['amount'] ?? 0);

        if ($productId > 0 && $amount > 0) {
            $this->user_productsModel->getUpdateProduct($user->getId(), $productId, $amount);
        }

        header('Location: /cart');
        exit();
    }

    public function removeFromCart()
    {
        if ($this->authService->check()) {
            header('Location: /login');
            exit();
        }

        $user = $this->authService->getCurrentUser();
        $productId = (int) ($_POST['productId'] ?? 0);

        if ($productId > 0) {
            $this->user_productsModel->deleteByProductId($user->getId(), $productId);
        }
        header('Location: /cart');
        exit();
    }

    public function clearCart()
    {
        if ($this->authService->check()) {
            header('Location: /login');
            exit();
        }
        $user = $this->authService->getCurrentUser();
        $this->user_productsModel->deleteByUserId($user->getId());
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
        // Проверяем, что это POST-запрос и передан product_id
        if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !isset($_POST['product_id'])) {
            header("Location: /catalog");
            exit();
        }

        $userId = $this->authService->getCurrentUser() ?? 1;   // или фиксированное значение
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