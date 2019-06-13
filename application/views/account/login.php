<?php
/** @var $method */
/** @var $errors */
Anton\Helpers\ViewsHelper::import('partials.header');

?>
    Введите логин и пароль<br/>
<!--    Ее нарисовал метод :  --><?//= $method; ?>

<form class="form container" action="/account/auth" method="post">
    <h2>Вход</h2>
    <div class="form__item <?= key_exists('email', $errors) ? 'form__item--invalid' : '' ?>">
        <label for="email">E-mail*</label>
        <input id="email" type="text" name="email" placeholder="Введите e-mail"
               value="<?//= $data['email'] ?? '' ?>">
        <? //if (key_exists('email', $errors)) : ?>
            <span class="form__error" style="color: red"><?= $errors['email']; ?></span>
        <? //endif; ?>
    </div>
    <div class="form__item <?= key_exists('password', $errors) ? 'form__item--invalid' : '' ?>">
        <label for="password">Пароль*</label>
        <input id="password" type="text" name="password" placeholder="Введите пароль"
               value="<?//= $data['password'] ?? '' ?>">
        <? //if (key_exists('password', $errors)) : ?>
            <span class="form__error" style="color: red"><?= $errors['password'] ?></span>
        <? //endif; ?>
    </div>
    <button type="submit" class="button">Войти</button>
</form>
