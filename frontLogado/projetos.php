<?php

include_once('../backend/conexao.php');




// Processar formulário de novo projeto
if (isset($_POST['action']) && $_POST['action'] == 'criar_projeto') {
    $titulo = $_POST['titulo'];
    $tipo_projeto = $_POST['tipo_projeto'];
    $descricao = $_POST['descricao'];
    $email_contato = $_POST['email_contato'];
    
    // Verificar se já existe um projeto similar (mesmo título e email) nos últimos 5 minutos
    $sql_check = "SELECT id FROM projetos WHERE titulo = ? AND email = ? AND data_criacao > DATE_SUB(NOW(), INTERVAL 5 MINUTE)";
    $stmt_check = $conn->prepare($sql_check);
    $stmt_check->bind_param("ss", $titulo, $_SESSION['email']);
    $stmt_check->execute();
    $projeto_existente = $stmt_check->get_result();
    
    if ($projeto_existente->num_rows > 0) {
        echo "<script>alert('Você já enviou um projeto com este título recentemente. Tente novamente mais tarde.'); window.history.back();</script>";
        exit();
    }
    
    // Upload da imagem
    $imagem = '';
    if (isset($_FILES['imagem']) && $_FILES['imagem']['error'] == 0) {
        $extensao = pathinfo($_FILES['imagem']['name'], PATHINFO_EXTENSION);
        $extensoes_permitidas = ['jpg', 'jpeg', 'png', 'gif'];
        
        if (!in_array(strtolower($extensao), $extensoes_permitidas)) {
            $_SESSION['erro'] = "Formato de imagem não permitido. Use JPG, PNG ou GIF.";
            header('Location: projetos.php');
            exit();
        }
        
        $nome_arquivo = uniqid() . '.' . $extensao;
        $caminho_upload = '../uploads/projetos/' . $nome_arquivo;
        
        // Criar diretório se não existir
        if (!file_exists('../uploads/projetos/')) {
            mkdir('../uploads/projetos/', 0777, true);
        }
        
        if (move_uploaded_file($_FILES['imagem']['tmp_name'], $caminho_upload)) {
            $imagem = $nome_arquivo;
        } else {
            $_SESSION['erro'] = "Erro ao fazer upload da imagem.";
            header('Location: projetos.php');
            exit();
        }
    }
    
    $sql = "INSERT INTO projetos (titulo, imagem, status, email, email_contato, descricao, tipo_projeto, data_criacao) 
            VALUES (?, ?, 'pendente', ?, ?, ?, ?, NOW())";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssssss", $titulo, $imagem, $_SESSION['email'], $email_contato, $descricao, $tipo_projeto);

    if ($stmt->execute()) {
        $_SESSION['sucesso'] = "Projeto enviado com sucesso! Aguarde a análise do administrador.";
        echo "<script>alert('Projeto enviado com sucesso! Aguarde a análise do administrador.'); window.location.href = './inicio.php';</script>";
        exit();
    } else {
        echo "<script>alert('Erro ao enviar projeto: " . addslashes($conn->error) . "'); window.history.back();</script>";
        exit();
    }
}


// Processar cancelamento de projeto
if (isset($_POST['action']) && $_POST['action'] == 'cancelar_projeto') {
    $projeto_id = $_POST['projeto_id'];

    // Verificar se pode cancelar (antes da aprovação ou até 2 dias antes da reunião)
    $sql = "SELECT status, data_agendamento FROM projetos WHERE id = ? AND email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("is", $projeto_id, $_SESSION['email']);
    $stmt->execute();
    $resultado = $stmt->get_result()->fetch_assoc();

    if ($resultado) {
        $pode_cancelar = false;
        if ($resultado['status'] == 'pendente') {
            $pode_cancelar = true;
        } elseif ($resultado['status'] == 'aprovado' && $resultado['data_agendamento']) {
            $data_limite = date('Y-m-d', strtotime($resultado['data_agendamento'] . ' -2 days'));
            if (date('Y-m-d') <= $data_limite) {
                $pode_cancelar = true;
            }
        }

        if ($pode_cancelar) {
            $sql = "UPDATE projetos SET status = 'cancelado' WHERE id = ? AND email = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("is", $projeto_id, $_SESSION['email']);
            $stmt->execute();
            $_SESSION['sucesso'] = "Projeto cancelado com sucesso.";
        }
        // Não define erro se não pode cancelar ou projeto não encontrado
    } else {
        // Se não pode cancelar ou projeto não encontrado, mostra alerta sem redirecionar
        $_SESSION['erro'] = "Projeto não encontrado ou você não tem permissão para cancelá-lo.";
    }
    // Só redireciona se cancelou, senão permanece na página
    if ($pode_cancelar || isset($_SESSION['erro'])) {
        echo "<script>alert('Projeto cancelado com sucesso.'); window.location.href = './inicio.php';</script>";
        exit();
    }
}

