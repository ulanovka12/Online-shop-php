<?php

//$autoload = function(string $className) {
//    $path = "../Core/$className.php";
//    if (file_exists($path)) {
//        require_once $path;
//        return true;
//    }
//        return false;
//};
//
//$autoloadController = function(string $className) {
//    $path = "../Controllers/$className.php";
//
//    if (file_exists($path)) {
//        require_once "../Controller/$className.php";
//        return true;
//    }
//    return false;
//};

//use Controller\UserController;

use Controller\UserController;

$autoload = function(string $className) {
     // ./../Core/App.php
    $path = str_replace('\\', '/', $className); //Core/App
    $path = $path . '.php'; //Core/App.php
    $path = './../' . $path;
    if (file_exists($path)) {
        require_once $path;
        return true;
    }
    return false;
};

spl_autoload_register($autoload);

//require_once '../Core/App.php';

//private array $routes = [
//    '/registration' => [
//        'GET' => [
//            'class' => UserController::class,
//            'method' => 'getRegistrate',
//        ],
//        'POST' => [
//            'class' => UserController::class,
//            'method' => 'registrate',
//        ],
//    ],
//    '/login' => [
//        'GET' => [
//            'class' => UserController::class,
//            'method' => 'getLogin',
//        ],
//        'POST' => [
//            'class' => UserController::class,
//            'method' => 'login',
//        ],
//    ],
//    '/catalog' => [
//        'GET' => [
//            'class' => ProductController::class,
//            'method' => 'Catalog',
//        ],
//    ],
//    '/profile' => [
//        'GET' => [
//            'class' => ProfileController::class,
//            'method' => 'Profile',
//        ],
//    ],
//    '/cart' => [
//        'GET' => [
//            'class' => CartController::class,
//            'method' => 'cart',
//        ],
//    ],
//    '/profile-change' => [
//        'GET' => [
//            'class' => ProfileController::class,
//            'method' => 'editProfile',
//        ],
//    ],
//    '/add-product' => [
//        'GET' => [
//            'class' => ProductController::class,
//            'method' => 'getProducts',
//        ],
//        'POST' => [
//            'class' => ProductController::class,
//            'method' => 'Product',
//        ],
//    ],
//    '/create-order' => [
//        'GET' => [
//            'class' => OrderController::class,
//            'method' => 'getCheckForm',
//        ],
//        'POST' => [
//            'class' => OrderController::class,
//            'method' => 'handleCheckout',
//        ],
//    ],
//];

$app = new Core\App();

$app->addRoute('/registration', 'GET', UserController::class, 'getRegistrate');
$app->addRoute('/registration', 'POST', UserController::class, 'Registrate');
$app->addRoute('/login', 'GET', UserController::class, 'getLogin');
$app->addRoute('/login', 'POST', UserController::class, 'login');
$app->addRoute('/catalog', 'GET', \Controller\ProductController::class, 'Catalog' );
$app->addRoute('/profile', 'GET', \Controller\ProfileController::class, 'Profile' );
$app->addRoute('/cart', 'GET', \Controller\CartController::class, 'cart' );
$app->addRoute('/profile-change', 'GET', \Controller\ProfileController::class, 'editProfile');
$app->addRoute('/add-product', 'GET', \Controller\ProductController::class, 'getProducts');
$app->addRoute('/add-product', 'POST', \Controller\ProductController::class, 'Product');
$app->addRoute('/create-order', 'GET', \Controller\OrderController::class, 'getCheckForm');
$app->addRoute('/create-order', 'POST', \Controller\OrderController::class, 'handleCheckout');

$app->run();


