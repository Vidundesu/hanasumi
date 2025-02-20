<?php
class Router {
    private $routes = [];
    private $container;

    public function __construct(Container $container) {
        $this->container = $container;
    }

    public function add($method, $path, $controllerAction) {
        $this->routes[] = [
            'method' => $method,
            'path' => $path,
            'controllerAction' => $controllerAction
        ];
    }

    public function run() {
        $requestMethod = $_SERVER['REQUEST_METHOD'];
        $requestUri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

        foreach ($this->routes as $route) {
            if ($route['method'] === $requestMethod && $route['path'] === $requestUri) {
                list($controllerClass, $action) = explode('@', $route['controllerAction']);
                
                // Get controller from container
                $controller = $this->container->get($controllerClass);
                if (!$controller) {
                    throw new Exception("Controller $controllerClass not found");
                }
                
                $controller->$action();
                return;
            }
        }

        http_response_code(404);
        echo json_encode(["error" => "Route not found"]);
    }
}
?>