<?php

abstract class Controller
{
    // Дейстаие по умолчанию, если действие не указано
    public abstract function index();

    // Представление
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