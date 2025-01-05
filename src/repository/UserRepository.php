<?php

namespace repository;

use PDO;
use models\User;
use exceptions\UserNotFoundException;

require_once "Repository.php";
require_once __DIR__ . "/../models/User.php";
require_once __DIR__ . "/../exceptions/UserNotFoundException.php";

class UserRepository extends Repository
{
    /**
     * @throws UserNotFoundException
     */
    public function getUserByEmail(string $email): User
    {
        $stmt = $this->database->connect()->prepare("
            SELECT * FROM users WHERE email = :email
        ");

        $stmt->bindParam(":email", $email);
        $stmt->execute();

        $userData = $stmt->fetch(PDO::FETCH_ASSOC);

        // Jeśli nie ma usera o danym emailu — rzucamy wyjątek!
        if (!$userData) {
            throw new UserNotFoundException("User with email $email not found.");
        }

        return new User(
            $userData['email'],
            $userData['password'],
            $userData['name'],
            $userData['surname']
        );
    }
}
