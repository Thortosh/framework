<?php

namespace Anton\Helpers;

use Anton\Core\Request;
use Anton\Database\Builder;
use App\Models\UserModel;

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
     * @throws \Anton\Exceptions\BuilderGetterException
     * @throws \Anton\Exceptions\UnaccaptableOperatorException
     * Метод authorise - авторизует пользователя.
     * В переменную $errors записываем массив с ошибками (метод validate - проверяет все ли поля заполнены)
     * Если переменная $errors не пустая возвращаем страницу авторизации с ошибками.
     * В переменную $user записываем SQL запрос: "SELECT * FROM users WHERE email = (email который ввел пользователь)".
     * Получаем объект object(App\Models\UserModel)#9 (2) { ["hidden":protected]=> array(1) { [0]=> string(8) "password" } ["attributes":protected]
     * => array(4) { ["id"]=> string(1) "1" ["email"]=> string(12) "asdf@mail.ru" ["name"]=> string(4) "asdf" ["password"]=> string(60) "$2y$10$GZ.a3lkm9H2SKvAPg0fEjOUYfsbyxILglr4s7I/0VOJyY.rzntk4." } }
     * Если email был введен не правильно, записываем в массив $errors['email'] = Пользователь не найден
     * Если пользователь с таким email существует, но не правильный пароль, записываем в массив ошибок $errors['password'] = Неверный пароль
     * Если переменная $errors не пустая возвращаем страницу авторизации с ошибками.
     * После того как все проверки были пройдены, записываем в $_SESSION['user'] = данные user и перекидываем на главную страницу (serialize — Генерирует пригодное для хранения представление переменной)
     */
    public static function authorise()
    {

        $errors = Request::validate(['email', 'password']);
        if (count($errors)) {
            return HttpHelper::redirect('/account/login', $errors);
        }

        /**
         * @var array $users = [UserModel] | []
         */
        $user = UserModel::query()
            ->where('email', '=', Request::get('email'))
            ->first();


        if (!$user) {
            $errors['email'] = 'Пользователь не найден';
        }
        if ($user && !password_verify(Request::get('password'), $user->get('password'))) {
            $errors['password'] = 'Неверный пароль';
        }

        if (count($errors)) {
            return HttpHelper::redirect('/account/login', $errors);
        }

        $_SESSION['user'] = serialize($user);
        return HttpHelper::redirect('/account/me');

    }

    /**
     * @param null $field
     * @param string $default
     * @return array|mixed
     * Статический метод user - фильрует данные переданные в сессию
     */
    public static function user($field = null, $default = '')
    {

        //var_dump($_SESSION['user']);
        $user = unserialize($_SESSION['user']);

        if (!$field) {
            return $user ?? $default;
        }
        //var_dump($user->get($field) ?? $default;);
        return $user->get($field) ?? $default;          //имя user либо пустая строка
    }

    /**
     * @return bool
     * Получаем получаем данные который передал пользователь(проверяем заполнил ли пользователь все поля)
     *
     */
    public static function register()
    {
        $errors = Request::validate(['email', 'password', 'name']);
        if (count($errors)) {
            return HttpHelper::redirect('/account/register', $errors);
        }
        $user = UserModel::create([
            'email' => Request::get('email'),
            'password' => password_hash(Request::get('password'), PASSWORD_DEFAULT),
            'name' => Request::get('name')
        ]);

//        return Request::all();
//        $userdata = Request::all();                                         //INSERT INTO users SET email = 'ignat@mail.ru', name = 'Ignat', password = 'qwer';
//        $email = $userdata['email'];
//        $name = $userdata['name'];
//        $password = $userdata['password'];
//        return 'SET' . ' email = ' . $email . ', name = ' . $name . ', password = ' . $password;

        if (!$user) {
            $errors['null_user'] = 'Shit happens';
            return HttpHelper::redirect('/account/register', $errors);
        }

        $_SESSION['user'] = serialize($user);
        return HttpHelper::redirect('/account/me');
    }


    /**
     * @return bool
     */
    public static function unauthorise()
    {
        session_destroy();
        return HttpHelper::redirect('/');
    }

}