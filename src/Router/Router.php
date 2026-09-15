<?php

namespace App\Router;

class Router {
    private static array $endpoints = [];

    public static function add(string $uri, string $controller, string $controller_method): void {
        self::$endpoints[$uri] = [
            "controller" => $controller,
            "method" => $controller_method
            ];
    }

    public static function dispatch() {
        $uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        $uri = rtrim($uri, '/');
        $uri = ($uri === '') ? '/' : $uri;

        if (isset(self::$endpoints[$uri])) {
            
            if (class_exists(self::$endpoints[$uri]["controller"])) {
                
                $controllerClass = self::$endpoints[$uri]["controller"];    
                $controller = new $controllerClass();  
                  

                if (method_exists($controller, self::$endpoints[$uri]["method"])) {
                    $method = self::$endpoints[$uri]["method"];
                
                    $controller->$method();
                    return;
                }
                
            }
        }

        http_response_code(404);
        echo "404 Not Found";
    }

    

}

?>