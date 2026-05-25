<?php

namespace App\Helpers;

class Router {
    private static $routes = [];

    public static function get($path, $controllerAction) {
        self::addRoute('GET', $path, $controllerAction);
    }

    public static function post($path, $controllerAction) {
        self::addRoute('POST', $path, $controllerAction);
    }

    private static function addRoute($method, $path, $controllerAction) {
        // Convert route like /event/{slug} to regex like ^/event/(?P<slug>[^/]+)$
        $pattern = preg_replace('/\{([a-zA-Z0-9_]+)\}/', '(?P<$1>[^/]+)', $path);
        $pattern = '#^' . $pattern . '$#';

        self::$routes[] = [
            'method' => $method,
            'pattern' => $pattern,
            'controllerAction' => $controllerAction
        ];
    }

    public static function dispatch() {
        $method = $_SERVER['REQUEST_METHOD'];
        $uri = $_SERVER['REQUEST_URI'];

        // Strip query string from URI
        if (($pos = strpos($uri, '?')) !== false) {
            $uri = substr($uri, 0, $pos);
        }

        // Handle base folder in Laragon (e.g., if accessed via localhost/Eventin/public/)
        // We get the base directory of the script and strip it from the URI
        $scriptName = $_SERVER['SCRIPT_NAME']; // e.g. /Eventin/public/index.php
        $basePath = dirname($scriptName);      // e.g. /Eventin/public
        
        // Normalize slashes
        $basePath = str_replace('\\', '/', $basePath);
        if ($basePath !== '/' && strpos($uri, $basePath) === 0) {
            $uri = substr($uri, strlen($basePath));
        }

        // Ensure leading slash and remove trailing slash (except for home page)
        $uri = '/' . trim($uri, '/');

        foreach (self::$routes as $route) {
            if ($route['method'] === $method && preg_match($route['pattern'], $uri, $matches)) {
                // Extract named parameters from regex matches
                $params = [];
                foreach ($matches as $key => $value) {
                    if (is_string($key)) {
                        $params[$key] = $value;
                    }
                }

                list($controllerClass, $action) = explode('@', $route['controllerAction']);
                $fullControllerClass = "App\\Controllers\\" . $controllerClass;

                if (class_exists($fullControllerClass)) {
                    $controller = new $fullControllerClass();
                    if (method_exists($controller, $action)) {
                        // Use array_values() to convert named route params to positional args
                        // This prevents PHP 8's "Unknown named parameter" error when route
                        // param names (e.g. kode_tiket) differ from method param names (e.g. $kodeTiket)
                        call_user_func_array([$controller, $action], array_values($params));
                        return;
                    } else {
                        self::errorResponse(500, "Method $action tidak ditemukan di controller $controllerClass");
                        return;
                    }
                } else {
                    self::errorResponse(500, "Controller $controllerClass tidak ditemukan");
                    return;
                }
            }
        }

        self::errorResponse(404, "Halaman Tidak Ditemukan (404)");
    }

    private static function errorResponse($code, $message) {
        http_response_code($code);
        // Beautiful error template
        $errorCode = $code;
        $errorMessage = $message;
        
        $errorFile = __DIR__ . '/../views/errors/error.php';
        if (file_exists($errorFile)) {
            include $errorFile;
        } else {
            echo "<h1>Error $errorCode</h1><p>$errorMessage</p>";
        }
    }
}
