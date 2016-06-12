<?php

class Controller_articles implements Controller
{
    private $ln_quantity = 7;
    private $db_con;

    public function index()
    {

    }

    public function view()
    {

    }

    public function dbConnect($db_con)
    {
        self::$db_con = $db_con;
    }
}

?>