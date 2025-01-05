<?php

namespace controllers;

use repository\UserRepository;
use exceptions\UserNotFoundException;

require_once "AppController.php";
require_once __DIR__ . "/../models/User.php";
require_once __DIR__ . "/../repository/UserRepository.php";
require_once __DIR__ . "/../exceptions/UserNotFoundException.php";

class SecurityController extends AppController
{
    public function login()
    {
        $userRepository = new UserRepository();

        // Jeśli żądanie GET, wyświetlamy formularz logowania
        if ($this->isGet()) {
            $this->render('login');
            return;
        }

        // Jeśli żądanie POST, sprawdzamy dane
        $email = $_POST['email'];
        $password = $_POST['password'];

        try {
            $user = $userRepository->getUserByEmail($email);
        } catch (UserNotFoundException $e) {
            // Jeśli wyrzuciliśmy wyjątek -> nie ma takiego usera w bazie
            $this->render('login', ['messages' => ['⚠ ' . $e->getMessage()]]);
            return;
        }

        // Sprawdzenie hasła
        if ($user->getPassword() !== $password) {
            $this->render('login', ['messages' => ['⚠ Wrong password!']]);
            return;
        }

        // Jeśli wszystko OK, przekierowanie na /cardsearch
        $url = "http://{$_SERVER['HTTP_HOST']}";
        header("Location: $url/cardsearch");
        exit;
    }
}