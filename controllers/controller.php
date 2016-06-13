<?php

abstract class Controller
{
    public abstract function index();
    public abstract function view();

    // Извлекает численное значение из GET запроса
    protected function getNumeric($key, $default)
    {
        $val = $_GET[$key];
        $val = preg_replace('~[^0-9]+~','', empty($val) ? '' : $val);
        return empty($val) ? $default : $val;
    }
}

?>