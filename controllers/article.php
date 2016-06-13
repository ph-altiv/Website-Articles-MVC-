<?php

class Controller_article extends Controller
{
    private $id;

    public function index()
    {
        $this->id = $this->getNumeric('id', null);
    }

    public function view()
    {
        $result = null;
        if(!empty($this->id))
        {
            $query = "select article from articles where oid=" . $this->id;
            $result = pg_query($query);
            if ( !$result )
                throw new Exception('[Controller_article] не удается выполнить запрос к базе данных');
        }
        if(!empty($result) and pg_num_rows($result) > 0)
            echo pg_fetch_result($result, 0, 0);
        else
            echo 'Статья не была найдена. Приносим свои извинения.';
        echo '<p class="pages">';
        echo '<a href="javascript:history.back()" onMouseOver="window.status=\'Назад\';return true">Назад</a>';
        echo '</p>';
    }
}

?>