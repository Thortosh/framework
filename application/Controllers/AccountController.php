<?php

namespace App\Controllers;

use Anton\Core\Controller;
use Anton\Core\Request;
use Anton\Helpers\AuthHelper;

class AccountController extends Controller
{
    /**
     * @return string
     * Станица авторизации
     * HTTP METHOD: GET
     */
    public function loginAction()
    {
        return $this->render('account.login', ['method' => __METHOD__]);
    }

    /**
     * @return string
     * Страница пользователя
     * HTTP METHOD: GET
     */
    public function meAction()
    {
        return $this->render('account.me', ['method' => __METHOD__]);
    }

    /**
     * @return string
     * Страница регистрации
     * HTTP METHOD: GET
     */
    public function registerAction()
    {
        return $this->render('account.register', ['method' => __METHOD__]);
    }

    /**
     * Страница создания акаунта
     * HTTP METHOD: POST
     */
    public function createAction()
    {
        AuthHelper::register();

//           var_dump(Request::all()['email']);
//        return $this->render('account.register', ['method' => __METHOD__]);
    }

    /**
     * @throws \Anton\Exceptions\BuilderGetterException
     * @throws \Anton\Exceptions\UnaccaptableOperatorException
     * HTTP METHOD: POST
     */
    public function authAction()
    {
        AuthHelper::authorise();
    }

    /**
     * HTTP METHOD: POST
     */
    public function logoffAction()
    {
        AuthHelper::unauthorise();
    }
}