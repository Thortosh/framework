<?php
/**
 * @var $method
 * @var $news
 */
\Anton\Helpers\ViewsHelper::import('partials.header');

?>
Это страница новостей<br/>
Ее нарисовал метод : <?= $method; ?> </br>

<? foreach ($news as $key) : ?>
    <li class="nav__item">
        <a href="">Новость <?= $key ?></a>
    </li>
<? endforeach; ?>

