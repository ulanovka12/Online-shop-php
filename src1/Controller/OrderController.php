<?php

namespace Controller;

use Model\Order;
use Model\Product;
use Model\Order_products;
use Service\OrderService;


class OrderController extends BaseController
{
    private Order $OrderModel;
    private Order_products $order_productsModel;
    private Product $product;
    private OrderService $orderService;

    public function __construct()
    {
        parent::__construct();
        $this->OrderModel = new Order();
        $this->order_productsModel = new Order_products();
        $this->product = new Product();
        $this->orderService = new OrderService();
    }


    public function getCheckForm()
    {
        require_once './../Views/order_form.php';
    }

    public function handleCheckout()
    {

        if (!$this->authService->check()) {
            header("Location: /login");
            exit();
        }

        // Валидация данных из POST
        $errors = $this->validate($_POST);

        // Если есть ошибки – показываем форму снова
        if (!empty($errors)) {
            require_once './../Views/order_form.php';
            return;
        }

        // Подготовка данных для сервиса
        $orderData = [
            'contact_name'  => trim($_POST['contact_name']),
            'contact_phone' => trim($_POST['contact_phone']),
            'comment'       => trim($_POST['comment'] ?? ''),
            'address'       => trim($_POST['address']),
        ];

        $userId = $this->authService->getCurrentUser()->getId();

        try {
            // Вызов сервиса с передачей данных и ID пользователя
            $orderId = $this->orderService->createOrder($userId, $orderData);

            //success
            header('Location: /users-orders');
            exit();
        } catch (\Exception $e) {
            // Обработка ошибок
            $errors[] = $e->getMessage();
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
        if (!$this->authService->check()) {
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