// Recuperar mensagens da sessão
$sucesso = '';
$erro = '';
if (isset($_SESSION['sucesso'])) {
    $sucesso = $_SESSION['sucesso'];
    unset($_SESSION['sucesso']);
}
if (isset($_SESSION['erro'])) {
    $erro = $_SESSION['erro'];
    unset($_SESSION['erro']);
}

// Buscar projetos do usuário
$sql = "SELECT * FROM projetos WHERE email = ? ORDER BY data_criacao DESC";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $_SESSION['email']);
$stmt->execute();
$projetos = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Meus Projetos - Constrular</title>
    <link rel="stylesheet" href="../css/projetos.css">

</head>
<body>
    <div class="projetos-container">
        <div class="projetos-header">
            <h1>Gerenciar Projetos</h1>
            <p>Envie suas ideias e acompanhe o andamento dos seus projetos</p>
        </div>
        
        <?php if ($sucesso): ?>
            <div class="alerta alerta-sucesso"><?php echo htmlspecialchars($sucesso); ?></div>
        <?php endif; ?>
        
        <?php if ($erro): ?>
            <div class="alerta alerta-erro"><?php echo htmlspecialchars($erro); ?></div>
        <?php endif; ?>
        
        <div class="projetos-grid">
            <!-- Formulário para novo projeto -->
            <div class="form-projeto">
                <h2>Novo Projeto</h2>
                <form method="POST" enctype="multipart/form-data" id="formProjeto">
                    <input type="hidden" name="action" value="criar_projeto">
                    
                    <div class="form-grupo">
                        <label for="titulo">Nome do Projeto</label>
                        <input type="text" id="titulo" name="titulo" required maxlength="255">
                    </div>
                    
                    <div class="form-grupo">
                        <label for="tipo_projeto">Tipo de Projeto</label>
                        <select id="tipo_projeto" name="tipo_projeto" required>
                            <option value="">Selecione o tipo</option>
                            <option value="residencial">Residencial</option>
                            <option value="comercial">Comercial</option>
                            <option value="industrial">Industrial</option>
                            <option value="reforma">Reforma</option>
                            <option value="paisagismo">Paisagismo</option>
                        </select>
                    </div>
                    
                    <div class="form-grupo">
                        <label for="imagem">Foto do Projeto</label>
                        <input type="file" id="imagem" name="imagem" accept="image/jpeg,image/jpg,image/png,image/gif">
                        <small style="color: #7f8c8d;">Formatos aceitos: JPG, PNG, GIF (máx. 5MB)</small>
                    </div>
                    
                    <div class="form-grupo">
                        <label for="email_contato">Email de Contato</label>
                        <input type="email" id="email_contato" name="email_contato" value="<?php echo htmlspecialchars($_SESSION['email']); ?>" required>
                    </div>
                    
                    <div class="form-grupo">
                        <label for="descricao">Descrição do Projeto</label>
                        <textarea id="descricao" name="descricao" rows="4" placeholder="Descreva detalhadamente seu projeto..." required maxlength="1000"></textarea>
                    </div>
                    
                    <button type="submit" class="btn-enviar" id="btnEnviar">Enviar Projeto</button>
                </form>
            </div>
            
            <!-- Lista de projetos -->
            <div class="lista-projetos">
                <h2>Meus Projetos</h2>
                
                <?php if ($projetos->num_rows > 0): ?>
                    <?php while ($projeto = $projetos->fetch_assoc()): ?>
                        <div class="projeto-card">
                            <?php if ($projeto['imagem']): ?>
                                <img src="../uploads/projetos/<?php echo htmlspecialchars($projeto['imagem']); ?>" alt="<?php echo htmlspecialchars($projeto['titulo']); ?>" class="projeto-imagem">
                            <?php endif; ?>
                            
                            <h3><?php echo htmlspecialchars($projeto['titulo']); ?></h3>
                            
                            <span class="status-badge status-<?php echo $projeto['status']; ?>">
                                <?php echo ucfirst($projeto['status']); ?>
                            </span>
                            
                            <p><strong>Tipo:</strong> <?php echo htmlspecialchars(ucfirst($projeto['tipo_projeto'])); ?></p>
                            <p><strong>Email:</strong> <?php echo htmlspecialchars($projeto['email_contato']); ?></p>
                            <p><strong>Descrição:</strong> <?php echo htmlspecialchars($projeto['descricao']); ?></p>
                            <p><strong>Data de Criação:</strong> <?php echo date('d/m/Y', strtotime($projeto['data_criacao'])); ?></p>
                            
                            <?php if ($projeto['data_agendamento']): ?>
                                <p><strong>Reunião Agendada:</strong> <?php echo date('d/m/Y', strtotime($projeto['data_agendamento'])); ?></p>
                            <?php endif; ?>
                            
                            <?php if ($projeto['resposta_admin']): ?>
                                <div style="background: #f8f9fa; padding: 10px; border-radius: 5px; margin-top: 10px;">
                                    <strong>Resposta do Administrador:</strong><br>
                                    <em><?php echo htmlspecialchars($projeto['resposta_admin']); ?></em>
                                </div>
                            <?php endif; ?>
                            
                            <?php
                            // Verificar se pode cancelar
                            $pode_cancelar = false;
                            if ($projeto['status'] == 'pendente') {
                                $pode_cancelar = true;
                            } elseif ($projeto['status'] == 'aprovado' && $projeto['data_agendamento']) {
                                $data_limite = date('Y-m-d', strtotime($projeto['data_agendamento'] . ' -2 days'));
                                if (date('Y-m-d') <= $data_limite) {
                                    $pode_cancelar = true;
                                }
                            }
                            ?>
                            
                            <?php if ($pode_cancelar): ?>
                                <form method="POST" style="display: inline;">
                                    <input type="hidden" name="action" value="cancelar_projeto">
                                    <input type="hidden" name="projeto_id" value="<?php echo $projeto['id']; ?>">
                                    <button type="submit" class="btn-cancelar" onclick="return confirm('Tem certeza que deseja cancelar este projeto?')">
                                        Cancelar Projeto
                                    </button>
                                </form>
                            <?php elseif ($projeto['status'] == 'aprovado' && $projeto['data_agendamento']): ?>
                                <p style="color: #e74c3c; font-size: 12px;">
                                    <em>Cancelamento disponível até <?php echo date('d/m/Y', strtotime($projeto['data_agendamento'] . ' -2 days')); ?></em>
                                </p>
                            <?php endif; ?>
                        </div>
                    <?php endwhile; ?>
                <?php else: ?>
                    <p style="text-align: center; color: #7f8c8d; font-style: italic;">
                        Nenhum projeto encontrado. Envie seu primeiro projeto!
                    </p>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <script>
        // Prevenir duplo envio do formulário
        document.getElementById('formProjeto').addEventListener('submit', function(e) {
            const btnEnviar = document.getElementById('btnEnviar');
            btnEnviar.disabled = true;
            btnEnviar.textContent = 'Enviando...';
            
            // Reabilitar após 5 segundos caso algo dê errado
            setTimeout(() => {
                btnEnviar.disabled = false;
                btnEnviar.textContent = 'Enviar Projeto';
            }, 5000);
        });

        // Validar tamanho da imagem
        document.getElementById('imagem').addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file && file.size > 5 * 1024 * 1024) { // 5MB
                alert('A imagem deve ter no máximo 5MB');
                e.target.value = '';
            }
        });
    </script>
</body>
</html>