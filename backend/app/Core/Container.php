<?php
include __DIR__ . '/Database.php';
include __DIR__ . '/../Services/ProductService.php';
include __DIR__ . '/../Controllers/ProductController.php';
include __DIR__ . '/../Services/UserService.php';
include __DIR__ . '/../Controllers/UserController.php';
include __DIR__ . '/../Services/OrderService.php';
include __DIR__ . '/../Controllers/OrderController.php';
include __DIR__ . '/../Services/AdminService.php';
include __DIR__ . '/../Controllers/AdminController.php';
class Container {
    private $instances = [];

    public function __construct() {
        // Register Database
        $this->instances[PDO::class] = Database::getInstance();

        // Register Services
        $this->instances[ProductService::class] = new ProductService(
            $this->instances[PDO::class]
        );
        $this->instances[UserService::class] = new UserService(
            $this->instances[PDO::class]
        );
        $this->instances[OrderService::class] = new OrderService(
            $this->instances[PDO::class]
        );
        $this->instances[AdminService::class] = new AdminService(
            $this->instances[PDO::class]
        );

        // Register Controllers
        $this->instances[ProductController::class] = new ProductController(
            $this->instances[ProductService::class]
        );
        $this->instances[UserController::class] = new UserController(
            $this->instances[UserService::class]
        );
        $this->instances[OrderController::class] = new OrderController(
            $this->instances[OrderService::class]
        );
        $this->instances[AdminController::class] = new AdminController(
            $this->instances[AdminService::class]
        );
        

    }

    public function get($class) {
        return $this->instances[$class] ?? null;
    }
}
?>