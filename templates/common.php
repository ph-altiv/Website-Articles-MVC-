<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8" />
    <title></title>
    <meta name="keywords" content="" />
    <meta name="description" content="" />
    <link href="/../styles/common.css" rel="stylesheet">
</head>

<body>

<div class="wrapper">

    <header class="header">
        It's header
    </header>

    <div class="middle">

        <div class="container">
            <main class="content">
                <?php
                    if(empty($controller) or !is_callable(array($controller, 'view')))
                        throw new Exception('[Common] Контроллер еще не поготовлен к выводу');
                    $controller->view();
                ?>
            </main>
        </div>

        <aside class="left-sidebar">
            Left Sidebar
            <a href="#" class="switch_content">Статьи</a>
            <a href="#" class="switch_content">Форма</a>
        </aside>

    </div>

    <footer class="footer">
        It's footer
    </footer>

</div>

</body>
</html>