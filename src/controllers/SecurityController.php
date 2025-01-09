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

        if ($this->isGet()) {
            $this->render('login');
            return;
        }

        $email = $_POST['email'];
        $password = $_POST['password'];

        try {
            $user = $userRepository->getUserByEmail($email);
        } catch (UserNotFoundException $e) {
            return $this->render('login', [
                'messages' => ['⚠ ' . $e->getMessage()]
            ]);
        }

        if (!password_verify($password, $user->getPassword())) {
            return $this->render('login', [
                'messages' => ['⚠ Wrong password!']
            ]);
        }

        session_start();
        $_SESSION['user_id'] = $user->getId();

        $url = "http://{$_SERVER['HTTP_HOST']}";
        header("Location: $url/cardsearch");
        exit;
    }

    public function registerUser()
    {
        $userRepository = new UserRepository();
        if ($this->isGet()) {
            $this->render('register');
            return;
        }

        $name     = $_POST['name'] ?? '';
        $surname  = $_POST['surname'] ?? '';
        $email    = $_POST['email'] ?? '';
        $password = $_POST['password'] ?? '';

        if (!$name || !$surname || !$email || !$password) {
            return $this->render('register', [
                'messages' => ['⚠ Please fill all fields!']
            ]);
        }

        try {
            $existing = $userRepository->getUserByEmail($email);
            return $this->render('register', ['messages' => ['⚠ Email already in use!']]);
        } catch (UserNotFoundException $e) {

        }

        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        $conn = $userRepository->getConnection();
        $conn->beginTransaction();
        try {
            $stmt = $conn->prepare("
                INSERT INTO users (email, password)
                VALUES (:email, :pwd) RETURNING id
            ");
            $stmt->bindParam(':email', $email);
            $stmt->bindParam(':pwd', $hashedPassword);
            $stmt->execute();
            $newUserId = $stmt->fetchColumn();

            $stmt2 = $conn->prepare("
                INSERT INTO users_details (id_user, name, surname)
                VALUES (:userId, :name, :surname)
            ");
            $stmt2->bindParam(':userId', $newUserId);
            $stmt2->bindParam(':name', $name);
            $stmt2->bindParam(':surname', $surname);
            $stmt2->execute();

            $conn->commit();
        } catch (\Exception $ex) {
            $conn->rollBack();
            return $this->render('register', [
                'messages' => ["Registration error: " . $ex->getMessage()]
            ]);
        }

        return $this->render('login', [
            'messages'=>['Account created! You can log in now.']
        ]);
    }

    public function logout()
    {
        session_start();
        session_unset();
        session_destroy();

        $this->render('login', ['messages' => ['You have been logged out!']]);
    }
}
