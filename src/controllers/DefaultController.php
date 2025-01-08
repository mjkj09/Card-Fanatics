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
        $this->ensureLoggedIn();
        $this->render('cardsearch');
    }

    public function personaldata()
    {
        $this->ensureLoggedIn();
        $this->render('personaldata');
    }

    public function cardsfortrade()
    {
        $this->ensureLoggedIn();
        $this->render('cardsfortrade');
    }

    public function wishlist()
    {
        $this->ensureLoggedIn();
        $this->render('wishlist');
    }

    private function ensureLoggedIn()
    {
        session_start();
        if (!isset($_SESSION['user_id'])) {
            $url = "http://{$_SERVER['HTTP_HOST']}";
            header("Location: $url");
            exit;
        }
    }
}
