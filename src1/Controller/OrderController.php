<?php

namespace Controller;

use Model\Order;
use Model\User_products;
use Model\Order_products;


class OrderController
{
    private Order $OrderModel;
    private User_products $user_productsModel;
    private Order_products $order_productsModel;

    public function __construct()
    {
        $this->OrderModel = new Order();
        $this->user_productsModel = new User_products();
        $this->order_productsModel = new Order_products();
    }


    public function getCheckForm()
    {
        require_once './../Views/order_form.php';
    }
    public function handleCheckout()
    {
        if (session_status() !== PHP_SESSION_ACTIVE) {
            session_start();
        }

        if (!isset($_SESSION['userId'])) {
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

            $userProducts = $this->user_productsModel->getAllByUserId($userId);

            foreach ($userProducts as $userProduct) {

                $productId = $userProduct['product_id'];
                $amount = $userProduct['amount'];

                $this->order_productsModel->create1($orderId, $productId, $amount);

            }
           $this->user_productsModel->deleteByUserId($userId);


        } else{
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
        if (session_status() !== PHP_SESSION_ACTIVE) {
            session_start();
        }
        if (!isset($_SESSION['userId'])) {
            header("Location: /login");
            exit();
        }
        $userId = $_SESSION['userId'];

        $userOrders = $this->user_productsModel->getAllByUserId($userId);

        $newUserOrders = [];

        foreach ($userOrders as $userOrder) {
            $orderId = $userOrder['id'];

            $ordersProducts = $this->order_productsModel->getAllByOrderId($userOrder['id']);

            $userOrder['orderProducts'] = $ordersProducts;
            $newUserOrders[] = $userOrder;
        }
        require_once '../Views/user_orders.php';

    }

}