<?php

class Controller_form extends Controller
{
    private $action;
    private $error = 0;
    private $DATA_ERROR = 1;
    private $DUPLICATE_ERROR = 2;
    private $EMAIL_ERROR = 3;
    private $DATABASE_ERROR = 4;

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

        // Проверка введенного e-mail
        if (!filter_var($email, FILTER_VALIDATE_EMAIL))
        {
            $this->error = $this->EMAIL_ERROR;
            return;
        }

        // Пытаемся занести данные в базу
        $query = "SELECT add_user('$name', '$email')";
        $result = pg_query($query);
        if(!$result)
            throw new Exception('[Form.save] Не удается отправить запрос к базе данных');
        $result = pg_fetch_result($result, 0, 0);
        switch($result)
        {
            case 'OK':
                break;
            case 'EMAIL_DUPLICATE':
                $this->error = $this->DUPLICATE_ERROR;
                break;
            default:
                $this->error = $this->DATABASE_ERROR_ERROR;
        }
    }

    // Представление для действия по умолчанию, форма для заполнения данных
    private function index_view()
    {
        $tform = $GLOBALS['templates_dir'] . 'reg_form.html';
        if ( !file_exists($tform) )
            throw new Exception('[Form] Не могу открыть файл ' . $tform);
        echo file_get_contents($tform);
    }

    // Представление с результатом сохранения данных
    private function save_view()
    {
        echo '<div class="form_msg">';
        switch($this->error)
        {
            case 0:
                echo 'Данные внесены успешно.';
                break;
            case $this->DATA_ERROR;
                echo 'Нужно заполнить оба поля.';
                break;
            case $this->DUPLICATE_ERROR:
                echo 'Пользователь с таким e-mail уже есть.';
                break;
            case $this->EMAIL_ERROR:
                echo 'Неверно введен e-mail.';
                break;
            case $this->DATABASE_ERROR;
                echo 'Произошла ошибка. Немного подождите и попробуйте повторно внести данные.';
                break;
        }
        echo '</div>';
        echo '<div class="pages">';
        echo '<a href="javascript:history.back()" onMouseOver="window.status=\'Назад\';return true">Назад</a>';
        echo '</div>';
    }

    
    public function view()
    {
        switch($this->action)
        {
            case 'index':
                $this->index_view();
                break;
            case 'save':
                $this->save_view();
        }
    }
}

?>