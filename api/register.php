<?php
header('Content-Type: application/json');
require_once '../db.php';

$data = json_decode(file_get_contents('php://input'), true);

$username = trim($data['username'] ?? '');
$email = trim($data['email'] ?? '');
$password = trim($data['password'] ?? '');

if (empty($username) || empty($email) || empty($password)) {
    http_response_code(400);
    echo json_encode(["error" => "Täida kõik väljad!"]);
    exit;
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    http_response_code(400);
    echo json_encode(["error" => "E-mail ei ole kehtiv!"]);
    exit;
}

$hashedPassword = password_hash($password, PASSWORD_DEFAULT);

try {
    $pdo = getDB();
    $stmt = $pdo->prepare("INSERT INTO users (username, email, password) VALUES (?, ?, ?)");
    $stmt->execute([$username, $email, $hashedPassword]);
    echo json_encode(["message" => "Konto loodud edukalt!"]);
} catch (PDOException $e) {
    if ($e->getCode() === '23000') {
        http_response_code(409);
        echo json_encode(["error" => "See e-mail on juba registreeritud!"]);
    } else {
        http_response_code(500);
        echo json_encode(["error" => "Serveri viga."]);
    }
}
?>
