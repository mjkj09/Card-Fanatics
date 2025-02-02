<?php

namespace controllers;

use repository\UserRepository;

require_once 'AppController.php';
require_once __DIR__ . '/../repository/UserRepository.php';
require_once __DIR__ . '/../repository/CardRepository.php';
require_once __DIR__ . '/../repository/UsersCardsRepository.php';

class AdminController extends AppController
{
    public function admindashboard()
    {
        session_start();
        if (!isset($_SESSION['user_id'])) {
            header("Location: /");
            exit;
        }
        $userId = $_SESSION['user_id'];

        if (!UserRepository::isUserAdmin($userId)) {
            http_response_code(403);
            die('Access forbidden: Admins only!');
        }

        $userRepo = new UserRepository();
        $allUsers = $userRepo->getAllUsersForAdmin();

        $this->render('admindashboard', [
            'allUsers' => $allUsers
        ]);
    }

    public function banUser()
    {
        session_start();
        if (!isset($_SESSION['user_id'])) {
            http_response_code(401);
            echo json_encode(['status' => 'error', 'message' => 'Not logged in']);
            return;
        }
        $userId = $_SESSION['user_id'];

        if (!UserRepository::isUserAdmin($userId)) {
            http_response_code(403);
            echo json_encode(['status' => 'error', 'message' => 'Admin only']);
            return;
        }

        if (!$this->isPost()) {
            http_response_code(405);
            echo json_encode(['status' => 'error', 'message' => 'Method not allowed']);
            return;
        }

        $banUserId = $_POST['banUserId'] ?? null;
        $banReason = $_POST['banReason'] ?? '';

        if (!$banUserId) {
            echo json_encode(['status' => 'error', 'message' => 'Missing user id']);
            return;
        }

        $userRepo = new UserRepository();
        $result = $userRepo->banUser((int)$banUserId, $banReason);

        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($result);
    }
}
