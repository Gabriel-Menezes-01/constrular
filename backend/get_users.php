<?php
include_once 'conexao.php';
session_start();
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

// Debug: Log das tentativas de acesso
error_log("Tentativa de acesso ao get_users.php - Session: " . print_r($_SESSION, true));

// Verificar se o usuário está logado e é admin
if (!isset($_SESSION['email'])) {
    error_log("Erro: Usuário não está logado");
    http_response_code(401);
    echo json_encode(['error' => 'Usuário não autenticado', 'debug' => 'Session não encontrada']);
    exit;
}

if ($_SESSION['email'] !== 'admin@gmail.com') {
    error_log("Erro: Usuário não é admin - Email: " . $_SESSION['email']);
    http_response_code(403);
    echo json_encode(['error' => 'Acesso negado - Apenas administradores', 'debug' => 'Email: ' . $_SESSION['email']]);
    exit;
}

// Incluir conexão
try {
    include 'conexao.php';
    
    if (!$conn) {
        throw new Exception("Erro na conexão com o banco de dados");
    }
    
} catch (Exception $e) {
    error_log("Erro de conexão: " . $e->getMessage());
    http_response_code(500);
    echo json_encode(['error' => 'Erro de conexão com banco de dados', 'debug' => $e->getMessage()]);
    exit;
}

try {
    // Query para buscar usuários
    $query = "SELECT id, nome, email, COALESCE(ativo, 1) as ativo, COALESCE(created_at, NOW()) as created_at FROM usuarios ORDER BY nome ASC";

    error_log("Executando query: " . $query);
    
    $result = mysqli_query($conn, $query);
    
    if (!$result) {
        throw new Exception("Erro na query: " . mysqli_error($conn));
    }
    
    $users = [];
    
    while ($row = mysqli_fetch_assoc($result)) {
        // Garantir que todos os campos necessários existam
        $users[] = [
            'id' => (int)$row['id'],
            'nome' => $row['nome'] ?? 'Nome não informado',
            'email' => $row['email'] ?? 'Email não informado',
            'ativo' => (bool)($row['ativo'] ?? 1),
            'created_at' => $row['created_at'] ?? date('Y-m-d H:i:s')
        ];
    }
    
    error_log("Usuários encontrados: " . count($users));
    
    // Liberar resultado
    mysqli_free_result($result);
    
    // Retornar JSON
    echo json_encode($users, JSON_UNESCAPED_UNICODE);
    
} catch (Exception $e) {
    error_log("Erro ao buscar usuários: " . $e->getMessage());
    http_response_code(500);
    echo json_encode([
        'error' => 'Erro interno do servidor', 
        'debug' => $e->getMessage(),
        'query' => isset($query) ? $query : 'Query não definida'
    ], JSON_UNESCAPED_UNICODE);
} finally {
    // Fechar conexão
    if (isset($conn)) {
        mysqli_close($conn);
    }
}
?>