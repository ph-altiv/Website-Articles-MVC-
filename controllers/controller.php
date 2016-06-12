<?php

interface Controller
{
    public function index();
    public function view();
    public function dbConnect($db_con);
}

?>