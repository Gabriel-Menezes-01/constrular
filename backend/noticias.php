<?php
// Conectar ao banco de dados
include 'conexao.php';

$query = "SELECT titulo, conteudo FROM noticias ORDER BY data DESC LIMIT 5";
$result = mysqli_query($conn, $query);

while ($row = mysqli_fetch_assoc($result)) {
    echo "<div class='noticia'>";
    echo "<h3>" . htmlspecialchars($row['titulo']) . "</h3>";
    echo "<p>" . htmlspecialchars($row['conteudo']) . "</p>";
    echo "</div>";
}
?>