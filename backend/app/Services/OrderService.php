<?php
class OrderService
{
    private $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
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

    private function reformatCartData($frontendCart)
    {
        $backendCart = [
            "cart" => [],
            "discount" => 0
        ];

        // Loop through all categories in the cart
        foreach ($frontendCart as $category => $categoryData) {
            if (isset($categoryData['items'])) {
                foreach ($categoryData['items'] as $item) {
                    $backendCart['cart'][] = [
                        "product_id" => (int)$item['product_id'],
                        "name" => $item['name'],
                        "price" => (float)$item['price'],
                        "quantity" => (int)$item['quantity'],
                        "category" => $category // Include category if needed
                    ];
                }
            }
        }

        return $backendCart;
    }

    private function calculateTotal($cartData)
    {
        $total = 0;
        foreach ($cartData as $item) {
            // Make sure both price and quantity are set and are numbers
            if (isset($item['price'], $item['quantity'])) {
                $total += $item['price'] * $item['quantity'];
            }
        }
        return $total;
    }

    public function placeOrder($input)
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        try {
            $this->validateSession();

            // Transform the frontend cart to backend format
            $reformattedCart = $this->reformatCartData($input['cart']);

            if (!isset($reformattedCart['cart']) || empty($reformattedCart['cart'])) {
                throw new Exception("Cart is missing or empty.");
            }

            $subtotal = $this->calculateTotal($reformattedCart['cart']);
            $discount = $reformattedCart['discount'] ?? 0;
            $total = $subtotal - $discount;

            // Ensure user session is set
            if (!isset($_SESSION['user']['id'])) {
                throw new Exception("User session is missing.");
            }

            $customer_id = $_SESSION['user']['id'];

             // Validate and encode address as JSON
        if (!isset($input['address']) || empty($input['address'])) {
            throw new Exception("Address is required.");
        }
        $addressJson = json_encode($input['address']);

        $stmt = $this->pdo->prepare("
            INSERT INTO orders 
            (customer_id, discount, total, order_items, date, time, isPaid, address) 
            VALUES (:customer_id, :discount, :total, :order_items, :date, :time, :isPaid, :address)
        ");

        $stmt->execute([
            ':customer_id' => $customer_id,
            ':discount' => $discount,
            ':total' => $total,
            ':order_items' => json_encode($reformattedCart['cart']),
            ':date' => date('Y-m-d'),
            ':time' => date('H:i:s'),
            ':isPaid' => 1, // Setting isPaid to 1 (Paid)
            ':address' => $addressJson
        ]);

            return ['status' => 'success', 'order_id' => $this->pdo->lastInsertId()];
        } catch (Exception $e) {
            return ['error' => $e->getMessage()];
        }
    }
    
}
