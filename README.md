# 🌸 Floral Shop E-Commerce Web App

## 📌 Overview

Floral Shop is an e-commerce web application for ordering flowers online. The application follows a **4-Tier Architecture** with a **Service-Oriented** design. It utilizes **Tailwind CSS** for frontend styling, **PHP** for backend development, and **REST APIs** for communication between layers.

---

## 🏗 Architecture

The application is built using a **4-Tier Architecture**:

1. **Presentation Layer (Frontend)** - UI built with Tailwind CSS.
2. **Application Layer** - Handles request routing and business logic.
3. **Service Layer** - Contains reusable service logic.
4. **Data Layer (API)** - Handles database interactions via REST APIs.

---

## 📂 File Structure

FloralShop/ 


│── API/ 

├─── index.php  

│── APP/ 

├─── Configs/ 

├─── Core/

├─── Services/  

├─── Controllers/


## ⚙️Custom Router Config
```php
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
```
---

##🔨 Technologies Used

-Tailwind CSS
-PHP, REST APIs
-MYSQL
-XAMP

---

##📃License

© 2025 | Vidundesu. All rights reserved.


