<?php
include './conexao.php';
session_start();

if (isset($_POST['cadrasto'])) {
    if (!empty($_POST['nome']) && !empty($_POST['apelido']) && !empty($_POST['email']) && !empty($_POST['senha'])) {
        $nome = $_POST['nome'];
        $apelido = $_POST['apelido'];
        $email = $_POST['email'];
        $senha =$_POST['senha'];
        // Usar prepared statements para evitar SQL Injection
        $sql = "INSERT INTO usuarios (nome, apelido, email, senha) VALUES (?, ?, ?, ?)";

        $stmt = mysqli_prepare($conn, $sql);
        $stmt->bind_param("ssss", $nome, $apelido, $email, $senha);


        
        header('Content-Type: application/json');
        header('Access-Control-Allow-Origin: *');
      


        if ($stmt) {
            if (mysqli_stmt_execute($stmt)) {
            // Login automático após cadastro
            $_SESSION['email'] = $email;
            $_SESSION['senha'] = $senha;
            // Retorna resposta JSON para AJAX
            json_encode(['success' =>$email]);
            header('Location: ../frontLogado/Inicio.php');
            exit();
            } else {
            // Verifica se o erro foi de email duplicado
            if (mysqli_errno($conn) == 1062) {
                echo "<script>alert('Este email já está cadastrado.'); window.history.back();</script>";
                header('Location: ../login/cadastro.html');
            } elseif (mysqli_errno($conn) == 1048) {
                echo "<script>alert('Preencha todos os campos.'); window.history.back();</script>";
                header('Location: ../login/cadastro.html');
            } else {
                echo "<script>alert('Erro ao salvar os dados.'); window.history.back();</script>";
                header('Location: ../login/cadastro.html');
            }
            }
            mysqli_stmt_close($stmt);
        } else {
            echo "<script>alert('Erro ao preparar a consulta.'); window.history.back();</script>";
            header('Location: ../login/cadastro.html');
        }
        exit();
    } 
}

?>