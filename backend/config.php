<?php
// Inicia a sessão
session_start();

// Destroi a sessão
session_destroy();

// Redireciona para a página inicial
header("Location: ../front/Inicio.php");
exit;
?>