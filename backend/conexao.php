<?php
$servidor = "localhost:3306";
$usuario = "root";
$senha = "";
$banco = "constrular";

 $conn = new PDO("mysql:host=$servidor;dbname=$banco", $usuario, $senha);
// try {
   
//     $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
//     
//     echo "Conexão bem-sucedida!";
// } catch (PDOException $e) {
//     echo "Erro na conexão: " . $e->getMessage();
// }
// 
?>