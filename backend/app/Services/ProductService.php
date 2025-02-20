<?php
class ProductService{
    private $pdo;
        
    public function __construct(PDO $pdo) {
        $this->pdo = $pdo;
    }
    public function getFlowerProducts(): array{
        try {
            $stmt = $this->pdo->prepare("
                SELECT id, name, price, description, image_url as image, additional_info
                FROM products
                WHERE product_type = 'flower'
            ");
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            throw new DatabaseException("Failed to fetch flower products: " . $e->getMessage());
        }
    }
    public function getCakeProducts(): array{
        try {
            $stmt = $this->pdo->prepare("
                SELECT id, name, price, description, image_url as image, additional_info
                FROM products
                WHERE product_type = 'cake'
            ");
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            throw new DatabaseException("Failed to fetch flower products: " . $e->getMessage());
        }
    }
    public function getCustomProducts(): array{
        try {
            $stmt = $this->pdo->prepare("
                SELECT id, name, price, description, image_url as image, additional_info
                FROM products
                WHERE product_type = 'custom_item'
            ");
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            throw new DatabaseException("Failed to fetch flower products: " . $e->getMessage());
        }
    }
}
class DatabaseException extends Exception {}
?>