<?php
// filepath: c:\wamp64\www\constrular\backend\debug_users.php

session_start();
header('Content-Type: text/html; charset=utf-8');

echo "<h2>Debug - Sistema de Usuários</h2>";

// 1. Verificar sessão
echo "<h3>1. Verificação de Sessão:</h3>";
echo "<pre>";
print_r($_SESSION);
echo "</pre>";

// 2. Verificar conexão
echo "<h3>2. Verificação de Conexão:</h3>";
try {
    include 'conexao.php';
    echo "✅ Conexão estabelecida com sucesso<br>";
    echo "Charset: " . mysqli_character_set_name($conn) . "<br>";
} catch (Exception $e) {
    echo "❌ Erro na conexão: " . $e->getMessage() . "<br>";
    exit;
}

// 3. Verificar estrutura da tabela
echo "<h3>3. Estrutura da Tabela 'usuarios':</h3>";
$desc = mysqli_query($conn, "DESCRIBE usuarios");
if ($desc) {
    echo "<table border='1' style='border-collapse: collapse;'>";
    echo "<tr><th>Campo</th><th>Tipo</th><th>Null</th><th>Key</th><th>Default</th><th>Extra</th></tr>";
    while ($row = mysqli_fetch_assoc($desc)) {
        echo "<tr>";
        foreach ($row as $value) {
            echo "<td>" . ($value ?? 'NULL') . "</td>";
        }
        echo "</tr>";
    }
    echo "</table>";
} else {
    echo "❌ Erro ao verificar estrutura: " . mysqli_error($conn);
}

// 4. Verificar dados
echo "<h3>4. Dados da Tabela (primeiros 5 registros):</h3>";
$query = "SELECT * FROM usuarios LIMIT 5";
$result = mysqli_query($conn, $query);

if ($result) {
    if (mysqli_num_rows($result) > 0) {
        echo "<table border='1' style='border-collapse: collapse;'>";
        
        // Cabeçalho
        $firstRow = mysqli_fetch_assoc($result);
        echo "<tr>";
        foreach (array_keys($firstRow) as $column) {
            echo "<th>$column</th>";
        }
        echo "</tr>";
        
        // Primeira linha
        echo "<tr>";
        foreach ($firstRow as $value) {
            echo "<td>" . ($value ?? 'NULL') . "</td>";
        }
        echo "</tr>";
        
        // Demais linhas
        while ($row = mysqli_fetch_assoc($result)) {
            echo "<tr>";
            foreach ($row as $value) {
                echo "<td>" . ($value ?? 'NULL') . "</td>";
            }
            echo "</tr>";
        }
        echo "</table>";
    } else {
        echo "⚠️ Nenhum usuário encontrado na tabela";
    }
} else {
    echo "❌ Erro na query: " . mysqli_error($conn);
}

// 5. Testar query específica
echo "<h3>5. Teste da Query Específica:</h3>";
$query = "SELECT 
            id, 
            nome, 
            email, 
            COALESCE(ativo, 1) as ativo,
            COALESCE(data_cadastro, created_at, NOW()) as created_at
          FROM usuarios 
          ORDER BY nome ASC";

echo "<strong>Query:</strong><br><code>$query</code><br><br>";

$result = mysqli_query($conn, $query);
if ($result) {
    $users = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $users[] = [
            'id' => (int)$row['id'],
            'nome' => $row['nome'] ?? 'Nome não informado',
            'email' => $row['email'] ?? 'Email não informado',
            'ativo' => (bool)($row['ativo'] ?? 1),
            'created_at' => $row['created_at'] ?? date('Y-m-d H:i:s')
        ];
    }
    
    echo "<strong>Resultado JSON:</strong><br>";
    echo "<pre>" . json_encode($users, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) . "</pre>";
} else {
    echo "❌ Erro na query: " . mysqli_error($conn);
}

mysqli_close($conn);
?>