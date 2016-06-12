<?php

// Для разбора запросов и выбора контролера
class Router
{
    private static $path;

    // Установка пути к контролерам
    static function setControllersDirectory($path)
    {
        $path = rtrim($path, '/\\');
        $path .= DIRECTORY_SEPARATOR;
        if (is_dir($path) == false)
            die ('404 Not Found');
        self::$path = $path;
    }

    // Имя контроллера и метод
    private static function getController(&$file, &$controller, &$action) {
        $route = (empty($_GET['route'])) ? $GLOBALS['entry'] : $_GET['route'];
        $route = str_replace("../", "", $route);
        $route = trim($route, '/\\') . '.php';
        $fullpath = self::path . $route;
        if (is_file($fullpath)) {
            $controller = pathinfo($fullpath, PATHINFO_FILENAME);
        }
        if (empty($controller)) { $controller = $GLOBALS['default_controller']; };
        $action = $_GET['action'];
        if (empty($action)) { $action = 'index'; }
        $file = self::path . $controller . '.php';
    }

    // Обработка запроса
    static function callController()
    {
        // Выбор контроллера
        self::getController($file, $controller, $action);
        if (is_readable($file) == false)
            die ('404 Not Found');
        include ($file);
        $class = 'Controller_' . $controller;
        $controller = new $class();
        if (is_callable(array($controller, $action)) == false)
            die ('404 Not Found');

        // Подсоединение к базе данных
        $con = pg_connect("host=localhost port=5432 dbname=flightphp  user=aaa password=123");
        if(!$con)
            throw Exception("Database connection error");
        $controller->$action();
        pg_free_result($result);
        pg_close($dbconn);
    }
}

?>