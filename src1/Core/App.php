<?php

namespace Core;

use Controller\CartController;
use Controller\OrderController;
use Controller\ProductController;
use Controller\UserController;

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
                'class' => UserController::class,
                'method' => 'profile',
            ],
        ],
        '/cart' => [
            'GET' => [
                'class' => CartController::class,
                'method' => 'cart',
            ],
        ],
        '/update-cart' => [
            'POST' => [
                'class' => CartController::class,
                'method' => 'updateCart',
            ],
        ],
        '/remove-from-cart' => [
            'GET' => [
                'class' => CartController::class,
                'method' => 'removeFromCart',
            ],
        ],
        '/clear-cart' => [
            'GET' => [
                'class' => CartController::class,
                'method' => 'clearCart',
            ],
        ],
        '/profile-change' => [
            'GET' => [
                'class' => UserController::class,
                'method' => 'editProfile',
            ],
            'POST' => [
                'class' => UserController::class,
                'method' => 'updateProfile',
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
        '/create-orders' => [
            'GET' => [
                'class' => OrderController::class,
                'method' => 'getCheckForm',
            ],
            'POST' => [
                'class' => OrderController::class,
                'method' => 'handleCheckout',
            ],
        ],
        '/users-order' => [
                'GET' => [
                    'class' => OrderController::class,
                    'method' => 'getAllOrders',
                ]
            ]
    ];


    public function run()
    {
        $requestUri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH); //registration
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

//    public function  addRoute(string $route, string $routeMethod, string $className, string $method)
//    {
//        $this->routes[$route][$routeMethod] = [
//                'class' => $className,
//                'method' => $method,
//        ];
//    }

    public function get(string $route, string $className, string $method)
    {
        $this->routes[$route]['GET'] = [
            'class' => $className,
            'method' => $method,
        ];
    }

    public function post(string $route, string $className, string $method)
    {
        $this->routes[$route]['POST'] = [
            'class' => $className,
            'method' => $method,
        ];
    }

    public function put(string $route, string $className, string $method)
    {
        $this->routes[$route]['PUT'] = [
            'class' => $className,
            'method' => $method,
        ];
    }
}

