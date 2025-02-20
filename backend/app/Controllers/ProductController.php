<?php
require_once __DIR__ . '/../Services/ProductService.php';

class ProductController {
    private $productService;

    public function __construct(ProductService $productService) {
        $this->productService = $productService;
    }

    public function getFlowerProducts() {
        try {
            $products = $this->productService->getFlowerProducts();
            $this->jsonResponse(['products' => $products]);
        } catch (DatabaseException $e) {
            $this->errorResponse(500, 'Database error: ' . $e->getMessage());
        }
    }
    public function getCakeProducts() {
        try {
            $products = $this->productService->getCakeProducts();
            $this->jsonResponse(['products' => $products]);
        } catch (DatabaseException $e) {
            $this->errorResponse(500, 'Database error: ' . $e->getMessage());
        }
    }
    public function getCustomProducts() {
        try {
            $products = $this->productService->getCustomProducts();
            $this->jsonResponse(['products' => $products]);
        } catch (DatabaseException $e) {
            $this->errorResponse(500, 'Database error: ' . $e->getMessage());
        }
    }

    private function jsonResponse(array $data, int $statusCode = 200) {
        header('Content-Type: application/json');
        http_response_code($statusCode);
        echo json_encode($data);
    }

    private function errorResponse(int $statusCode, string $message) {
        $this->jsonResponse(['error' => $message], $statusCode);
    }
}
?>