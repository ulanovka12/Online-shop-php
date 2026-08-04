<?php

namespace Controller;

use Model\Order;
use Model\Product;
use Model\Order_products;
use Model\User_products;


class OrderController extends BaseController
{
    private Order $OrderModel;
    private Order_products $order_productsModel;
    private Product $product;
    private User_products $user_productsModel;

    public function __construct()
    {
        parent::__construct();
        $this->OrderModel = new Order();
        $this->order_productsModel = new Order_products();
        $this->product = new Product();
        $this->user_productsModel = new User_products();
    }


    public function getCheckForm()
    {
        require_once './../Views/order_form.php';
    }

    public function handleCheckout()
    {

        if ($this->authService->check()) {
            header("Location: /login");
            exit();
        }

        $errors = $this->validate($_POST);

        if (empty($errors)) {
            $contactName = $_POST['contact_name'];
            $contactPhone = $_POST['contact_phone'];
            $comment = $_POST['comment'];
            $address = $_POST['address'];
            $userId = $_SESSION['userId'];

            $order = $this->OrderModel->create($contactName, $contactPhone, $comment, $address, $userId);

            $orderId = $order->getId();

            $userProducts = $this->user_productsModel->getAllUserProductByUserId($userId);

            foreach ($userProducts as $userProduct) {

                $productId = $userProduct->getProductId();
                $amount = $userProduct->getAmount();

                $this->order_productsModel->create1($orderId, $productId, $amount);

            }
            $this->user_productsModel->deleteByUserId($userId);

            header('Location: /users-orders');
            exit();
        } else {

            require_once './../Views/order_form.php';
        }
    }

    private function validate(array $data): array
    {
        $errors = [];

        $contactName = $this->validateName($data);
        if (!empty($contactName)) {
            $errors['contact_name'] = $contactName;
        }


        if (isset($data['contact_phone'])) {
            $contactPhone = $data['contact_phone'];

            if (!ctype_digit($contactPhone)) {
                $errors['contact_phone'] = 'Телефон должен содержать только цифры';
            } elseif (strlen($contactPhone) < 10) {
                $errors['contact_phone'] = 'Телефон должен содержать минимум 10 цифр';
            } elseif (strlen($contactPhone) > 15) {
                $errors['contact_phone'] = 'Телефон не может содержать больше 15 цифр';
            }
        } else {
            $errors['contact_phone'] = 'Телефон должен быть заполнен!';
        }
        // Валидация комментария
        if (isset($data['comment']) && !empty($data['comment'])) {
            $comment = $data['comment'];
            if (strlen($comment) > 500) {
                $errors['comment'] = 'Комментарий не может быть длиннее 500 символов';
            }
        }

        if (isset($data['address'])) {
            $address = $data['address'];
            if (strlen($address) < 5) {
                $errors['address'] = 'Адрес не может содержать меньше 5 символов';
            }
        } else {
            $errors['address'] = 'Адрес должен быть заполнен!';
        }

        return $errors;
    }

    private function validateName(array $data): null|string
    {
        if (isset($data['contact_name'])) {
            $contact_name = $data['contact_name'];
            if (strlen($contact_name) < 3) {
                return 'Имя не может содержать меньше 3 символов';
            }
            return null;
        } else {
            return 'Имя должно быть заполнено';
        }
    }

    public function getAllOrders()
    {
        if ($this->authService->check()) {
            header("Location: /login");
            exit();
        }
        $user = $this->authService->getCurrentUser(); // исправил (!)

        $userOrders = $this->OrderModel->getAllByUserId($user->getId());

        $newUserOrders = [];

        foreach ($userOrders as $userOrder) {
            $ordersProducts = $this->order_productsModel->getAllByOrderId($userOrder->getId());

            $orderProductsData = [];
            $orderTotal = 0;

            foreach ($ordersProducts as $orderProduct) {
                $productId = $orderProduct->getProductId();

                $product = $this->product->getOneById($productId);


                $name = $product !== null ? $product->getName() : 'Товар Удалён';
                $price = $product !== null ? $product->getPrice() : 0;
                $amount = $orderProduct->getAmount();
                $totalSum = $price * $amount;

                $orderProductsData[] = [
                    'name' => $name,
                    'amount' => $amount,
                    'price' => $price,
                    'totalSum' => $totalSum,
                ];
                $orderTotal += $totalSum;
            }

            $userOrder->OrderProducts = $orderProductsData;
            $userOrder->total = $orderTotal;

            $userOrders = array_filter($userOrders, function($order) {
                return (!empty($order->OrderProducts));
            });
        }

        $resultOrders = $userOrders;

        require_once '../Views/user_orders.php';

        return $userOrders;
    }
}