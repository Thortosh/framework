<?php
/** @var $method */
\Anton\Helpers\ViewsHelper::import('partials.header');

 ?>
Мой аккаунт <?= \Anton\Helpers\AuthHelper::user('name') ?><br/>
Ее нарисовал метод <?= $method ?>
