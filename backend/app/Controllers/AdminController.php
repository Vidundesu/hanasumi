<?php
require_once __DIR__ . '/../Services/AdminService.php';

class AdminController{
    private $adminService;

    public function __construct(AdminService $adminService)
    {
        $this->adminService = $adminService;
    }
    public function login()
    {
        header('Content-Type: application/json');

        if ($this->adminService->isLoggedIn()) {
            echo json_encode([
                'status' => 'already_logged_in',
                'admin' => $_SESSION['admin'],
                'session_start' => date('Y-m-d H:i:s', $_SESSION['admin']['login_time'])
            ]);
            return;
        }

        $input = json_decode(file_get_contents('php://input'), true);
        $response = $this->adminService->login($input);

        if (isset($response['error'])) {
            http_response_code(401);
        } 
        echo json_encode($response);
    }
    private function validateAdminSession() {
        try {
            $this->adminService->validateSession();
            return true;
        } catch (Exception $e) {
            http_response_code(401);
            echo json_encode(['error' => $e->getMessage()]);
            return false;
        }
    }
    public function getDashboardStat() {
        header('Content-Type: application/json');
        
        try {
            // Check admin authentication
            if (!$this->validateAdminSession()) {
                http_response_code(401);
                echo json_encode(['error' => 'Unauthorized access']);
                return;
            }

            $stats = $this->adminService->getDashboardStats();
            echo json_encode([
                'status' => 'success',
                'data' => $stats
            ]);
            
        } catch (DatabaseException $e) {
            http_response_code(500);
            echo json_encode([
                'status' => 'error',
                'message' => 'Database error: ' . $e->getMessage()
            ]);
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode([
                'status' => 'error',
                'message' => 'Server error: ' . $e->getMessage()
            ]);
        }
    }
    public function getPendingOrder(){
        header('Content-Type: application/json');
        
        try {
            if (!$this->validateAdminSession()) {
                http_response_code(401);
                echo json_encode(['error' => 'Unauthorized access']);
                return;
            }

            $stats = $this->adminService->getPendingDeliver();
            echo json_encode([
                'status' => 'success',
                'data' => $stats
            ]);
            
        } catch (DatabaseException $e) {
            http_response_code(500);
            echo json_encode([
                'status' => 'error',
                'message' => 'Database error: ' . $e->getMessage()
            ]);
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode([
                'status' => 'error',
                'message' => 'Server error: ' . $e->getMessage()
            ]);
        }
    }
    public function getDelivers(){
        header('Content-Type: application/json');
        
        try {
            if (!$this->validateAdminSession()) {
                http_response_code(401);
                echo json_encode(['error' => 'Unauthorized access']);
                return;
            }

            $stats = $this->adminService->getDelivers();
            echo json_encode([
                'status' => 'success',
                'data' => $stats
            ]);
            
        } catch (DatabaseException $e) {
            http_response_code(500);
            echo json_encode([
                'status' => 'error',
                'message' => 'Database error: ' . $e->getMessage()
            ]);
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode([
                'status' => 'error',
                'message' => 'Server error: ' . $e->getMessage()
            ]);
        }
    }
    public function getProducts(){
        header('Content-Type: application/json');
        
        try {
            if (!$this->validateAdminSession()) {
                http_response_code(401);
                echo json_encode(['error' => 'Unauthorized access']);
                return;
            }

            $stats = $this->adminService->getProducts();
            echo json_encode([
                'status' => 'success',
                'data' => $stats
            ]);
            
        } catch (DatabaseException $e) {
            http_response_code(500);
            echo json_encode([
                'status' => 'error',
                'message' => 'Database error: ' . $e->getMessage()
            ]);
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode([
                'status' => 'error',
                'message' => 'Server error: ' . $e->getMessage()
            ]);
        }
    }
}
?>