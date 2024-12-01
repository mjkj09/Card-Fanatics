<?php

require_once 'AppController.php';

class DefaultController extends AppController
{
    public function login()
    {
        $this->render('login');
    }

    public function register()
    {
        $this->render('register');
    }

    public function cardfinder()
    {
        $this->render('cardfinder');
    }

    public function personaldata()
    {
        $this->render('personaldata');
    }

    public function cardsfortrade()
    {
        $this->render('cardsfortrade');
    }

    public function wishlist()
    {
        $this->render('wishlist');
    }
}