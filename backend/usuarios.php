<?php
include './conexao.php';
session_start();

if (isset($_POST['login'])) {
    $email = $_POST['email'];
    $senha = $_POST['senha'];

    $sql = "SELECT * FROM usuario WHERE email = :email AND senha = :senha";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':email', $email);
    $stmt->bindParam(':senha', $senha);
    $stmt->execute();
    $resultado = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if (count($resultado) > 0) {
        $_SESSION['usuario'] = $resultado[0]['email'];
        $_SESSION['senha'] = $resultado[0]['senha'];
        $_SESSION['logged_in'] = true; // Set a session variable to indicate login status
        header('Location: ../frontLogado/Inicio.php'); // Redirect to the main page after login
    } else {
        header('Location: ../login/login.php'); 
    }

    exit();
}
?>