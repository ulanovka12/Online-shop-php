<?php

namespace Core;

class Autoloader
{

    public static function register(string $dir)
    {
        $autoload = function(string $className) use($dir)
        {

            $path = str_replace('\\', '/', $className); //Core\App

            $path = "$dir/$path.php"; //Core/App.php

            if (file_exists($path)) {
                require_once $path;
                return true;
            }
            return false;
        };
        spl_autoload_register($autoload);
    }
}