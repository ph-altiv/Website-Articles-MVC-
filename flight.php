<?php
header('Content-type: text/html; charset=utf-8');
$GLOBALS['entry'] = pathinfo(__FILE__, PATHINFO_FILENAME);
$GLOBALS['default_controller'] = 'articles';
$GLOBALS['dbstr'] = file_get_contents("database_str");

include('classes/router.php');

define('ENDL', '<br/>');
$site_path = __DIR__ . DIRECTORY_SEPARATOR;
try
{
    // Соединение с базой данных
    $con = pg_connect($GLOBALS['dbstr']);
    if(!$con)
        throw new Exception("Не удается подключить базу данных");
    Router::setControllersDirectory($site_path . "controllers");
    Router::callController();

    // Получение страницы из шаблона
    include('templates/common.php');

    pg_close($con);
}
catch(Exception $e)
{
    $ferr = fopen('log.txt', 'a');
    fwrite($ferr, $e->getMessage() . PHP_EOL);
    fclose($ferr);
    die("Произошла ошибка. Приносим извенения.");
}


?>