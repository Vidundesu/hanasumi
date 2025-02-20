<?php
class UserService {
    private $pdo;

    public function __construct(PDO $pdo) {
        $this->pdo = $pdo;
        $this->initSession();
    }
    private function validateSession()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (!isset($_SESSION['user']['id'])) {
            throw new Exception("User not logged in");
        }
    }
    private function initSession() {
        // Configure session settings
        session_set_cookie_params([
            'lifetime' => 86400 * 30, // 30 days
            'path' => '/',
            'domain' => '.localhost',
            'secure' => true,  // Set to false for HTTP requests (non-HTTPS)
            'samesite' => 'None', // Prevent CSRF attacks
            'httponly' => true  // Make cookie accessible only via HTTP
        ]);
    
        session_start();
    }
    public function registerUser($input) {
        try {
            if (empty($input['fname']) || empty($input['lname']) || empty($input['email']) || empty($input['username']) || empty($input['password'])) {
                throw new Exception('All fields are required');
            }

            $stmt = $this->pdo->prepare("SELECT id FROM customers WHERE email = ? OR username = ?");
            $stmt->execute([$input['email'], $input['username']]);
            if ($stmt->rowCount() > 0) {
                throw new Exception('Email or username already exists');
            }

            $hashedPassword = password_hash($input['password'], PASSWORD_DEFAULT);
            $stmt = $this->pdo->prepare("INSERT INTO customers (fname, lname, email, username, pw) VALUES (?, ?, ?, ?, ?)");
            $stmt->execute([$input['fname'], $input['lname'], $input['email'], $input['username'], $hashedPassword]);

            return ['status' => 'success', 'message' => 'User registered successfully'];
        } catch (Exception $e) {
            return ['error' => $e->getMessage()];
        }
    }

    public function login($input) {
        if (empty($input['username']) || empty($input['password'])) {
            return ['error' => 'Username and password required'];
        }
    
        $user = $this->authenticate($input['username'], $input['password']);
    
        if ($user) {
            $this->createUserSession($user);
            $userId = $_SESSION['user']['id'];
            $stmt = $this->pdo->prepare("
                UPDATE customers 
                SET isActive = 1 
                WHERE id = :id
            ");
            $stmt->execute([':id' =>$userId ]);
            return [
                'status' => 'success',
                'user' => $_SESSION['user'],
                'session_start' => date('Y-m-d H:i:s', $_SESSION['user']['login_time'])
            ];
        }
        
        return ['error' => 'Invalid credentials'];
    }

    private function authenticate($username, $password) {
        $tables = ['customers' => 'customer', 'delivery_men' => 'delivery_man'];

        foreach ($tables as $table => $type) {
            $stmt = $this->pdo->prepare("SELECT id, fname, lname, username, pw FROM $table WHERE username = ?");
            $stmt->execute([$username]);

            if ($stmt->rowCount() > 0) {
                $user = $stmt->fetch();
                if (password_verify($password, $user['pw'])) {
                    return [
                        'id' => $user['id'],
                        'fname' => $user['fname'],
                        'lname' => $user['lname'],
                        'username' => $user['username'],
                        'type' => 'customer'
                    ];
                }
            }
        }
        return false;
    }

    private function createUserSession($user) {
        $_SESSION['user'] = [
            'id' => $user['id'],
            'username' => $user['username'],
            'fname' => $user['fname'],
            'lname' => $user['lname'],
            'user_type' => $user['type'],
            'login_time' => time()
        ];

        // Regenerate session ID to prevent session fixation
        session_regenerate_id(true);

        // Set a secure cookie with the session ID
        setcookie(
            session_name(),
            session_id(),
            [
                'expires' => time() + 86400 * 30, // 30 days
                'path' => '/',
                'domain' => $_SERVER['HTTP_HOST'],
                'secure' => true,
                'httponly' => true,
                'samesite' => 'Strict'
            ]
        );
    }

    public function isLoggedIn() {
        return isset($_SESSION['user']);
    }

    public function checkSession() {
        if (isset($_SESSION['user'])) {
            return ['status' => 'active', 'user' => $_SESSION['user']];
        }
        return ['status' => 'no_session'];
    }

    public function logout() {
        // Clear session data
        $_SESSION = [];
        session_unset();

        // Destroy the session
        session_destroy();

        // Clear the session cookie
        if (ini_get("session.use_cookies")) {
            $params = session_get_cookie_params();
            setcookie(
                session_name(),
                '',
                time() - 42000,
                $params["path"],
                $params["domain"],
                $params["secure"],
                $params["httponly"]
            );
        }

        return ['status' => 'logged_out'];
    }
    public function getCustomerDetails() {
        try {
            $this->validateSession();
            $userId = $_SESSION['user']['id'];
    
            // Get customer info
            $stmt = $this->pdo->prepare("
                SELECT id, fname, lname, email
                FROM customers 
                WHERE id = :id AND isActive = 1
            ");
            $stmt->execute([':id' => $userId]);
            $customer = $stmt->fetch(PDO::FETCH_ASSOC);
    
            if (!$customer) {
                throw new Exception('User not found', 404);
            }
    
            // Get orders
            $orders = $this->getCustomerOrders($userId);
    
            return [
                'user' => $customer,
                'orders' => $orders
            ];
            
        } catch (PDOException $e) {
            throw new Exception('Database error: ' . $e->getMessage());
        } catch (Exception $e) {
            throw $e;
        }
    }
    
    public function updatePassword($currentPassword, $newPassword) {
        try {
            $this->validateSession();
            $userId = $_SESSION['user']['id'];
    
            // Verify current password
            $stmt = $this->pdo->prepare("
                SELECT pw FROM customers 
                WHERE id = :id AND isActive = 1
            ");
            $stmt->execute([':id' => $userId]);
            $user = $stmt->fetch();
    
            if (!$user || !password_verify($currentPassword, $user['pw'])) {
                throw new Exception('Invalid current password', 401);
            }
    
            // Update password
            $newHash = password_hash($newPassword, PASSWORD_DEFAULT);
            $updateStmt = $this->pdo->prepare("
                UPDATE customers 
                SET pw = :password 
                WHERE id = :id
            ");
            $updateStmt->execute([
                ':password' => $newHash,
                ':id' => $userId
            ]);
    
            if ($updateStmt->rowCount() === 0) {
                throw new Exception('Password update failed', 500);
            }
    
            return ['status' => 'success'];
            
        } catch (PDOException $e) {
            throw new Exception('Database error: ' . $e->getMessage());
        }
    }
    
    public function deleteAccount() {
        try {
            $this->validateSession();
            $userId = $_SESSION['user']['id'];
    
            $stmt = $this->pdo->prepare("
                UPDATE customers 
                SET isActive = 0 
                WHERE id = :id
            ");
            $stmt->execute([':id' => $userId]);
    
            if ($stmt->rowCount() === 0) {
                throw new Exception('Account deletion failed', 500);
            }
    
            return ['status' => 'success'];
            
        } catch (PDOException $e) {
            throw new Exception('Database error: ' . $e->getMessage());
        }
    }
    
    private function getCustomerOrders($userId) {
        try {
            $stmt = $this->pdo->prepare("
                SELECT id, total, deliverStatus, date, time, order_items
                FROM orders 
                WHERE customer_id = :id
            ");
            $stmt->execute([':id' => $userId]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
            
        } catch (PDOException $e) {
            throw new Exception('Failed to fetch orders: ' . $e->getMessage());
        }
    }
}
?>
