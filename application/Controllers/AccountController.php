<?php

namespace App\Controllers;

use Anton\Core\Controller;
use Anton\Helpers\AuthHelper;

class AccountController extends Controller
{
    /**
     * @return string
     */
    public function loginAction()
    {
        return $this->render('account.login', ['method' => __METHOD__]);
    }

    /**
     * @return string
     */
    public function meAction()
    {
        return $this->render('account.me', ['method' => __METHOD__]);
    }

    public function authAction()
    {
        AuthHelper::authorise();
    }

    public function logoffAction()
    {
        AuthHelper::unauthorise();
    }
}