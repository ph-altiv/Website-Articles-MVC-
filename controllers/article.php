<?php

// Контролер для вывода одной статьи по ее id
class Controller_article extends Controller
{
    private $id;

    // Получаем id из GET-запроса
    public function index()
    {
        $this->id = $this->getNumeric('id', null);
    }

    // Выводит статью
    public function view()
    {
        // По id находим статью в базе
        if(!empty($this->id))
        {
            $query = "select article from articles where oid=" . $this->id;
            $result = pg_query($query);
            if ( !$result )
                throw new Exception('[Controller_article] Не удается выполнить запрос к базе данных');
        }

        // Получем статью из базы
        echo '<div class="text">';
        if(!empty($result) and pg_num_rows($result) > 0)
            echo pg_fetch_result($result, 0, 0);
        else
            echo 'Статья не была найдена. Приносим свои извинения.';
        echo '</div>';

        // Кнопка `назад`
        echo '<div class="pages">';
        echo '<a href="javascript:history.back()" onMouseOver="window.status=\'Назад\';return true">Назад</a>';
        echo '</div>';
    }
}

?>