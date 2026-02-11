<?php
//
//if (session_status() !== PHP_SESSION_ACTIVE) {
//    session_start();
//}
//
//if (!isset($_SESSION['userId'])) {
//    header("Location: ./login");
//    exit();
//}
//
//function validate(array $data):array
//{
//    $errors = [];
//
//    if (isset($data['product_id'])) {
//        $productId = (int)$data['product_id'];
//
//        $pdo = new PDO ('pgsql:host = postgres; dbname= mydb', 'king', 'qwerty');
//        $stmt = $pdo->prepare("SELECT * FROM products WHERE id = :productId");
//        $stmt->execute(['productId' => $productId]);
//        $data = $stmt->fetch();
//
//        if ($data === false) {
//            $errors['product_id'] = 'Продукт не найден';
//        }
//        if (isset($data['amount'])) {
//            $amount = (int)$data['amount'];
//            if ($amount <= 0) {
//                $errors['amount'] = 'Количество товара должно быть больше 0.';
//            }
//        }
//    }else {
//        $errors['product_id'] = 'id продукта должен быть указан';
//    }
//    return $errors;
//}
//
//$errors = validate($_POST);
//
//if (empty($errors)) {
//    $pdo = new PDO ('pgsql:host = postgres; dbname= mydb', 'king', 'qwerty');
//    $userId = $_SESSION['userId'];
//    $productId = $_POST['product_id'];
//    $amount = $_POST['amount'];
//
//    $stmt = $pdo->prepare("SELECT * FROM user_products WHERE product_id = :productId AND user_id = :userId");
//    $stmt->execute(['userId' => $userId, 'productId' => $productId]);
//    $data = $stmt->fetch();
//
//    if ($data === false) {
//        $stmt = $pdo->prepare("INSERT INTO user_products (user_id, product_id, amount) VALUES (:userId, :productId, :amount)");
//        $stmt->execute(['userId' => $userId, 'productId' => $productId, 'amount' => $amount]);
//    } else {
//        $amount = $data['amount'] + $amount;
//
//        $stmt = $pdo->prepare("UPDATE user_products SET amount = :amount WHERE user_id = :userId and product_id = :productId");
//        $stmt->execute(['userId' => $userId, 'productId' => $productId, 'amount' => $amount]);
//    }
//
//    header("Location: ./catalog");
//    exit();
//}
