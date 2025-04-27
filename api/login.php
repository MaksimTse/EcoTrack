<?php
session_start();
header('Content-Type: application/json');
require_once '../db.php';

$data = json_decode(file_get_contents('php://input'), true);

$email = trim($data['email'] ?? '');
$password = trim($data['password'] ?? '');

if (empty($email) || empty($password)) {
    http_response_code(400);
    echo json_encode(["error" => "Täida kõik väljad!"]);
    exit;
}

try {
    $pdo = getDB();
    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch();

    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];

        echo json_encode(["message" => "Sisse logitud edukalt!", "username" => $user['username']]);
    } else {
        http_response_code(401);
        echo json_encode(["error" => "Vale e-mail või parool."]);
    }
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(["error" => "Serveri viga."]);
}
?>
