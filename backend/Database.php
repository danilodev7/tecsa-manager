<?php
require 'api/db.php';

class Database {
    private $pdo;

    public function __construct() {
        global $pdo;
        $this->pdo = $pdo;
    }

    public function createTask($task) {
        $stmt = $this->pdo->prepare("INSERT INTO tasks (title, description, status, created_at, updated_at) VALUES (?, ?, ?, ?, ?)");
        $stmt->execute([$task->title, $task->description, $task->status, $task->created_at, $task->updated_at]);
    }

    public function getTasks() {
        $stmt = $this->pdo->query("SELECT * FROM tasks");
        return $stmt->fetchAll();
    }

    public function getTask($id) {
        $stmt = $this->pdo->prepare("SELECT * FROM tasks WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    public function updateTask($task) {
        $stmt = $this->pdo->prepare("UPDATE tasks SET title = ?, description = ?, status = ?, updated_at = ? WHERE id = ?");
        $stmt->execute([$task->title, $task->description, $task->status, $task->updated_at, $task->id]);
    }

    public function deleteTask($id) {
        $stmt = $this->pdo->prepare("DELETE FROM tasks WHERE id = ?");
        $stmt->execute([$id]);
    }
}
?>