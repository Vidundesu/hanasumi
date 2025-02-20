<?php
require_once __DIR__ . '/../Config/config.php';
require_once __DIR__ . '/../Services/UserService.php';

class UserController
{
    private $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    public function registerUser()
    {
        header('Content-Type: application/json');
        $input = json_decode(file_get_contents('php://input'), true);
        $response = $this->userService->registerUser($input);

        if (isset($response['error'])) {
            http_response_code(400);
        }

        echo json_encode($response);
    }
    public function validateSession()
    {
        session_start();
        if (!isset($_SESSION['user_id'])) {
            throw new Exception("User not logged in");
        }
    }
    public function login()
    {
        session_start();
        header('Content-Type: application/json');

        if ($this->userService->isLoggedIn()) {
            echo json_encode([
                'status' => 'already_logged_in',
                'user' => $_SESSION['user'],
                'session_start' => date('Y-m-d H:i:s', $_SESSION['user']['login_time'])
            ]);
            return;
        }

        $input = json_decode(file_get_contents('php://input'), true);
        $response = $this->userService->login($input);

        if (isset($response['error'])) {
            http_response_code(401);
        } else {
            // Redirect back to the cart page after successful login
            $response['redirect'] = '/frontend/dist/cart.html'; // Add redirect URL to response
        }

        echo json_encode($response);
    }

    public function checkAuth()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        header('Content-Type: application/json');

        // Debug: Log session data
        error_log("Session ID: " . session_id());
        error_log("Session Data: " . print_r($_SESSION, true));

        if (!isset($_SESSION['user'])) {
            error_log("No user in session");
            http_response_code(401);
            echo json_encode(['status' => 'no_session']);
            exit;
        }

        echo json_encode(['status' => 'active', 'user' => $_SESSION['user']]);
        exit;
    }
    public function logout()
    {
        session_start();
        header('Content-Type: application/json');

        echo json_encode($this->userService->logout());
    }
    public function getCustomer()
    {
        header('Content-Type: application/json');
        try {
            $response = $this->userService->getCustomerDetails();
            echo json_encode($response);
        } catch (Exception $e) {
            http_response_code($e->getCode() ?: 500);
            echo json_encode(['error' => $e->getMessage()]);
        }
    }

    public function updatePassword()
    {
        header('Content-Type: application/json');
        $input = json_decode(file_get_contents('php://input'), true);

        try {
            if (!isset($input['currentPassword']) || !isset($input['newPassword'])) {
                throw new Exception('Missing required fields', 400);
            }

            $response = $this->userService->updatePassword(
                $input['currentPassword'],
                $input['newPassword']
            );
            echo json_encode($response);
        } catch (Exception $e) {
            http_response_code($e->getCode() ?: 500);
            echo json_encode(['error' => $e->getMessage()]);
        }
    }

    public function deleteAccount()
    {
        header('Content-Type: application/json');
        try {
            $response = $this->userService->deleteAccount();
            session_unset();
            session_destroy();
            echo json_encode($response);
        } catch (Exception $e) {
            http_response_code($e->getCode() ?: 500);
            echo json_encode(['error' => $e->getMessage()]);
        }
    }
}
