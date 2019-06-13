<?php

namespace Anton\Helpers;

use Anton\Core\Request;

class AuthHelper
{
    /**
     * @return bool
     */
    public static function isAuthorised()
    {
        if (isset($_SESSION['user'])) {
            return true;
        }
        return false;
    }

    /**
     * @return bool
     */
    public static function authorise()
    {
        $errors = Request::validate(['email', 'password']);
        if (count($errors)) {
            return HttpHelper::redirect('/account/login', $errors);
        }

        $user = require CONFIG_PATH . 'user.php';

        if (!count($errors) && $user = self::searchUserByEmail(Request::get('email'), $user)) {
            if (password_verify(Request::get('password'), $user['password'])) {
                $_SESSION['user'] = serialize($user);
            } else {
                $errors['password'] = 'Неверный пароль.';
            }
        } else {
            $errors['email'] = 'Такого email не существует.';
        }

        if (count($errors)) {
            return HttpHelper::redirect('/account/login', $errors);
        } else {
            return HttpHelper::redirect('/account/me');
        }
    }

    /**
     * @param null $field
     * @return array|mixed
     */
    public static function user($field = null, $default = '')
    {
        $user = unserialize($_SESSION['user']);

        if (!$field) {
            return $user ?? $default;
        }

        return $user[$field] ?? $default;
    }

    /**
     * @param $email
     * @param $users
     * @return mixed|null
     * Функция для проверки email. Первый аргумент содержит данный введенные пользователем, второй аргумент данные на сервере
     */
    protected static function searchUserByEmail($email, $users)
    {
        $result = null;
        foreach ($users as $user) {
            if ($user['email'] == $email) {                // Проходимся по массиву, если данные которые ввел пользователь соответствуют данным на сервере
                $result = $user;                           // Записываем в переменную result данные введенные пользователем
                break;
            }
        }
        return $result;                                    // Возвращаем содержимое переменной result
    }

    public static function unauthorise()
    {
        session_destroy();
        HttpHelper::redirect('/');
    }

}