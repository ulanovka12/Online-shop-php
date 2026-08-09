<?php
//
//namespace Service;
//
//use Model\order_products;
//use Model\User_products;
//
//class OrderService
//{
//    private order_products $OrderModel;
//    private AuthService $authService;
//    private user_products $user_productsModel;
//    private order_products $order_productsModel;
//
//    public function __construct()
//    {
//        $this->OrderModel = new order_products();
//        $this->authService = new AuthService();
//        $this->user_productsModel = new user_products();
//        $this->order_productsModel = new order_products();
//    }
//    public function handleCheckout()
//    {
//
//        if (empty($errors)) {
//
//            $contactName = $_POST['contact_name'];
//            $contactPhone = $_POST['contact_phone'];
//            $comment = $_POST['comment'];
//            $address = $_POST['address'];
//            $userId = $this->authService->getCurrentUser()->getId();
//
//            $order = $this->OrderModel->create($contactName, $contactPhone, $comment, $address, $userId);
//
//            $orderId = $order->getId();
//
//            $userProducts = $this->user_productsModel->getAllUserProductByUserId($userId);
//
//            foreach ($userProducts as $userProduct) {
//
//                $productId = $userProduct->getProductId();
//                $amount = $userProduct->getAmount();
//
//                $this->order_productsModel->create1($orderId, $productId, $amount);
//
//            }
//            $this->user_productsModel->deleteByUserId($userId);
//        }
//    }
//
//}