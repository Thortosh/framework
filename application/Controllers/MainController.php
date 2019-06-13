<?php

namespace App\Controllers;

use Anton\Core\Controller;
use Anton\Database\Builder;

//application\Framework\Core\Controller;

class MainController extends Controller
{
    public function indexAction()
    {
        $builder = new Builder();
        $users = $builder->select('id', 'email')
            ->from('userdata')
            ->where('name', '<>', 'Ivan')
            ->where('id', '>=', 1)
            ->orderBy('email', 'DESC')
//            ->limit(1)
//            ->offset(2)
            ->get();

        return $this->render('main.index', ['method' => __METHOD__, 'users' => $users]);
    }
}



