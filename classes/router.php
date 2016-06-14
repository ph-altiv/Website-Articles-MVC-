<?php

// Для разбора запросов и выбора контроллера
class Router
{
    private static $path;

    // Установка пути к контроллерам
    static function setControllersDirectory($path)
    {
        $path = rtrim($path, '/\\');
        $path .= DIRECTORY_SEPARATOR;
        if (is_dir($path) == false)
            throw new Exception('[Router::setControllersDirectory] Ошибка директории контроллера');
        self::$path = $path;
    }

    // Из GET-запроса выделяет имя контроллера и метод контроллера
    private static function getController(&$file, &$controller, &$action) {
        $route = (empty($_GET['route'])) ? $GLOBALS['entry'] : $_GET['route'];
        $route = str_replace("../", "", $route);
        $route = trim($route, '/\\') . '.php';
        $name = self::$path . $route;
        if (is_file($name)) {
            $controller = pathinfo($name, PATHINFO_FILENAME);
        }
        if (empty($controller)) { $controller = $GLOBALS['default_controller']; };
        $action = $_GET['action'];
        if (empty($action) or $action == 'view' or $action == 'dbConnect') { $action = 'index'; }
        $file = self::$path . $controller . '.php';
    }

    // Обработка запроса
    static function callController()
    {
        // Выбор контроллера
        self::getController($file, $controller, $action);
        if (!is_readable($file))
            throw new Exception("[Router::callController] Нет класса для контроллера " . $file);
        include(self::$path . 'controller.php'); // Подключаем интерфейс контроллера
        include($file); // Подключаем сам контроллер
        $class = 'Controller_' . $controller;
        $controller = new $class();

        // Проверяем можно ли вызвать метод и вызываем его
        if (is_callable(array($controller, $action)) == false)
            throw new Exception("[Router::callController] Не получается вызвать действие " . $action);
        $controller->$action();

        $GLOBALS['controller'] = $controller;
    }
}

?>