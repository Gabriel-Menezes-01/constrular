<?php
include 'conexao.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $senha = password_hash($_POST['senha'], PASSWORD_DEFAULT);

    $query = "INSERT INTO usuarios (email, senha) VALUES ('$email', '$senha')";
    mysqli_query($conn, $query);

    echo "Usuário cadastrado com sucesso!";
}
?>