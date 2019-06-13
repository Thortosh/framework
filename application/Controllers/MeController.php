<?php

namespace App\Controllers;

use Anton\Core\Controller;

//application\Framework\Core\Controller;

class MeController extends Controller
{
    public function meAction()
    {
        return $this->render('account.me', ['method' => __METHOD__]);
    }

}