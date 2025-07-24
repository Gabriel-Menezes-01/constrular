
<?php
// Script para criar diretório de upload
$upload_dir = 'uploads/projetos';

if (!file_exists($upload_dir)) {
    mkdir($upload_dir, 0777, true);
    echo "Diretório criado: " . $upload_dir;
} else {
    echo "Diretório já existe: " . $upload_dir;
}
?>