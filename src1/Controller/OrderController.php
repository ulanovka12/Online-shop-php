<?php

namespace Controller;
class OrderController
{

    public function getCheckForm()
    {
        require_once './../Views/order_form.php';

    }
    public function handleCheckout()
    {

        if (session_status() !== PHP_SESSION_ACTIVE) {
//            session_start();
            header("Location: /login");
            exit();
        }

        if (empty($errors)) {
            $contactName = $_POST['contact_name'];
            $contactPhone = $_POST['contact_phone'];
            $comment = $_POST['comment'];
            $address = $_POST['address'];

        }else{
            require_once './../Views/order_form.php';
        }
    }

//    private function validate(array $data):array
//    {
//
//    }


}