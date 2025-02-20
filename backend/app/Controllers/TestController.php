<?php
// app/Controllers/TestController.php
class TestController {
    public function testDb() {
        header("Content-Type: application/json");
        try {
            $db = Database::getInstance();
            $stmt = $db->query("SELECT 1 + 1 AS result");
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            echo json_encode([
                "success" => true,
                "message" => "Database connection works!",
                "result" => $result['result']
            ]);
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode([
                "success" => false,
                "error" => $e->getMessage()
            ]);
        }
    }
}

?>