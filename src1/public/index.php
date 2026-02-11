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

$app = new Core\App();
$app->run();


