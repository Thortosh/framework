<?php
/** @var $method */
/** @var array $users */
Anton\Helpers\ViewsHelper::import('partials.header');
?>

Это главная страница<br/>
Ее нарисовал метод <?= $method ?>
<!--<br><br><span style="font-weight: bold; font-family: monospace; font-size: larger;">--><?//foreach ($users as $user) { ?><!--</span>-->
<!--<br>-->
<!--    --><?//= $user->id . ': '. $user->email ?>
<!--<br>-->
<?// } ?>