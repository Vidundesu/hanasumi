<?php
class AdminService
{
    private $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
        $this->initSession();
    }
    public function validateSession()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (!isset($_SESSION['admin']['id'])) {
            throw new Exception("admin not logged in");
        }
    }
    public function isLoggedIn()
    {
        return isset($_SESSION['admin']);
    }

    private function initSession()
    {
        if (session_status() === PHP_SESSION_NONE) {
            // Only configure session parameters if session isn't active
            session_set_cookie_params([
                'lifetime' => 86400 * 30,
                'path' => '/',
                'domain' => '.localhost',
                'secure' => true,
                'samesite' => 'None',
                'httponly' => true
            ]);
            session_start();
        }
    }
    public function getPendingDeliver()
    {
        try {
            $stmt = $this->pdo->prepare("
                SELECT orders.*, customers.fname FROM 
                orders INNER JOIN customers ON orders.customer_id = customers.id WHERE 
                orders.deliverStatus = 'Pending' AND orders.isActive = 1 
                ORDER BY orders.date DESC;

            ");
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            throw new DatabaseException("Failed to fetch pending orders " . $e->getMessage());
        }
    }
    public function getDelivers()
    {
        try {
            $stmt = $this->pdo->prepare("
                SELECT fname,lname, city, username from delivery_men
            ");
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            throw new DatabaseException("Failed to fetch delivers " . $e->getMessage());
        }
    }
    public function getProducts()
    {
        try {
            $stmt = $this->pdo->prepare("
                SELECT * from products
            ");
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            throw new DatabaseException("Failed to fetch delivers " . $e->getMessage());
        }
    }
    public function getDashboardStats()
    {
        try {
            // Total Active Orders
            $totalOrders = $this->pdo->query(
                "SELECT COUNT(*) FROM orders WHERE isActive = 1"
            )->fetchColumn();

            // Current Month Income
            $currentMonth = $this->pdo->query(
                "SELECT SUM(total) FROM orders 
                    WHERE MONTH(date) = MONTH(CURRENT_DATE()) 
                    AND YEAR(date) = YEAR(CURRENT_DATE())
                    AND isActive = 1"
            )->fetchColumn();

            // Previous Month Income
            $lastMonth = $this->pdo->query(
                "SELECT SUM(total) FROM orders 
                    WHERE MONTH(date) = MONTH(CURRENT_DATE() - INTERVAL 1 MONTH) 
                    AND YEAR(date) = YEAR(CURRENT_DATE() - INTERVAL 1 MONTH)
                    AND isActive = 1"
            )->fetchColumn();

            // Percentage calculation
            $percentageChange = 0;
            if ($lastMonth > 0) {
                $percentageChange = (($currentMonth - $lastMonth) / $lastMonth) * 100;
            }

            // Monthly Sales Data
            $monthlySales = $this->pdo->query(
                "SELECT 
                        DATE_FORMAT(date, '%Y-%m') AS month,
                        COUNT(*) AS order_count 
                    FROM orders 
                    WHERE isActive = 1
                    GROUP BY DATE_FORMAT(date, '%Y-%m')
                    ORDER BY month DESC
                    LIMIT 12"
            )->fetchAll(PDO::FETCH_ASSOC);

            return [
                'total_orders' => $totalOrders,
                'current_month_income' => $currentMonth,
                'percentage_change' => $percentageChange,
                'monthly_sales' => $monthlySales
            ];
        } catch (PDOException $e) {
            throw new Exception("Database error: " . $e->getMessage());
        }
    }
    public function login($input)
    {
        if (empty($input['username']) || empty($input['password'])) {
            return ['error' => 'Username and password required'];
        }

        $user = $this->authenticate($input['username'], $input['password']);

        if ($user) {
            $this->createAdminSession($user);
            return [
                'status' => 'success',
                'admin' => $_SESSION['admin'],
                'session_start' => date('Y-m-d H:i:s', $_SESSION['admin']['login_time'])
            ];
        }

        return ['error' => 'Invalid credentials'];
    }

    private function authenticate($username, $password)
    {

        $stmt = $this->pdo->prepare("SELECT id,username, pw FROM admin WHERE username = ?");
        $stmt->execute([$username]);

        if ($stmt->rowCount() > 0) {
            $user = $stmt->fetch();
            if (password_verify($password, $user['pw'])) {
                return [
                    'id' => $user['id'],
                    'username' => $user['username'],
                ];
            }
        }
        return false;
    }

    private function createAdminSession($user)
    {
        // Only regenerate ID if session is active
        if (session_status() === PHP_SESSION_ACTIVE) {
            session_regenerate_id(true);
        }

        $_SESSION['admin'] = [
            'id' => $user['id'],
            'username' => $user['username'],
            'login_time' => time()
        ];
    }
}
