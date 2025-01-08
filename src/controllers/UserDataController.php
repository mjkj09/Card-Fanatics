<?php

namespace controllers;

use repository\UserRepository;

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
        if(!isset($_SESSION['user_id'])){
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
            'status'   => 'success',
            'data'     => [
                'email'     => $user->getEmail(),
                'name'      => $user->getName(),
                'surname'   => $user->getSurname(),
                'phone'     => $user->getPhone(),
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
        $name      = $_POST['name']      ?? '';
        $surname   = $_POST['surname']   ?? '';
        $phone     = $_POST['phone']     ?? '';
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
}
