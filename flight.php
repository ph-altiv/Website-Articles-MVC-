<?php

// Для кириллицы
header('Content-type: text/html; charset=utf-8');

$GLOBALS['entry'] = pathinfo(__FILE__, PATHINFO_FILENAME);
$GLOBALS['default_controller'] = 'articles'; // Если контроллер не указан в запросе, вызывается указанный здесь
$GLOBALS['dbstr'] = file_get_contents("database_str"); // Строка для подкоючения к бд
$GLOBALS['templates_dir'] = __DIR__ . '/templates/';

include('classes/router.php');

define('ENDL', '<br/>');

$site_path = __DIR__ . DIRECTORY_SEPARATOR;

// Соединение с базой данных
$con = pg_connect($GLOBALS['dbstr']);
if(!$con)
    throw new Exception("Не удается подключить базу данных");
try
{
    // Устанавливает директорию с контроллерами
    Router::setControllersDirectory($site_path . "controllers");

    // Получает контроллер и выполняет соответсвующее действие
    Router::callController();

    // Получение страницы из шаблона
    include('templates/common.php');
}
catch(Exception $e)
{
    // Вывод сообщения об исключении в файл log
    $ferr = fopen('log.txt', 'a');
    fwrite($ferr, $e->getMessage() . PHP_EOL);
    fclose($ferr);

    echo "Произошла ошибка. Приносим извенения.";
}
pg_close($con);

?>