<?php
session_start();
header('Content-Type: application/json');
require_once '../db.php';

if (!isset($_SESSION['user_id'])) {
    http_response_code(401);
    echo json_encode(["error" => "Pole sisse logitud."]);
    exit;
}

$data = json_decode(file_get_contents('php://input'), true);

$source = trim($data['source'] ?? 'AQI');
$location = trim($data['location'] ?? '');
$lat = $data['lat'] ?? null;
$lon = $data['lon'] ?? null;

if (empty($location) || !is_numeric($lat) || !is_numeric($lon)) {
    http_response_code(400);
    echo json_encode(["error" => "Vigased andmed."]);
    exit;
}

try {
    $pdo = getDB();

    $stmt = $pdo->prepare("SELECT id FROM favorites WHERE user_id = ? AND source = ? AND lat = ? AND lon = ?");
    $stmt->execute([$_SESSION['user_id'], $source, $lat, $lon]);

    if ($stmt->fetch()) {
        http_response_code(409);
        echo json_encode(["error" => "See asukoht on juba lisatud lemmikutesse."]);
        exit;
    }

    $stmt = $pdo->prepare("INSERT INTO favorites (user_id, source, location, lat, lon) VALUES (?, ?, ?, ?, ?)");
    $stmt->execute([$_SESSION['user_id'], $source, $location, $lat, $lon]);

    echo json_encode(["message" => "Asukoht lisatud lemmikutesse!"]);
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(["error" => "Serveri viga."]);
}
?>
