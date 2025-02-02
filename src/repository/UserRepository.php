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

    /**
     * @throws UserNotFoundException
     */
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

    public static function isUserAdmin(int $userId): bool
    {
        $db = new self();
        $conn = $db->database->connect();

        $stmt = $conn->prepare("
            SELECT 1
            FROM users_roles ur
            JOIN roles r ON ur.id_role = r.id
            WHERE ur.id_user = :userId
              AND r.role_name = 'admin'
            LIMIT 1
        ");
        $stmt->bindParam(':userId', $userId);
        $stmt->execute();

        return (bool) $stmt->fetchColumn();
    }

    public static function isUserBanned(int $userId): bool
    {
        $db = new self();
        $conn = $db->database->connect();
        $stmt = $conn->prepare("
            SELECT 1
            FROM bans
            WHERE id_user = :userId
            LIMIT 1
        ");
        $stmt->bindParam(':userId', $userId);
        $stmt->execute();
        return (bool) $stmt->fetchColumn();
    }

    public static function getBanReason(int $userId): ?string
    {
        $db = new self();
        $conn = $db->database->connect();
        $stmt = $conn->prepare("
            SELECT reason
            FROM bans
            WHERE id_user = :userId
            ORDER BY banned_at DESC
            LIMIT 1
        ");
        $stmt->bindParam(':userId', $userId);
        $stmt->execute();

        $reason = $stmt->fetchColumn();
        return $reason ?: null;
    }

    public function getAllUsersForAdmin(): array
    {
        $conn = $this->database->connect();
        $sql = "
            SELECT
                u.id,
                u.email,
                COALESCE(d.name, '') as name,
                COALESCE(d.surname, '') as surname,
                CASE
                    WHEN b.id_user IS NOT NULL THEN true
                    ELSE false
                END as is_banned
            FROM users u
            LEFT JOIN users_details d ON u.id = d.id_user
            LEFT JOIN bans b ON u.id = b.id_user
            ORDER BY u.created_at DESC
        ";
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function banUser(int $userId, string $reason): array
    {
        $conn = $this->getConnection();
        try {
            $conn->beginTransaction();

            $stmtBan = $conn->prepare("
                INSERT INTO bans (id_user, reason)
                VALUES (:userId, :reason)
            ");
            $stmtBan->bindParam(':userId', $userId);
            $stmtBan->bindParam(':reason', $reason);
            $stmtBan->execute();

            $stmtDel = $conn->prepare("
                DELETE FROM users_cards
                WHERE id_user = :userId
            ");
            $stmtDel->bindParam(':userId', $userId);
            $stmtDel->execute();

            $conn->commit();
            return [
                'status' => 'success',
                'message' => "User $userId banned successfully"
            ];
        } catch (\Exception $e) {
            $conn->rollBack();
            return [
                'status' => 'error',
                'message' => 'Ban failed: '.$e->getMessage()
            ];
        }
    }
}
