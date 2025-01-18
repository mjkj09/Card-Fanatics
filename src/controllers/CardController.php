<?php

namespace controllers;

use repository\UsersCardsRepository;
use repository\CardRepository;
use repository\CollectionRepository;

require_once 'AppController.php';
require_once __DIR__ . '/../repository/UsersCardsRepository.php';
require_once __DIR__ . '/../repository/CardRepository.php';
require_once __DIR__ . '/../repository/CollectionRepository.php';

class CardController extends AppController
{
    private $usersCardsRepo;
    private $cardRepo;
    private $collectionRepo;

    public function __construct()
    {
        parent::__construct();
        $this->usersCardsRepo = new UsersCardsRepository();
        $this->cardRepo = new CardRepository();
        $this->collectionRepo = new CollectionRepository();
    }

    public function addCardForTrade()
    {
        $this->handleRequest('trade', 'cardsfortrade', 'add');
    }

    public function removeCardForTrade()
    {
        $this->handleRequest('trade', 'cardsfortrade', 'remove');
    }

    public function addCardToWishlist()
    {
        $this->handleRequest('wishlist', 'wishlist', 'add');
    }

    public function removeCardFromWishlist()
    {
        $this->handleRequest('wishlist', 'wishlist', 'remove');
    }

    private function handleRequest(string $type, string $view, string $operation): void
    {
        if (!$this->isPost()) {
            http_response_code(405);
            return;
        }

        session_start();
        if(!isset($_SESSION['user_id'])){
            header("Location: /");
            exit;
        }
        $userId = $_SESSION['user_id'];

        $cardCode       = $_POST['cardCode']       ?? null;
        $collectionName = $_POST['collectionName'] ?? null;
        $parallel       = $_POST['parallel']       ?? '';
        $quantity       = (int)($_POST['quantity'] ?? 1);

        if (!$cardCode || !$collectionName) {
            header('Content-Type: application/json; charset=utf-8');
            echo json_encode([
                'status'  => 'error',
                'message' => 'Missing card code or collection'
            ]);
            return;
        }

        if ($operation === 'add') {
            $collectionId = $this->collectionRepo->findOrCreateCollection($collectionName);
        } else {
            $collectionId = $this->collectionRepo->getCollectionIdByName($collectionName);
            if (!$collectionId) {
                header('Content-Type: application/json; charset=utf-8');
                echo json_encode(['status'=>'error','message'=>'Collection not found']);
                return;
            }
        }

        if ($operation === 'add') {
            $cardId = $this->cardRepo->findOrCreateCard($cardCode, $collectionId, $parallel);
        } else {
            $cardId = $this->cardRepo->getCardIdByCodeAndCollection($cardCode, $collectionId, $parallel);
            if (!$cardId) {
                header('Content-Type: application/json; charset=utf-8');
                echo json_encode(['status'=>'error','message'=>'Card not found']);
                return;
            }
        }

        if ($operation === 'add') {
            if ($type === 'trade') {
                $result = $this->usersCardsRepo->addCardForTrade($userId, $cardId, $quantity);

            } else {
                $result = $this->usersCardsRepo->addCardToWishlistWithCheck($userId, $cardId);
            }
            header('Content-Type: application/json; charset=utf-8');
            echo json_encode($result);

        } else {
            if ($type === 'trade') {
                $this->usersCardsRepo->removeCardForTrade($userId, $cardId);
            } else {
                $this->usersCardsRepo->removeCardFromWishlist($userId, $cardId);
            }

            header('Content-Type: application/json; charset=utf-8');
            echo json_encode(['status'=>'success','message'=>'Card removed']);
        }
    }

    public function getTradeCards()
    {
        if (!$this->isGet()) {
            http_response_code(405);
            return;
        }
        session_start();
        $userId = $_SESSION['user_id'] ?? 1;

        $cards = $this->usersCardsRepo->fetchCardsByType($userId, 'trade');
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode(['status'=>'success','cards'=>$cards]);
    }

    public function getWishlistCards()
    {
        if (!$this->isGet()) {
            http_response_code(405);
            return;
        }
        session_start();
        $userId = $_SESSION['user_id'] ?? 1;

        $cards = $this->usersCardsRepo->fetchCardsByType($userId, 'wishlist');
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode(['status'=>'success','cards'=>$cards]);
    }

    public function updateTradeQuantity()
    {
        if (!$this->isPost()) {
            http_response_code(405);
            return;
        }

        session_start();
        $userId = $_SESSION['user_id'] ?? 1;

        $cardCode       = $_POST['cardCode']       ?? null;
        $collectionName = $_POST['collectionName'] ?? null;
        $parallel       = $_POST['parallel']       ?? '';
        $newQty         = (int) ($_POST['newQuantity'] ?? 1);

        $collectionId = $this->collectionRepo->getCollectionIdByName($collectionName);
        if (!$collectionId) {
            header('Content-Type: application/json; charset=utf-8');
            echo json_encode(['status'=>'error','message'=>'Collection not found']);
            return;
        }

        $cardId = $this->cardRepo->getCardIdByCodeAndCollection($cardCode, $collectionId, $parallel);
        if (!$cardId) {
            header('Content-Type: application/json; charset=utf-8');
            echo json_encode(['status'=>'error','message'=>'Card not found']);
            return;
        }

        $result = $this->usersCardsRepo->updateTradeQuantity($userId, $cardId, $newQty);
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($result);
    }

    public function searchTradeCardsAllFields()
    {
        if (!$this->isGet()) {
            http_response_code(405);
            return;
        }

        $query = $_GET['query'] ?? '';
        $cards = $this->cardRepo->searchTradeCardsAllFields($query);

        header('Content-Type: application/json; charset=utf-8');
        echo json_encode([
            'status' => 'success',
            'cards'  => $cards
        ]);
    }
}
