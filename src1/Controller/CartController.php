<?php

namespace Controller;

use DTO\CartCreateDTO;
use DTO\OrderCreateDTO;
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
        if (!$user) {
            header('Location: /login');
            exit;
        }

        $productId = ($_POST['product_id']);
        $amount = ($_POST['amount']);

        if ($productId <= 0 || $amount <= 0) {
            header('Location: /catalog');
            exit;
        }

        $dto = new CartCreateDTO($user->getId(), $productId, $amount);
        $this->cartService->addProduct($dto);

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

        if (!isset($_POST['product_id'])) {
            header('Location: /catalog');
            exit;
        }

        $productId = (int)$_POST['product_id'];
        $amount = isset($_POST['amount']) ? (int)$_POST['amount'] : 1;

        if ($productId <= 0 || $amount <= 0) {
            header('Location: /catalog');
            exit;
        }
        $dto = new CartCreateDTO($user->getId(), $productId, $amount);

        $this->cartService->decreaseProductFromCart($dto);

        header('Location: /catalog');
        exit;
    }
}