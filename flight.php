<?php
header('Content-type: text/html; charset=utf-8');
$GLOBALS['entry'] = pathinfo(__FILE__, PATHINFO_FILENAME);
$GLOBALS['default_controller'] = 'articles';

include('classes/router.php');

define('ENDL', '</br>');
$site_path = __DIR__ . DIRECTORY_SEPARATOR;

try
{
    Router::setControllersDirectory($site_path . "controllers");
}
catch(Exception $e)
{
    echo $site_path . ENDL;
    $ferr = fopen("log.txt", "a");
    fwrite($ferr, $e->getMessage() . PHP_EOL);
    fclose($ferr);
    exit("Произошла ошибка");
}



?>