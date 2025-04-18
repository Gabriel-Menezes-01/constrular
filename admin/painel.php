<?php
session_start();
if (!isset($_SESSION['usuario'])) {
    header("Location: ../login.html");
    exit();
}

echo "<h1>Painel Administrativo</h1>";
echo "<a href='editar-conteudo.php'>Editar Conteúdo</a> | ";
echo "<a href='verificar-usuarios.php'>Ver Usuários Cadastrados</a> | ";
echo "<a href='logout.php'>Sair</a>";
?>