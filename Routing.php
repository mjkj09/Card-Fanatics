<?php

require_once 'src/controllers/DefaultController.php';
require_once 'src/controllers/SecurityController.php';

class Routing
{
    public static $routes;

    public static function get($url, $view)
    {
        self::$routes[$url] = $view;
    }

    public static function post($url, $view)
    {
        self::$routes[$url] = $view;
    }

    public static function run($url)
    {
        // Rozdzielenie ścieżki na akcję
        $action = explode('/', $url)[0];

        // Sprawdzenie, czy akcja istnieje w routingu
        if (!array_key_exists($action, self::$routes)) {
            die('Wrong URL');
        }

        // Pobranie nazwy kontrolera z routing
        $controller = self::$routes[$action];

        // Dodanie przestrzeni nazw
        $controllerNamespace = "controllers\\" . $controller;

        // Sprawdzenie, czy klasa istnieje
        if (!class_exists($controllerNamespace)) {
            die("Class $controllerNamespace not found!");
        }

        // Utworzenie obiektu klasy kontrolera
        $object = new $controllerNamespace;

        // Wywołanie metody w kontrolerze
        $method = $action ?: 'index';
        if (!method_exists($object, $method)) {
            die("Method $method not found in class $controllerNamespace!");
        }

        // Wywołanie metody
        $object->$method();
    }
}
