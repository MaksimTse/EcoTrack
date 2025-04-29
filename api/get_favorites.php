<?php
session_start();
header('Content-Type: application/json');
require_once '../db.php';

if (!isset($_SESSION['user_id'])) {
    echo json_encode([]);
    exit;
}

try {
    $pdo = getDB();
    $stmt = $pdo->prepare("SELECT * FROM favorites WHERE user_id = ?");
    $stmt->execute([$_SESSION['user_id']]);
    $favorites = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode($favorites);
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(["error" => "Serveri viga"]);
}
