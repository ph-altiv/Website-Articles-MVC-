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
            throw new Exception ('Invalid controller path: `' . $path . '`');
        self::$path = $path;
    }
}

?>