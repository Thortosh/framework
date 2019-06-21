<?php

use Anton\Helpers\AuthHelper;

?>
<li>
    <a href="/"> Главная </a>
</li>
<li>

    <?php if (AuthHelper::isAuthorised()): ?>
    <span>Welcome <?= AuthHelper::user('name', '%username%'); ?></span>
    <li>
        <a href="/account/logoff"> Выход </a>
    </li>
<?php else: ?>
    <a href="/account/register"> Регистрация </a>
    <li>
        <a href="/account/login"> Вход </a>
    </li>
<?php endif; ?>
</li>
<li>
    <a href="/news/show"> News </a>
</li>
