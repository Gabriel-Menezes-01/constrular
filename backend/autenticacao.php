<?php
include './conexao.php';
session_start();

if (isset($_POST['cadrasto'])) {
    if (!empty($_POST['nome']) && !empty($_POST['apelido']) && !empty($_POST['email']) && !empty($_POST['senha'])) {
        $nome = $_POST['nome'];
        $apelido = $_POST['apelido'];
        $email = $_POST['email'];
        $senha =$_POST['senha'];

        try {
            $sql = "INSERT INTO usuario (nome, apelido, email, senha) VALUES (:nome, :apelido, :email, :senha)";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':nome', $nome);
            $stmt->bindParam(':apelido', $apelido);
            $stmt->bindParam(':email', $email);
            $stmt->bindParam(':senha', $senha);

            $stmt->execute();

            // Login automático após cadastro
            $_SESSION['email'] = $email;
            $_SESSION['senha'] = $senha;
            header('Location: ../frontLogado/Inicio.php');
            exit();
        } catch (PDOException $e) {
            echo "Erro ao salvar os dados: " . $e->getMessage();
        }
    } 
}

?>