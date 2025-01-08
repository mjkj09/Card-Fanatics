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
}
