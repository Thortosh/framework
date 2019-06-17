<?php

namespace App\Controllers;

use Anton\Core\Controller;
use Anton\Exceptions\BuilderGetterException;
use App\Models\UserModel;

class MainController extends Controller
{
    /**
     * @return string
     * @throws BuilderGetterException
     * @throws \Exception
     */
    public function indexAction()
    {
        $users = UserModel::query()->get();

        return $this->render('main.index', ['method' => __METHOD__, 'users' => $users]);
    }
}



