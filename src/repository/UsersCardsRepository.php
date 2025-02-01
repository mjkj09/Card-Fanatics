<?php

namespace repository;

use PDO;

require_once "Repository.php";

class UsersCardsRepository extends Repository
{
    public function addCardForTrade(int $userId, int $cardId, int $quantity = 1): array
    {
        $conn = $this->database->connect();

        $stmtCheck = $conn->prepare("
            SELECT id, quantity
            FROM users_cards
            WHERE id_user = :userId
              AND id_card = :cardId
              AND card_type = 'trade'
        ");
        $stmtCheck->bindParam(':userId', $userId);
        $stmtCheck->bindParam(':cardId', $cardId);
        $stmtCheck->execute();
        $row = $stmtCheck->fetch(PDO::FETCH_ASSOC);

        if ($row) {
            return [
                'status'  => 'error',
                'message' => 'This card is already in your trade list!'
            ];
        }

        $stmtInsert = $conn->prepare("
            INSERT INTO users_cards (id_user, id_card, card_type, quantity)
            VALUES (:userId, :cardId, 'trade', :qty)
        ");
        $stmtInsert->bindParam(':userId', $userId);
        $stmtInsert->bindParam(':cardId', $cardId);
        $stmtInsert->bindParam(':qty', $quantity);
        $stmtInsert->execute();

        return [
            'status'  => 'success',
            'message' => 'Card added with quantity ' . $quantity
        ];
    }

    public function removeCardForTrade(int $userId, int $cardId): void
    {
        $conn = $this->database->connect();
        $stmt = $conn->prepare("
            DELETE FROM users_cards
            WHERE id_user = :userId
              AND id_card = :cardId
              AND card_type = 'trade'
        ");
        $stmt->bindParam(':userId', $userId);
        $stmt->bindParam(':cardId', $cardId);
        $stmt->execute();
    }

    public function updateTradeQuantity(int $userId, int $cardId, int $newQty): array
    {
        $conn = $this->database->connect();

        $stmtCheck = $conn->prepare("
            SELECT id, quantity
            FROM users_cards
            WHERE id_user = :userId
              AND id_card = :cardId
              AND card_type = 'trade'
        ");
        $stmtCheck->bindParam(':userId', $userId);
        $stmtCheck->bindParam(':cardId', $cardId);
        $stmtCheck->execute();
        $row = $stmtCheck->fetch(PDO::FETCH_ASSOC);

        if (!$row) {
            return [
                'status'  => 'error',
                'message' => 'Card not found in trade list!'
            ];
        }

        if ($newQty < 1) {
            return [
                'status'  => 'error',
                'message' => 'Quantity must be >= 1'
            ];
        }

        $stmtUp = $conn->prepare("
            UPDATE users_cards
            SET quantity = :qty
            WHERE id = :id
        ");
        $stmtUp->bindParam(':qty', $newQty);
        $stmtUp->bindParam(':id', $row['id']);
        $stmtUp->execute();

        return [
            'status'  => 'success',
            'message' => 'Quantity updated to ' . $newQty
        ];
    }

    public function addCardToWishlistWithCheck(int $userId, int $cardId): array
    {
        $conn = $this->database->connect();

        $stmtCheck = $conn->prepare("
            SELECT id 
            FROM users_cards
            WHERE id_user = :userId
              AND id_card = :cardId
              AND card_type = 'wishlist'
        ");
        $stmtCheck->bindParam(':userId', $userId);
        $stmtCheck->bindParam(':cardId', $cardId);
        $stmtCheck->execute();
        $row = $stmtCheck->fetch(PDO::FETCH_ASSOC);

        if ($row) {
            return [
                'status'  => 'error',
                'message' => 'This card is already in your wishlist!'
            ];
        }

        $stmt = $conn->prepare("
            INSERT INTO users_cards (id_user, id_card, card_type, quantity)
            VALUES (:userId, :cardId, 'wishlist', 1)
        ");
        $stmt->bindParam(':userId', $userId);
        $stmt->bindParam(':cardId', $cardId);
        $stmt->execute();

        return [
            'status'  => 'success',
            'message' => 'Card added to wishlist'
        ];
    }

    public function addCardToWishlist(int $userId, int $cardId): void
    {
        $conn = $this->database->connect();
    }

    public function removeCardFromWishlist(int $userId, int $cardId): void
    {
        $conn = $this->database->connect();
        $stmt = $conn->prepare("
            DELETE FROM users_cards
            WHERE id_user = :userId
              AND id_card = :cardId
              AND card_type = 'wishlist'
        ");
        $stmt->bindParam(':userId', $userId);
        $stmt->bindParam(':cardId', $cardId);
        $stmt->execute();
    }

    public function fetchCardsByType(int $userId, string $type): array
    {
        $conn = $this->database->connect();
        $stmt = $conn->prepare("
            SELECT 
                c.code, 
                c.parallel, 
                c.player_name,         
                c.player_surname,      
                col.name AS collection, 
                uc.quantity
            FROM users_cards uc
            JOIN cards c ON uc.id_card = c.id
            JOIN collections col ON c.id_collection = col.id
            WHERE uc.id_user = :userId
              AND uc.card_type = :type
            ORDER BY c.code
        ");
        $stmt->bindParam(':userId', $userId);
        $stmt->bindParam(':type', $type);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
