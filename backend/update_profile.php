<?php
// filepath: c:\wamp64\www\constrular\backend\update_profile.php
session_start();
header('Content-Type: application/json');

include 'conexao.php';

// Verificar se usuário está logado
if (!isset($_SESSION['email'])) {
    echo json_encode(['success' => false, 'message' => 'Usuário não autenticado']);
    exit;
}

$email_session = $_SESSION['email'];
$response = ['success' => false, 'message' => ''];

try {
    // Verificar tipo de operação
    $operation = $_POST['operation'] ?? '';
    
    switch ($operation) {
        case 'personal':
            updatePersonalInfo($conn, $email_session);
            break;
            
        case 'security':
            updateSecurity($conn, $email_session);
            break;
            
        case 'photo':
            updateProfilePhoto($conn, $email_session);
            break;
            
        default:
            throw new Exception('Operação não especificada');
    }
    
} catch (Exception $e) {
    $response['message'] = $e->getMessage();
    echo json_encode($response);
}

function updatePersonalInfo($conn, $email_session) {
    global $response;
    
    // Validar dados recebidos
    $nome = trim($_POST['name'] ?? '');
    $apelido = trim($_POST['apelido'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $telefone = trim($_POST['phone'] ?? '');
    $nascimento = $_POST['birthdate'] ?? null;
    $endereco = trim($_POST['address'] ?? '');
    
    // Validações básicas
    if (empty($nome)) {
        throw new Exception('Nome é obrigatório');
    }
    
    if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        throw new Exception('E-mail válido é obrigatório');
    }
    
    // Verificar se o novo email já existe (se for diferente do atual)
    if ($email !== $email_session) {
        $checkQuery = "SELECT id FROM usuarios WHERE email = ? AND email != ?";
        $checkStmt = $conn->prepare($checkQuery);
        $checkStmt->bind_param("ss", $email, $email_session);
        $checkStmt->execute();
        
        if ($checkStmt->get_result()->num_rows > 0) {
            throw new Exception('Este e-mail já está em uso');
        }
    }
    
    // Atualizar dados
    $query = "UPDATE usuarios SET nome = ?, apelido = ?, email = ?, telefone = ?, data_nascimento = ?, endereco = ? WHERE email = ?";
    
    $stmt = $conn->prepare($query);
    $stmt->bind_param("sssssss", $nome, $apelido, $email, $telefone, $nascimento, $endereco, $email_session);
    
    if ($stmt->execute()) {
        // Atualizar sessão se o email mudou
        if ($email !== $email_session) {
            $_SESSION['email'] = $email;
        }
        
        $response['success'] = true;
        $response['message'] = 'Informações pessoais atualizadas com sucesso!';
        $response['data'] = [
            'nome' => $nome,
            'email' => $email,
            'apelido' => $apelido
        ];
    } else {
        throw new Exception('Erro ao atualizar informações: ' . $conn->error);
    }
    
    echo json_encode($response);
}

function updateSecurity($conn, $email_session) {
    global $response;
    
    $senhaAtual = $_POST['currentPassword'] ?? '';
    $novaSenha = $_POST['newPassword'] ?? '';
    $confirmarSenha = $_POST['confirmPassword'] ?? '';
    
    // Validações
    if (empty($senhaAtual) || empty($novaSenha) || empty($confirmarSenha)) {
        throw new Exception('Todos os campos de senha são obrigatórios');
    }
    
    if ($novaSenha !== $confirmarSenha) {
        throw new Exception('Nova senha e confirmação não coincidem');
    }
    
    if (strlen($novaSenha) < 6) {
        throw new Exception('Nova senha deve ter pelo menos 6 caracteres');
    }
    
    // Verificar senha atual
    $checkQuery = "SELECT senha FROM usuarios WHERE email = ?";
    $checkStmt = $conn->prepare($checkQuery);
    $checkStmt->bind_param("s", $email_session);
    $checkStmt->execute();
    $result = $checkStmt->get_result();
    
    if ($result->num_rows === 0) {
        throw new Exception('Usuário não encontrado');
    }
    
    $user = $result->fetch_assoc();
    
    // Verificar se a senha atual está correta
    if (!password_verify($senhaAtual, $user['senha'])) {
        throw new Exception('Senha atual incorreta');
    }
    
    // Hash da nova senha
    $novaSenhaHash = password_hash($novaSenha, PASSWORD_DEFAULT);
    
    // Atualizar senha
    $updateQuery = "UPDATE usuarios SET senha = ?  WHERE email = ?";
    $updateStmt = $conn->prepare($updateQuery);
    $updateStmt->bind_param("ss", $novaSenhaHash, $email_session);
    
    if ($updateStmt->execute()) {
        $response['success'] = true;
        $response['message'] = 'Senha alterada com sucesso!';
    } else {
        throw new Exception('Erro ao atualizar senha: ' . $conn->error);
    }
    
    echo json_encode($response);
}

function updateProfilePhoto($conn, $email_session) {
    global $response;
    
    if (!isset($_FILES['perfil_foto'])) {
        throw new Exception('Nenhuma foto foi enviada');
    }

    $file = $_FILES['perfil_foto'];

    // Validações do arquivo
    $allowedTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif'];
    $maxSize = 5 * 1024 * 1024; // 5MB
    
    if ($file['error'] !== UPLOAD_ERR_OK) {
        throw new Exception('Erro no upload da foto');
    }
    
    if (!in_array($file['type'], $allowedTypes)) {
        throw new Exception('Tipo de arquivo não permitido. Use JPG, PNG ou GIF');
    }
    
    if ($file['size'] > $maxSize) {
        throw new Exception('Arquivo muito grande. Máximo 5MB');
    }
    
    // Criar diretório se não existir - CORRIGIR NOME DA PASTA
    $uploadDir = '../uploads/perfis/';
    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0755, true);
    }
    
    // Gerar nome único para o arquivo
    $extension = pathinfo($file['name'], PATHINFO_EXTENSION);
    $sanitized_email = str_replace(['@', '.', '+', '-'], '_', $email_session);
    $timestamp = time();
    $random = mt_rand(1000, 9999);
    $filename = 'perfil_' . $sanitized_email . '_' . $timestamp . '_' . $random . '.' . $extension;
    $filepath = $uploadDir . $filename;
    
    // Mover arquivo
    if (move_uploaded_file($file['tmp_name'], $filepath)) {
        // Buscar foto atual para deletar
        $currentQuery = "SELECT perfil_foto FROM usuarios WHERE email = ?";
        $currentStmt = $conn->prepare($currentQuery);
        $currentStmt->bind_param("s", $email_session);
        $currentStmt->execute();
        $currentResult = $currentStmt->get_result();
        
        if ($currentResult->num_rows > 0) {
            $currentUser = $currentResult->fetch_assoc();
            $currentPhoto = $currentUser['perfil_foto'];
            
            // Deletar foto anterior se existir - CONSTRUIR CAMINHO COMPLETO
            if (!empty($currentPhoto) && file_exists($uploadDir . $currentPhoto)) {
                unlink($uploadDir . $currentPhoto);
            }
        }
        
        // Atualizar no banco - SALVAR APENAS O NOME DO ARQUIVO
        $updateQuery = "UPDATE usuarios SET perfil_foto = ? WHERE email = ?";
        $updateStmt = $conn->prepare($updateQuery);
        $updateStmt->bind_param("ss", $filename, $email_session);
        
        if ($updateStmt->execute()) {
            $response['success'] = true;
            $response['message'] = 'Foto de perfil atualizada com sucesso!';
            $response['photo_url'] = '../uploads/perfis/' . $filename;
            $response['filename'] = $filename;
        } else {
            throw new Exception('Erro ao salvar foto no banco: ' . $conn->error);
        }
    } else {
        throw new Exception('Erro ao salvar arquivo no servidor');
    }
    
    echo json_encode($response);
}
?>