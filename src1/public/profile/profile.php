<?php

session_start();

if (isset($_SESSION['userId'])) {

    $userId = $_SESSION['userId'];

    $pdo = new PDO ('pgsql:host = postgres;dbname=mydb', 'king', 'qwerty');
    $stmt = $pdo->query("SELECT * FROM users WHERE id = $userId");
    $user = $stmt->fetch();
    require_once './profile/profile_form.php';
} else {
    header('Location: /login');
}