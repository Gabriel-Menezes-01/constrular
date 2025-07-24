<?php
include './conexao.php';
session_start();

if (isset($_POST['cadrasto'])) {
    if (!empty($_POST['nome']) && !empty($_POST['apelido']) && !empty($_POST['email']) && !empty($_POST['senha'])) {
        $nome = $_POST['nome'];
        $apelido = $_POST['apelido'];
        $email = $_POST['email'];
        $senha = $_POST['senha'];

        $sql = "INSERT INTO usuarios (nome, apelido, email, senha) VALUES ('$nome', '$apelido', '$email', '$senha')";
        if (mysqli_query($conn, $sql)) {
            // Cadastra o email na tabela de projetos
            
            

            // Login automático após cadastro
            $_SESSION['usuario_id'] = mysqli_insert_id($conn);
            $_SESSION['nome'] = $nome;
            $_SESSION['email'] = $email;
            $_SESSION['senha'] = $senha;
            header('Location: ../frontLogado/Inicio.php');
            exit();
        } else {
            echo "Erro ao salvar os dados: " . mysqli_error($conn);
        }
    }
}

?>