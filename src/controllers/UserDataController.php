<?php

namespace controllers;

use repository\CollectionRepository;
use repository\UserRepository;
use repository\UsersCardsRepository;

require_once __DIR__ . '/AppController.php';
require_once __DIR__ . '/../repository/UserRepository.php';

class UserDataController extends AppController
{
    private $userRepository;

    public function __construct()
    {
        parent::__construct();
        $this->userRepository = new UserRepository();
    }

    public function getUserData()
    {
        if (!$this->isGet()) {
            http_response_code(405);
            return;
        }

        session_start();
        if (!isset($_SESSION['user_id'])) {
            header("Location: /");
            exit;
        }
        $userId = $_SESSION['user_id'];

        $user = $this->userRepository->getUserById($userId);
        if (!$user) {
            http_response_code(404);
            echo json_encode(['status' => 'error', 'message' => 'User not found']);
            return;
        }

        header('Content-Type: application/json; charset=utf-8');
        echo json_encode([
            'status' => 'success',
            'data' => [
                'email' => $user->getEmail(),
                'name' => $user->getName(),
                'surname' => $user->getSurname(),
                'phone' => $user->getPhone(),
                'instagram' => $user->getInstagram()
            ]
        ]);
    }

    public function updatePersonalData()
    {
        if (!$this->isPost()) {
            http_response_code(405);
            return;
        }

        session_start();
        $userId = $_SESSION['user_id'] ?? 1;

        // Pobierz dane z POST
        $name = $_POST['name'] ?? '';
        $surname = $_POST['surname'] ?? '';
        $phone = $_POST['phone'] ?? '';
        $instagram = $_POST['instagram'] ?? '';

        $user = $this->userRepository->getUserById($userId);
        if (!$user) {
            http_response_code(404);
            echo json_encode(['status' => 'error', 'message' => 'User not found']);
            return;
        }
        $email = $user->getEmail();

        $this->userRepository->updateUserDetails($userId, $name, $surname, $email, $phone, $instagram);

        header('Content-Type: application/json; charset=utf-8');
        echo json_encode(['status' => 'success', 'message' => 'Data updated']);
    }

    public function getUserCollections()
    {
        if (!$this->isGet()) {
            http_response_code(405);
            return;
        }

        session_start();
        $userId = $_SESSION['user_id'] ?? null;

        if (!$userId) {
            header('Content-Type: application/json; charset=utf-8');
            echo json_encode(['status' => 'error', 'message' => 'User not logged in']);
            return;
        }

        $collectionRepo = new CollectionRepository();
        $collections = $collectionRepo->getCollectionsByUserId($userId);

        header('Content-Type: application/json; charset=utf-8');
        echo json_encode(['status' => 'success', 'collections' => $collections]);

        error_log('getUserCollections: Start');
        error_log('User ID: ' . $_SESSION['user_id'] ?? 'No user ID');
    }

    public function userProfile()
    {
        if (!$this->isGet()) {
            http_response_code(405);
            return;
        }

        $userIdToShow = $_GET['userId'] ?? null;
        if (!$userIdToShow) {
            echo "No userId provided";
            return;
        }

        if (UserRepository::isUserBanned((int)$userIdToShow)) {
            echo "This user is banned!";
            return;
        }

        $user = $this->userRepository->getUserById((int)$userIdToShow);

        if (!$user) {
            echo "User not found";
            return;
        }

        $usersCardsRepo = new UsersCardsRepository();
        $tradeCards = $usersCardsRepo->fetchCardsByType($userIdToShow, 'trade');
        $wishlistCards = $usersCardsRepo->fetchCardsByType($userIdToShow, 'wishlist');

        $this->render('userprofile', [
            'user' => $user,
            'tradeCards' => $tradeCards,
            'wishlist' => $wishlistCards
        ]);
    }
}
