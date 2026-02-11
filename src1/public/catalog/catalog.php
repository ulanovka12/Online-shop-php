<?php

session_start();

if (isset($_SESSION["userId"])) {
    $pdo = new PDO ('pgsql:host = postgres; dbname= mydb', 'king', 'qwerty');

    //добавлениe пользователя!!!

    $stmt = $pdo->query('SELECT * FROM products');
    $products = $stmt->fetchAll();
    require_once './catalog/catalog_page.php';

} else {
    header('Location: /login');
}
