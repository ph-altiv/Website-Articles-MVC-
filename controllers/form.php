<?php

class Controller_form extends Controller
{
    private $action;
    private $error = 0;
    private $DATA_ERROR = 1;
    private $DUPLICATE_ERROR = 2;

    public function index()
    {
        $this -> action = 'index';
    }

    // После получения запроса на добавление пользователя
    public function save()
    {
        $this -> action = 'save';

        if (!isset($_POST['name']) or !isset($_POST['email'])) {
            $this->error = $this->DATA_ERROR;
            return;
        }

        $name = $_POST['name'];
        $email = $_POST['email'];

        // Избавляемся от введенных тегов
        $name = stripslashes($name);
        $name = htmlspecialchars($name);
        $email = stripslashes($email);
        $email = htmlspecialchars($email);

        // Убираем пробелы
        $name = trim($name);
        $email = trim($email);

        if (empty($name) or empty($email))
        {
            $this->error = $this->DATA_ERROR;
            return;
        }
    }

    public function view()
    {
        if($this->action == 'index')
        {
            $tform = $GLOBALS['templates_dir'] . 'reg_form.html';
            if ( !file_exists($tform) )
                throw new Exception('[Form] Не могу открыть файл ' . $tform);
            echo file_get_contents($tform);
        }
    }
}

?>