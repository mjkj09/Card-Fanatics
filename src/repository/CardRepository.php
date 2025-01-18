<?php

namespace repository;

require_once "Repository.php";

class CardRepository extends Repository
{
    public function findOrCreateCard(string $code, int $collectionId, ?string $parallel = ''): int
    {
        $code = strtoupper($code);
        $parallel = strtoupper($parallel);

        $conn = $this->database->connect();

        $stmt = $conn->prepare("
        SELECT id
        FROM cards
        WHERE code = :code
          AND id_collection = :collectionId
          AND parallel = :parallel
    ");
        $stmt->bindParam(':code', $code);
        $stmt->bindParam(':collectionId', $collectionId);
        $stmt->bindParam(':parallel', $parallel);
        $stmt->execute();
        $foundId = $stmt->fetchColumn();
        if ($foundId) {
            return $foundId;
        }

        $stmt2 = $conn->prepare("
        INSERT INTO cards (code, id_collection, parallel)
        VALUES (:code, :collectionId, :parallel)
        RETURNING id
    ");
        $stmt2->bindParam(':code', $code);
        $stmt2->bindParam(':collectionId', $collectionId);
        $stmt2->bindParam(':parallel', $parallel);
        $stmt2->execute();

        return $stmt2->fetchColumn();
    }


    public function getCardIdByCodeAndCollection(string $code, int $collectionId, ?string $parallel = ''): ?int
    {
        $code = strtoupper($code);
        $parallel = strtoupper($parallel);

        $conn = $this->database->connect();
        $stmt = $conn->prepare("
            SELECT id 
            FROM cards
            WHERE code = :code
              AND id_collection = :collectionId
              AND parallel = :parallel
        ");
        $stmt->bindParam(':code', $code);
        $stmt->bindParam(':collectionId', $collectionId);
        $stmt->bindParam(':parallel', $parallel);
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
        c.code,
        c.parallel,
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
      )
    ORDER BY c.code
    ";

        $stmt = $conn->prepare($sql);
        $stmt->bindValue(':pattern', $pattern);
        $stmt->execute();
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }
}
