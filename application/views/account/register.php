<?php

/** @var $method */
/** @var $errors */
Anton\Helpers\ViewsHelper::import('partials.header');

use Anton\Helpers\ViewsHelper; ?>
Введите логин и пароль<br/>
<!--    Ее нарисовал метод :  --><? //= $method; ?>

<form class="form container" action="/account/create" method="post">
    <h2>Регистрация</h2>
    <? if (key_exists('null_user', $errors)) { ?>
        <span class="form__error"><?= $errors['null_user'] ?></span>
    <? } ?>
    <div class="form__item <?= key_exists('email', $errors) ? 'form__item--invalid' : '' ?>">
        <label for="email">E-mail*</label>
        <input id="email" type="email" name="email" placeholder="Введите e-mail" value="">
        <span class="form__error" style="color: red"><?= $errors['email']; ?></span>
    </div>
    <div class="form__item <?= key_exists('password', $errors) ? 'form__item--invalid' : '' ?>">
        <label for="password">Пароль*</label>
        <input id="password" type="password" name="password" placeholder="Введите пароль"
               value="<?//= $data['password'] ?? '' ?>">
        <span class="form__error" style="color: red"><?= $errors['password'] ?></span>
    </div>
    <div class="form__item <?= key_exists('password', $errors) ? 'form__item--invalid' : '' ?>">
        <label for="password">Имя*</label>
        <input id="password" type="password" name="name" placeholder="Введите имя"
               value="<?//= $data['password'] ?? '' ?>">
        <span class="form__error" style="color: red"><?= $errors['password'] ?></span>
    </div>
    <button type="submit" class="button">Создайте учётную запись</button>
</form>
