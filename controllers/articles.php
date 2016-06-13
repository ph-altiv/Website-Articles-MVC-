<?php

class Controller_articles implements Controller
{
    private $data;
    private $page;
    private $psize;

    private function checkNumericGet($key, $default)
    {
        $val = $_GET[$key];
        $val = preg_replace('~[^0-9]+~','', empty($val) ? '' : $val);
        return empty($val) ? $default : $val;
    }

    private function urlArticle($id)
    {
        $data = array('id'=>$id);
        return 'article?' . http_build_query($data);
    }

    private function link($href, $caption)
    {
        return "<a href=\"$href\">$caption</a>";
    }

    public function index()
    {
        $page = $this::checkNumericGet('p', 1);
        $size = $this::checkNumericGet('s', 7);
        $r1 = pg_query("select count(*) from articles");
        $query = "select num, name, oid from pages_articles($size) where page = $page";
        $r2 = pg_query($query);
        if(!$r1 or !$r2)
            throw new Exception("[Controller_articles.Index] Не удается получить данные из базы");
        $this->psize = pg_fetch_array($r1, 0, 0);
        $this->data = $r2;
        $this->page = $page;
    }

    public function view()
    {
        while ($line = pg_fetch_array($this->data, null, PGSQL_ASSOC))
        {
            $caption = $line['num'] . '. ' . $line['name'];
            $url = $this::urlArticle($line['oid']);
            echo $this::link($url, $caption) . ENDL;
        }
    }
}

?>