<?php
// filepath: c:\wamp64\www\constrular\backend\delete_user.php
session_start();
header('Content-Type: application/json');

if (!isset($_SESSION['email']) || $_SESSION['email'] !== 'admin@gmail.com') {
    http_response_code(403);
    echo json_encode(['error' => 'Acesso negado']);
    exit;
}

include 'conexao.php';

$input = json_decode(file_get_contents('php://input'), true);
$userId = $input['userId'] ?? null;

if (!$userId) {
    http_response_code(400);
    echo json_encode(['error' => 'ID do usuário é obrigatório']);
    exit;
}

try {
    // Não permitir deletar admin
    $checkStmt = $conn->prepare("SELECT email FROM usuarios WHERE id = ?");
    $checkStmt->bind_param("i", $userId);
    $checkStmt->execute();
    $result = $checkStmt->get_result();
    $user = $result->fetch_assoc();
    
    if ($user['email'] === 'admin@gmail.com') {
        http_response_code(400);
        echo json_encode(['error' => 'Não é possível deletar o administrador']);
        exit;
    }
    
    $stmt = $conn->prepare("DELETE FROM usuarios WHERE id = ?");
    $stmt->bind_param("i", $userId);
    $stmt->execute();
    
    echo json_encode(['success' => true]);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Erro interno']);
}
?>