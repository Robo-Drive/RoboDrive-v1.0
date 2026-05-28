<?php

namespace app\controllers;

use app\core\Controller;

class HomeController extends Controller
{
    public function home()
    {
        $this->view("home/home");
    }
}