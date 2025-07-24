<?php
// filepath: c:\wamp64\www\constrular\backend\conexao.php

// Configurações do banco
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "constrular";

try {
    // Conectar ao MySQL
    $conn = mysqli_connect($servername, $username, $password, $dbname);
    
    // Verificar conexão
    if (!$conn) {
        throw new Exception("Falha na conexão: " . mysqli_connect_error());
    }
    
    // Definir charset
    mysqli_set_charset($conn, "utf8mb4");
    
    // Log de sucesso (apenas para debug)
    error_log("Conexão com banco estabelecida com sucesso");
    
} catch (Exception $e) {
    error_log("Erro na conexão com banco: " . $e->getMessage());
    die("Erro de conexão com banco de dados");
}
?>