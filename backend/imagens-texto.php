<?php
// Conectar ao banco de dados
include 'conexao.php';

// Endpoint para atualizar textos e imagens
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $novoTexto = $_POST['texto'];
    $novaImagem = $_POST['imagem'];

    $query = "UPDATE conteudo SET texto='$novoTexto', imagem='$novaImagem' WHERE id=1";
    mysqli_query($conn, $query);

    echo "Conteúdo atualizado com sucesso!";
}
?>