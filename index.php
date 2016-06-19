<?php

// Для кириллицы
header('Content-type: text/html; charset=utf-8');

// Подключаем flightphp
require_once 'flight/Flight.php';

// Устанавливаем директорию с шаблонами
Flight::set('flight.views.path', 'templates');

// Логирование ошибок
// Flight::set('flight.log_errors', true);

// Обработка исключений
Flight::map('error', function(Exception $e){
    $fer = fopen('log.txt', 'a');
    fwrite($fer, $e->getMessage() . PHP_EOL);
    fclose($fer);
    echo 'Произошла ошибка. Приносим свои извинения.';
});

// По названию контроллера строит представление
function router($controller = 'articles') {
    $cont = "controllers/$controller.php";
    if(!is_readable($cont))
        Flight::notFound(); // 404
    require_once $cont;
    $class = 'Controller_' . $controller;
    $obj = new $class();
    $action = Flight::request()->query['action'];
    if(empty($action))
        $action = 'index';
    if (!is_callable(array($obj, $action)))
        Flight::notFound(); // 404
    $obj -> $action();
    if (!is_callable(array($obj, 'view')))
        throw new Exception("[Router] Не получается вызвать метод представления для контроллера " . $controller);
    Flight::view()->set('content', $obj -> view());
    Flight::render('common.php');
}

// Маршрутизация в зависимости от запроса
Flight::route('/', 'router');
Flight::route('/@controller', 'router');

// Подключаемся к базе данных
$con = pg_connect(file_get_contents("database_str"));

// Запускаем обработку framework'ом
Flight::start();

// Закрываем соединение
pg_close($con);

?>