<?php

namespace Controller;

use Model\Product;
use Model\User_products;
use Service\CartService;

class CartController extends BaseController
{
    private Product $product;
    private User_products $user_productsModel;
    private CartService $cartService;


    public function __construct()
    {
        parent::__construct();
        $this->product = new Product();
        $this->user_productsModel = new User_products();
        $this->cartService = new CartService();

    }

    public function cart()
    {
        if (!$this->authService->check()) {
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
        $this->user_productsModel->deleteByUserId($user);
        header('Location: /cart');
        exit();
    }

    public function addProduct()
    {
        $user = $this->authService->getCurrentUser();

        // Получаем ID товара из запроса
        $productId = (int) $_POST['productId'];
        $amount = (int) $_POST['amount']; // количество, которое хочет добавить пользователь

        $this->cartService->addProduct($productId, $user->getId(), $amount);

        header('Location: /catalog');
        exit;
    }

    public function decreaseProductFromCart()
    {
        $user = $this->authService->getCurrentUser();
        if (!$user) {
            header('Location: /login');
            exit;
        }

        // Проверка на наличие product_id
        if (!isset($_POST['product_id'])) {
            $_SESSION['error'] = 'Не передан идентификатор товара';
            header('Location: /catalog');
            exit;
        }

        $productId = (int) $_POST['product_id'];
        // Если amount не передан, уменьшаем на 1
        $amount = isset($_POST['amount']) ? (int) $_POST['amount'] : 1;

        if ($amount <= 0) {
            $_SESSION['error'] = 'Количество должно быть больше нуля';
            header('Location: /catalog');
            exit;
        }

        $this->cartService->decreaseProductFromCart($productId, $user->getId(), $amount);

        header('Location: /catalog');
        exit;
    }
}