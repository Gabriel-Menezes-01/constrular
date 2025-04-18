<?php
include '../backend/conexao.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $novoTexto = $_POST['texto'];
    $novaImagem = $_POST['imagem'];

    $query = "UPDATE conteudo SET texto='$novoTexto', imagem='$novaImagem' WHERE id=1";
    $conn->query($query);
    echo "Conteúdo atualizado!";
}
?>

<form method="POST">
    <textarea name="texto" placeholder="Novo texto"></textarea>
    <input type="text" name="imagem" placeholder="URL da nova imagem">
    <button type="submit">Salvar</button>
</form>