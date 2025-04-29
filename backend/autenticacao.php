<?php
include './conexao.php';
session_start();
if(isset($_POST['cadrasto'])){
    if(!empty($_POST['nome']) && !empty($_POST['apelido']) && !empty($_POST['email']) && !empty($_POST['senha'])) {
        $nome = $_POST['nome'];
        $apelido = $_POST['apelido'];
        $email = $_POST['email'];
        $senha = password_hash($_POST['senha'], PASSWORD_DEFAULT); // Hashing the password for security

        try {
            $sql = "INSERT INTO usuario (nome, apelido, email, senha) VALUES (:nome, :apelido, :email, :senha)";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':nome', $nome);
            $stmt->bindParam(':apelido', $apelido);
            $stmt->bindParam(':email', $email);
            $stmt->bindParam(':senha', $senha);
            $stmt->execute();

            header('Location: ../front/Inicio.html');
            exit();
        } catch (PDOException $e) {
            echo "Erro ao salvar os dados: " . $e->getMessage();
            header('Location: ../login/cadrasto.html?error=1');
            exit();
        }
    } else {
        header('Location: ../login/cadrasto.html?error=2');
        exit();
    }
} else {
    header('Location: ../login/cadrasto.html');
    exit();
}


?>