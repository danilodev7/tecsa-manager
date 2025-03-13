<?php
require '../models/Task.php';
require '../Database.php';

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");

class TaskController {
    private $db;

    public function __construct() {
        $this->db = new Database();
    }

    public function createTask() {
        $data = json_decode(file_get_contents("php://input"), true);
        $task = new Task();
        $task->title = $data['title'];
        $task->description = $data['description'];
        $task->status = $data['status'];
        $task->created_at = date('Y-m-d H:i:s');
        $task->updated_at = date('Y-m-d H:i:s');
        $this->db->createTask($task);
    }

    public function getTasks() {
        $tasks = $this->db->getTasks();
        echo json_encode($tasks);
    }

    public function getTask($id) {
        $task = $this->db->getTask($id);
        echo json_encode($task);
    }

    public function updateTask($id) {
        $data = json_decode(file_get_contents("php://input"), true);
        $task = new Task();
        $task->id = $id;
        $task->title = $data['title'];
        $task->description = $data['description'];
        $task->status = $data['status'];
        $task->updated_at = date('Y-m-d H:i:s');
        $this->db->updateTask($task);
    }

    public function deleteTask($id) {
        $this->db->deleteTask($id);
    }
}
?>