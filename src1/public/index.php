<?php

use Controller\UserController;
use Core\App;
use Core\Autoloader;

require './../Core/Autoloader.php';

$path = dirname(__DIR__);
\Core\Autoloader::register($path);

$app = new Core\App();

$app->get('/registration', \Controller\UserController::class, 'getRegistrate');
$app->post('/registration', \Controller\UserController::class, 'Registrate');
$app->get('/login',  \Controller\UserController::class, 'getLogin');
$app->post('/login',  UserController::class, 'login');
$app->get('/logout',  \Controller\UserController::class, 'logout');
$app->get('/catalog', \Controller\ProductController::class, 'Catalog' );
$app->get('/profile', \Controller\ProfileController::class, 'Profile' );
$app->get('/cart', \Controller\CartController::class, 'cart' );
$app->post('/update-cart',  \Controller\CartController::class, 'updateCart' );
$app->get('/remove-from-cart',  \Controller\CartController::class, 'removeFromCart' );
$app->get('/clear-cart', \Controller\CartController::class, 'clearCart' );
$app->post('/add-product', \Controller\CartController::class, 'addProductToCart');
$app->post('/decrease-product', \Controller\CartController::class, 'decreaseProductFromCart');
$app->get('/profile-change', \Controller\ProfileController::class, 'editProfile');
$app->post('/profile-change', \Controller\ProfileController::class, 'updateProfile');
$app->get('/add-product', \Controller\ProductController::class, 'getProducts');
$app->post('/add-product',  \Controller\ProductController::class, 'Product');
$app->get('/create-order', \Controller\OrderController::class, 'getCheckForm');
$app->post('/create-order', \Controller\OrderController::class, 'handleCheckout');
$app->get('/users-orders', \Controller\OrderController::class, 'getAllOrders');

$app->run();


