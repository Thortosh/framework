<?php

namespace App\Controllers;

use Anton\Core\Controller;
use Anton\Database\Builder;
use App\Models\UserModel;

//application\Framework\Core\Controller;

class MainController extends Controller
{
    public function indexAction()
    {
        $users = UserModel::query()->get();

        return $this->render('main.index', ['method' => __METHOD__, 'users' => $users]);
    }
}



