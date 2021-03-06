<?php

class Controller_articles extends Controller
{
    private $data;
    private $page;
    private $size; // Количество статей на странице
    private $psize; // Количество страниц

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
    private function linkPage($page, $caption, $space = ' ')
    {
        if($this->page != $page)
        {
            $url = $this->urlPage($page, $this->size);
            $caption = $this->link($url, $caption);
        }

        return $caption . $space;
    }

    public function index()
    {
        $page = $this->getNumeric('p', 1);
        $size = $this->getNumeric('s', 7);

        // Контролируем размер страницы
        $size = max(min($size, 100), 3);

        // Получаем количесво страниц
        $result = pg_query("select ceil(count(*)::real/$size) from articles;");
        if(!$result)
            throw new Exception("[Controller_articles.Index] Не удается получить количество страниц из базы данных");
        $this->psize = pg_fetch_result($result, 0, 0);

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
        // Вывод ссылок на статьи
        $start = pg_fetch_result($this->data, 0, 0);
        echo '<div class="article_links"><ol type="1" start="' . $start . '">';
        while ($line = pg_fetch_assoc($this->data))
        {
            $caption = $line['name'];
            $url = $this->urlArticle($line['oid']);
            echo '<li>' . $this->link($url, $caption) . '</li>';
        }
        echo '</ol></div>';

        // Линки страниц
        if($this->psize <= 1)
            return;
        echo '<div class="pages">';
        echo $this->linkPage(max($this->page - 1, 1), '&lt;'); // На одну страницу назад
        $fpage = max($this->page - 1, 1);
        if($fpage > 1)
            echo $this->linkPage(1, 1) . (($fpage > 2) ? '…' : ''); // Первая страница
        $tpage = min($fpage + 3, $this->psize);
        for($p = $fpage; $p <= $tpage; $p++) // Показывает 4 страницы в центре
            echo $this->linkPage($p, $p);
        if($tpage < $this->psize)
            echo '…' . $this->linkPage($this->psize, $this->psize); // Последняя страница
        echo $this->linkPage(min($this->page + 1, $this->psize), '&gt;', ''); // На одну страницу вперед
        echo '</div>';
    }
}

?>