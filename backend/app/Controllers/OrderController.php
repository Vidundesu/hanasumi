<?php
require_once __DIR__ . '/../Services/OrderService.php';
class OrderController
{
    private $orderService;

    public function __construct(OrderService $orderService)
    {
        $this->orderService = $orderService;
    }

    private function handleResponse($response)
    {
        header('Content-Type: application/json');
        if (isset($response['error'])) {
            http_response_code(400);
        }
        echo json_encode($response);
    }


    public function placeOrder()
    {
        try {
            $input = json_decode(file_get_contents('php://input'), true);
            
            // Debugging: Log input
            if (!$input) {
                throw new Exception("Invalid JSON input.");
            }

            $response = $this->orderService->placeOrder($input);
            $this->handleResponse($response);
        } catch (Exception $e) {
            $this->handleResponse(['error' => $e->getMessage()]);
        }
    }
}
