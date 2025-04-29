<?php
session_start();
header('Content-Type: application/json');
require_once '../db.php';

if (!isset($_SESSION['user_id']) || !isset($_GET['id'])) {
    http_response_code(403);
    echo json_encode(["error" => "Not allowed"]);
    exit;
}

try {
    $pdo = getDB();
    $stmt = $pdo->prepare("DELETE FROM favorites WHERE id = ? AND user_id = ?");
    $stmt->execute([$_GET['id'], $_SESSION['user_id']]);
    echo json_encode(["message" => "Deleted"]);
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(["error" => "Server error"]);
}
