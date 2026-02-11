<?php

namespace Core;

use classes\OrderController;
use Controller\UserController;
use Controller\ProductController;
use Controller\CartController;
use Controller\ProfileController;

class App
{
    private array $routes = [
        '/registration' => [
            'GET' => [
                'class' => UserController::class,
                'method' => 'getRegistrate',
            ],
            'POST' => [
                'class' => UserController::class,
                'method' => 'registrate',
            ],
        ],
        '/login' => [
            'GET' => [
                'class' => UserController::class,
                'method' => 'getLogin',
            ],
            'POST' => [
                'class' => UserController::class,
                'method' => 'login',
            ],
        ],
        '/catalog' => [
            'GET' => [
                'class' => ProductController::class,
                'method' => 'Catalog',
            ],
        ],
        '/profile' => [
            'GET' => [
                'class' => ProfileController::class,
                'method' => 'Profile',
            ],
        ],
        '/cart' => [
            'GET' => [
                'class' => CartController::class,
                'method' => 'cart',
            ],
        ],
        '/profile-change' => [
            'GET' => [
                'class' => ProfileController::class,
                'method' => 'editProfile',
            ],
        ],
        '/add-product' => [
            'GET' => [
                'class' => ProductController::class,
                'method' => 'getProducts',
            ],
            'POST' => [
                'class' => ProductController::class,
                'method' => 'Product',
            ],
        ],
        '/create-order' => [
            'GET' => [
                'class' => \Controller\OrderController::class,
                'method' => 'getCheckForm',
            ],
            'POST' => [
                'class' => \Controller\OrderController::class,
                'method' => 'handleCheckout',
            ],
        ],
    ];


    public function run()
    {
        $requestUri = $_SERVER['REQUEST_URI']; //registration
        $requestMethod = $_SERVER['REQUEST_METHOD']; // POST

       if (isset($this->routes[$requestUri])){
           $routeMethods = $this->routes[$requestUri];
           if (isset($routeMethods[$requestMethod])) {

               $handler = $routeMethods[$requestMethod];

               $class = $handler['class']; //UserController
               $method = $handler['method'];

//               require_once "../Controllers/$class.php";

               $controller = new $class();
               $controller->$method();

           } else {
               echo "$requestMethod не поддерживается для $requestUri";
           }
       } else {
           http_response_code(404);
           require_once '../Views/404.php';
       }
    }

}

