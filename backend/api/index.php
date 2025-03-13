<?php
require 'db.php';

header("Content-Type: application/json");

$method = $_SERVER['REQUEST_METHOD'];
$uri = explode('/', trim(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH), '/'));

error_log("Request URI: " . $_SERVER['REQUEST_URI']);
error_log("Parsed URI: " . json_encode($uri));
error_log("Method: " . $method);
error_log("URI Segments: " . json_encode($uri));

if (count($uri) >= 3 && $uri[1] === 'api' && $uri[2] === 'tasks') {
    switch ($method) {
        case 'POST':
            $data = json_decode(file_get_contents('php://input'), true);
            $stmt = $pdo->prepare("INSERT INTO tasks (title, description, status, created_at, updated_at) VALUES (?, ?, ?, NOW(), NOW())");
            $stmt->execute([$data['title'], $data['description'], $data['status']]);
            echo json_encode(['id' => $pdo->lastInsertId()]);
            break;
        case 'GET':
            $stmt = $pdo->query("SELECT * FROM tasks");
            echo json_encode($stmt->fetchAll());
            break;
        case 'PUT':
            if (isset($uri[2])) {
                $data = json_decode(file_get_contents('php://input'), true);
                $stmt = $pdo->prepare("UPDATE tasks SET title = ?, description = ?, status = ?, updated_at = NOW() WHERE id = ?");
                $stmt->execute([$data['title'], $data['description'], $data['status'], $uri[2]]);
                echo json_encode(['status' => 'success']);
            }
            break;
        case 'DELETE':
            if (isset($uri[2])) {
                $stmt = $pdo->prepare("DELETE FROM tasks WHERE id = ?");
                $stmt->execute([$uri[2]]);
                echo json_encode(['status' => 'success']);
            }
            break;
        default:
            http_response_code(405);
            echo json_encode(['error' => 'Method Not Allowed']);
            break;
    }
} else {
    http_response_code(404);
    echo json_encode(['error' => 'Not Found']);
}
?>