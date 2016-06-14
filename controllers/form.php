<?php

class Controller_form extends Controller
{
    public function index()
    {

    }

    public function view()
    {
        $tform = $GLOBALS['templates_dir'] . 'reg_form.html';
        if(!file_exists($tform))
            throw new Exception('[Form] Не могу открыть файл ' . $tform);
        echo file_get_contents($tform);
    }
}

?>