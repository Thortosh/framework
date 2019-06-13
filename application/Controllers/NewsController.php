<?php

namespace App\Controllers;

use Anton\Core\Controller;

//application\Framework\Core\Controller;

class NewsController extends Controller
{
    protected $news1 = [1,2];

    public function showAction()
    {
        return $this->render('news.show', ['method' => __METHOD__, 'news' => $this->news1]);
    }
}