<?php

use Controller\UserController;
use Core\App;
use Core\Autoloader;

require './../Core/Autoloader.php';

$path = dirname(__DIR__);
\Core\Autoloader::register($path);

$app = new Core\App();

$app->get('/registration', \Controller\UserController::class, 'getRegistrate');
$app->post('/registration', \Controller\UserController::class, 'Registrate', \Request\RegistrateRequest::class);

$app->get('/login',  \Controller\UserController::class, 'getLogin');
$app->post('/login',  UserController::class, 'login', \Request\LoginRequest::class);
$app->get('/logout',  \Controller\UserController::class, 'logout');

$app->get('/reviews', \Controller\ReviewsController::class, 'getReviews');
$app->post('/reviews', \Controller\ReviewsController::class, 'Reviews', Request\ReviewsRequest::class);



$app->get('/catalog', \Controller\ProductController::class, 'Catalog' );
$app->get('/profile', \Controller\ProfileController::class, 'Profile' );
$app->get('/update-profile', \Controller\ProfileController::class, 'UpdateProfile' );
$app->get('/cart', \Controller\CartController::class, 'cart' );
$app->post('/update-cart',  \Controller\CartController::class, 'updateCart', \Request\CartRequest::class );
$app->get('/remove-from-cart',  \Controller\CartController::class, 'removeFromCart' );
$app->get('/clear-cart', \Controller\CartController::class, 'clearCart' );
$app->post('/add-product', \Controller\CartController::class, 'addProductToCart', \Request\AddProductRequest::class );
$app->post('/decrease-product', \Controller\CartController::class, 'decreaseProductFromCart', \Request\AddProductRequest::class );
$app->get('/profile-change', \Controller\UserController::class, 'editProfile');
$app->post('/profile-change', \Controller\UserController::class, 'updateProfile', \Request\ProfileRequest::class );
$app->get('/add-product', \Controller\ProductController::class, 'getProducts');
$app->post('/add-product',  \Controller\ProductController::class, 'Product', Request\ProductRequest::class );
$app->get('/create-order', \Controller\OrderController::class, 'getCheckForm');
$app->post('/create-order', \Controller\OrderController::class, 'handleCheckout', \Request\OrderRequest::class );
$app->get('/users-orders', \Controller\OrderController::class, 'getAllOrders');

$app->run();


