<?php
header('Content-type: text/html; charset=utf-8');
$GLOBALS['entry'] = pathinfo(__FILE__, PATHINFO_FILENAME);
$GLOBALS['default_controller'] = 'articles';

include('classes/router.php');

define('ENDL', '</br>');
$site_path = __DIR__ . DIRECTORY_SEPARATOR;

Router::setControllersDirectory($site_path . "controllers");

?>