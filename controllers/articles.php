<?php

class Controller_articles implements Controller
{
    private $data;
    private $page;
    private $size; // Кольчество статей на странице
    private $psize; // Количество страниц

    private function checkNumericGet($key, $default)
    {
        $val = $_GET[$key];
        $val = preg_replace('~[^0-9]+~','', empty($val) ? '' : $val);
        return empty($val) ? $default : $val;
    }

    // Создает URL статьи
    private function urlArticle($id)
    {
        $data = array('id'=>$id);
        return 'article?' . http_build_query($data);
    }

    // Создает URL определенной страцицы
    private function urlPage($page, $size = 7)
    {
        $data = ($size == 7) ? ['p'=>$page] : ['p'=>$page, 's'=>$size];
        return 'articles?' . http_build_query($data);
    }

    // Создает ссылку
    private function link($href, $caption)
    {
        return "<a href=\"$href\">$caption</a>";
    }

    // Создает тег ссылки на страницу
    private function linkPage($page, $caption)
    {
        if($this->page == $page)
            return $caption;
        $url = $this->urlPage($page, $this->size);
        return $this->link($url, $caption);
    }

    public function index()
    {
        $page = $this::checkNumericGet('p', 1);
        $size = $this::checkNumericGet('s', 7);

        // Контролируем размер страницы
        $size = max(min($size, 100), 3);

        // Получаем количесво страниц
        $result = pg_query("select ceil(count(*)::real/$size) from articles;");
        if(!$result)
            throw new Exception("[Controller_articles.Index] Не удается получить количество страниц из базы данных");
        $this->psize = pg_fetch_array($result, 0)[0];

        // Подбираем существующую страницу
        $page = min(max(1, $page), $this->psize);

        // Получаем список статей
        $query = "select num, name, oid from pages_articles($size) where page = $page";
        $result = pg_query($query);

        // Запоминаем необходимые данные в объекте класса
        $this->data = $result;
        $this->page = $page;
        $this->size = $size;
    }

    public function view()
    {
        while ($line = pg_fetch_array($this->data, null, PGSQL_ASSOC))
        {
            $caption = $line['num'] . '. ' . $line['name'];
            $url = $this->urlArticle($line['oid']);
            echo $this->link($url, $caption) . ENDL;
        }

        // Линки страниц
        if($this->psize <= 1)
            return;
        echo '<p class="pages">' . PHP_EOL;
        echo $this->linkPage(max($this->page - 1, 1), '&lt;') . PHP_EOL; // На одну страницу назад
        $fpage = max($this->page - 1, 1);
        if($fpage > 1)
            echo $this->linkPage(1, 1) . (($fpage > 2) ? '…' : '') . PHP_EOL; // Первая страница
        $tpage = min($fpage + 3, $this->psize);
        for($p = $fpage; $p <= $tpage; $p++) // Показывает 4 страницы в центре
            echo $this->linkPage($p, $p) . PHP_EOL;
        if($tpage < $this->psize)
            echo '…' . $this->linkPage($this->psize, $this->psize) . PHP_EOL; // Последняя страница
        echo $this->linkPage(min($this->page + 1, $this->psize), '&gt;') . PHP_EOL; // На одну страницу вперед
        echo '</p>' . PHP_EOL;
    }
}

?>