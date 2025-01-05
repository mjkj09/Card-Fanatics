<?php

namespace controllers;

require_once 'AppController.php';

class DefaultController extends AppController
{
    public function index()
    {
        $this->render('login');
    }

    public function register()
    {
        $this->render('register');
    }

    public function cardsearch()
    {
        $this->render('cardsearch');
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