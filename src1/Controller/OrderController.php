<?php

namespace Controller;

use DTO\OrderCreateDTO;
use Model\Order;
use Model\Product;
use Model\Order_products;
use Request\OrderRequest;
use Service\CartService;
use Service\OrderService;

class OrderController extends BaseController
{
    private Order $OrderModel;
    private Order_products $order_productsModel;
    private Product $product;
    private OrderService $orderService;
    private CartService $cartService;

    public function __construct()
    {
        parent::__construct();
        $this->OrderModel = new Order();
        $this->order_productsModel = new Order_products();
        $this->product = new Product();
        $this->orderService = new OrderService();
        $this->cartService = new CartService();
    }


    public function getCheckForm()
    {
        require_once './../Views/order_form.php';
    }

    public function handleCheckout(OrderRequest $request)
    {
        if (!$this->authService->check()) {
            header("Location: /login");
            exit();
        }

        $sum = $this->cartService->getSum();

        if ($sum < 1000){
            throw new \Exception("Для оформления заказа сумма корзины должна быть больше 1000");
        }

        $errors = $request->validate();

        if (!empty($errors)) {
            require_once './../Views/order_form.php';
            return;
        }

        $user = $this->authService->getCurrentUser();
        if (!$user) {
            header("Location: /login");
            exit();
        }

        try {

            $dto = new OrderCreateDTO
            (
                $request->getContactName(),
                $request->getContactPhone(),
                $request->getComment(),
                $request->getAddress(),
            );

            $orderId = $this->orderService->createOrder($dto);

            header('Location: /users-orders');
            exit();
        } catch (\Exception $e) {
            $errorMessage = $e->getMessage();
            require_once './../Views/order_form.php';
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