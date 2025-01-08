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
    public function getUserById(int $id): ?User
    {
        $conn = $this->database->connect();

        $stmt = $conn->prepare("
            SELECT u.id, u.email, u.password, d.name, d.surname, d.phone, d.instagram
            FROM users u
            LEFT JOIN users_details d ON u.id = d.id_user
            WHERE u.id = :id
        ");
        $stmt->bindParam(":id", $id);
        $stmt->execute();
        $userData = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$userData) {
            return null;
        }

        $user = new User(
            (int)$userData['id'],
            $userData['email'],
            $userData['password'],
            $userData['name'] ?? '',
            $userData['surname'] ?? ''
        );
        $user->setPhone($userData['phone'] ?? null);
        $user->setInstagram($userData['instagram'] ?? null);

        return $user;
    }

    public function getUserByEmail(string $email): User
    {
        $conn = $this->database->connect();

        $stmt = $conn->prepare("
            SELECT u.id, u.email, u.password, d.name, d.surname, d.phone, d.instagram
            FROM users u
            LEFT JOIN users_details d ON u.id = d.id_user
            WHERE u.email = :email
        ");
        $stmt->bindParam(":email", $email);
        $stmt->execute();
        $userData = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$userData) {
            throw new UserNotFoundException("User with email $email not found.");
        }

        $user = new User(
            (int)$userData['id'],
            $userData['email'],
            $userData['password'],
            $userData['name'] ?? '',
            $userData['surname'] ?? ''
        );
        $user->setPhone($userData['phone'] ?? null);
        $user->setInstagram($userData['instagram'] ?? null);

        return $user;
    }

    public function updateUserDetails(
        int $userId,
        string $name,
        string $surname,
        string $email,
        ?string $phone,
        ?string $instagram
    ): void {
        $conn = $this->database->connect();

        $stmt = $conn->prepare("
            UPDATE users
            SET email = :email
            WHERE id = :userId
        ");
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':userId', $userId);
        $stmt->execute();

        $stmtCheck = $conn->prepare("SELECT id FROM users_details WHERE id_user = :userId");
        $stmtCheck->bindParam(':userId', $userId);
        $stmtCheck->execute();
        $detailId = $stmtCheck->fetchColumn();

        if ($detailId) {
            $stmt2 = $conn->prepare("
                UPDATE users_details
                SET name = :name,
                    surname = :surname,
                    phone = :phone,
                    instagram = :instagram
                WHERE id_user = :userId
            ");
        } else {
            $stmt2 = $conn->prepare("
                INSERT INTO users_details (id_user, name, surname, phone, instagram)
                VALUES (:userId, :name, :surname, :phone, :instagram)
            ");
        }

        $stmt2->bindParam(':userId', $userId);
        $stmt2->bindParam(':name', $name);
        $stmt2->bindParam(':surname', $surname);
        $stmt2->bindParam(':phone', $phone);
        $stmt2->bindParam(':instagram', $instagram);
        $stmt2->execute();
    }

    public function getConnection()
    {
        return $this->database->connect();
    }
}
