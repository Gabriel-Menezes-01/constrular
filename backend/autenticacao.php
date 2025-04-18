<?php
include 'conexao.php';

session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $senha = $_POST['senha'];

    if (isset($_POST['cadastro'])) {
        $senhaCriptografada = password_hash($senha, PASSWORD_DEFAULT);
        $query = "INSERT INTO usuarios (email, senha) VALUES ('$email', '$senhaCriptografada')";
        $conn->query($query);
        echo "Usuário cadastrado com sucesso!";
    } elseif (isset($_POST['login'])) {
        $query = "SELECT senha FROM usuarios WHERE email='$email'";
        $result = $conn->query($query);
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            if (password_verify($senha, $row['senha'])) {
                $_SESSION['usuario'] = $email;
                echo "Login realizado com sucesso!";
            } else {
                echo "Senha incorreta!";
            }
        } else {
            echo "Usuário não encontrado!";
        }
    }
}
?>