<?php

namespace repository;

require_once "Repository.php";

class CardRepository extends Repository
{
    public function findOrCreateCard(string $code, int $collectionId, ?string $parallel = '', string $playerName, string $playerSurname): int
    {
        $code = strtoupper($code);
        $parallel = strtoupper($parallel);
        $playerName = strtoupper($playerName);
        $playerSurname = strtoupper($playerSurname);

        $conn = $this->database->connect();

        $stmt = $conn->prepare("
            SELECT id
            FROM cards
            WHERE code = :code
              AND id_collection = :collectionId
              AND parallel = :parallel
              AND player_name = :playerName      
              AND player_surname = :playerSurname  
        ");
        $stmt->bindParam(':code', $code);
        $stmt->bindParam(':collectionId', $collectionId);
        $stmt->bindParam(':parallel', $parallel);
        $stmt->bindParam(':playerName', $playerName);
        $stmt->bindParam(':playerSurname', $playerSurname);
        $stmt->execute();
        $foundId = $stmt->fetchColumn();
        if ($foundId) {
            return $foundId;
        }

        $stmt2 = $conn->prepare("
            INSERT INTO cards (code, id_collection, parallel, player_name, player_surname)
            VALUES (:code, :collectionId, :parallel, :playerName, :playerSurname)
            RETURNING id
        ");
        $stmt2->bindParam(':code', $code);
        $stmt2->bindParam(':collectionId', $collectionId);
        $stmt2->bindParam(':parallel', $parallel);
        $stmt2->bindParam(':playerName', $playerName);
        $stmt2->bindParam(':playerSurname', $playerSurname);
        $stmt2->execute();

        return $stmt2->fetchColumn();
    }

    public function getCardIdByCodeAndCollection(string $code, int $collectionId, ?string $parallel = '', string $playerName, string $playerSurname): ?int
    {
        $code = strtoupper($code);
        $parallel = strtoupper($parallel);
        $playerName = strtoupper($playerName);
        $playerSurname = strtoupper($playerSurname);

        $conn = $this->database->connect();
        $stmt = $conn->prepare("
            SELECT id 
            FROM cards
            WHERE code = :code
              AND id_collection = :collectionId
              AND parallel = :parallel
              AND player_name = :playerName      
              AND player_surname = :playerSurname  
        ");
        $stmt->bindParam(':code', $code);
        $stmt->bindParam(':collectionId', $collectionId);
        $stmt->bindParam(':parallel', $parallel);
        $stmt->bindParam(':playerName', $playerName);
        $stmt->bindParam(':playerSurname', $playerSurname);
        $stmt->execute();

        $id = $stmt->fetchColumn();
        return $id ?: null;
    }

    public function searchTradeCardsAllFields(string $query): array
    {
        $conn = $this->database->connect();
        $pattern = '%'.strtoupper($query).'%';

        $sql = "
        SELECT
            uc.id_user AS user_id,
            c.code,
            c.parallel,
            c.player_name,         
            c.player_surname,     
            col.name AS collection,
            uc.quantity
        FROM users_cards uc
        JOIN cards c ON uc.id_card = c.id
        JOIN collections col ON c.id_collection = col.id
        WHERE uc.card_type = 'trade'
          AND (
               UPPER(c.code) ILIKE :pattern
            OR UPPER(col.name) ILIKE :pattern
            OR UPPER(c.parallel) ILIKE :pattern
            OR UPPER(c.player_name) ILIKE :pattern   
            OR UPPER(c.player_surname) ILIKE :pattern  
          )
        ORDER BY c.code
    ";

        $stmt = $conn->prepare($sql);
        $stmt->bindValue(':pattern', $pattern);
        $stmt->execute();
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }
}
