<?php
session_start();
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    require_once 'conexao.php'; // arquivo de conexão com o banco

    $email = $_POST['email'] ?? '';
    $senha = $_POST['senha'] ?? '';
    $nome = $_POST['nome'] ?? '';

    // Protege contra SQL Injection
    $stmt = $conn->prepare("SELECT id, nome FROM usuarios WHERE email = ? AND senha = ?");
    $stmt->bind_param("ss", $email, $senha);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($row = $result->fetch_assoc()) {
        // Verifica se a senha está correta
            $_SESSION['usuario_id'] = $row['id'];
            $_SESSION['email'] = $email;
            $_SESSION['senha'] = $senha;
            $_SESSION['apelido'] = $row['nome']; // Armazena o apelido
            $_SESSION['nome'] = $row['nome'];
            $_SESSION['isAdmin'] = ($row['nome'] === 'Admin'); // Verifica se o usuário é admin
            header('Location: ../frontLogado/Inicio.php');
            exit;
        
        
    } else {
        echo "Usuário não encontrado.";
    }

    $stmt->close();
    $conn->close();
}
else {
    echo "Método de requisição inválido.";
    exit();
}
?>
