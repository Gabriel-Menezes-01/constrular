<?php
include_once('../backend/conexao.php');


// Processar ações do admin
if (isset($_POST['action'])) {
    $projeto_id = $_POST['projeto_id'];

    switch ($_POST['action']) {
        case 'aprovar':
            $data_agendamento = $_POST['data_agendamento'];
            $resposta_admin = $_POST['resposta_admin'] ?? '';

            $sql = "UPDATE projetos SET status = 'aprovado', data_agendamento = ?, resposta_admin = ? WHERE id = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("ssi", $data_agendamento, $resposta_admin, $projeto_id);

            if ($stmt->execute()) {
                $sucesso = "Projeto aprovado com sucesso! Reunião agendada para " . date('d/m/Y', strtotime($data_agendamento));
            } else {
                $erro = "Erro ao aprovar projeto.";
            }
            break;

        case 'recusar':
            $resposta_admin = $_POST['resposta_admin'];

            $sql = "UPDATE projetos SET status = 'recusado', resposta_admin = ? WHERE id = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("si", $resposta_admin, $projeto_id);

            if ($stmt->execute()) {
                $sucesso = "Projeto recusado.";
            } else {
                $erro = "Erro ao recusar projeto.";
            }
            break;

        case 'cancelar':
            $resposta_admin = $_POST['resposta_admin'] ?? 'Projeto cancelado pelo administrador';

            $sql = "UPDATE projetos SET status = 'cancelado', resposta_admin = ? WHERE id = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("si", $resposta_admin, $projeto_id);

            if ($stmt->execute()) {
                $sucesso = "Projeto cancelado.";
            } else {
                $erro = "Erro ao cancelar projeto.";
            }
            break;
    }
    // Não faz redirect, só mostra alerta
}

// Recuperar mensagens
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

// Filtros
$filtro_status = $_GET['status'] ?? '';
$filtro_busca = $_GET['busca'] ?? '';

// Construir query
$sql = "SELECT p.*, u.nome as nome_usuario FROM projetos p 
        LEFT JOIN usuarios u ON p.email = u.email 
        WHERE 1=1";
$params = [];
$types = "";

if ($filtro_status) {
    $sql .= " AND p.status = ?";
    $params[] = $filtro_status;
    $types .= "s";
}

if ($filtro_busca) {
    $sql .= " AND (p.titulo LIKE ? OR p.email LIKE ? OR p.descricao LIKE ?)";
    $busca_param = "%" . $filtro_busca . "%";
    $params[] = $busca_param;
    $params[] = $busca_param;
    $params[] = $busca_param;
    $types .= "sss";
}

$sql .= " ORDER BY p.data_criacao DESC";

$stmt = $conn->prepare($sql);
if (!empty($params)) {
    $stmt->bind_param($types, ...$params);
}
$stmt->execute();
$projetos = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gerenciar Projetos - Admin | Constrular</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: #f8f9fa;
            color: #2c3e50;
        }
        
        .admin-header {
            background: linear-gradient(135deg, #2c3e50, #3498db);
            color: white;
            padding: 20px;
            text-align: center;
            margin-bottom: 30px;
        }
        
        .container {
            max-width: 1400px;
            margin: 0 auto;
            padding: 20px;
        }
        
        .filtros {
            background: white;
            padding: 20px;
            border-radius: 10px;
            margin-bottom: 30px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        
        .filtros-grid {
            display: grid;
            grid-template-columns: 1fr 2fr 1fr;
            gap: 15px;
            align-items: end;
        }
        
        .form-grupo {
            display: flex;
            flex-direction: column;
        }
        
        .form-grupo label {
            margin-bottom: 5px;
            font-weight: bold;
            color: #2c3e50;
        }
        
        .form-grupo select,
        .form-grupo input {
            padding: 10px;
            border: 2px solid #bdc3c7;
            border-radius: 5px;
            font-size: 14px;
        }
        
        .btn-filtrar {
            background: linear-gradient(135deg, #3498db, #2980b9);
            color: white;
            border: none;
            padding: 12px 20px;
            border-radius: 5px;
            cursor: pointer;
            font-weight: bold;
        }
        
        .btn-filtrar:hover {
            transform: translateY(-2px);
        }
        
        .projetos-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(400px, 1fr));
            gap: 20px;
        }
        
        .projeto-card {
            background: white;
            border-radius: 10px;
            padding: 20px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
            transition: transform 0.3s;
        }
        
        .projeto-card:hover {
            transform: translateY(-5px);
        }
        
        .projeto-imagem {
            width: 100%;
            height: 200px;
            object-fit: cover;
            border-radius: 8px;
            margin-bottom: 15px;
        }
        
        .status-badge {
            padding: 5px 15px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: bold;
            text-transform: uppercase;
            display: inline-block;
            margin-bottom: 10px;
        }
        
        .status-pendente { background: #f39c12; color: white; }
        .status-aprovado { background: #27ae60; color: white; }
        .status-recusado { background: #e74c3c; color: white; }
        .status-cancelado { background: #95a5a6; color: white; }
        
        .projeto-info {
            margin-bottom: 15px;
        }
        
        .projeto-info p {
            margin-bottom: 8px;
            line-height: 1.5;
        }
        
        .acoes-admin {
            border-top: 2px solid #ecf0f1;
            padding-top: 15px;
            margin-top: 15px;
        }
        
        .acoes-admin h4 {
            margin-bottom: 10px;
            color: #2c3e50;
        }
        
        .form-acao {
            margin-bottom: 10px;
        }
        
        .form-acao textarea,
        .form-acao input[type="date"] {
            width: 100%;
            padding: 8px;
            border: 1px solid #bdc3c7;
            border-radius: 4px;
            margin-bottom: 8px;
            font-size: 13px;
        }
        
        .botoes-acao {
            display: flex;
            gap: 8px;
            flex-wrap: wrap;
        }
        
        .btn-aprovar {
            background: #27ae60;
            color: white;
            border: none;
            padding: 8px 15px;
            border-radius: 4px;
            cursor: pointer;
            font-size: 12px;
        }
        
        .btn-recusar {
            background: #e74c3c;
            color: white;
            border: none;
            padding: 8px 15px;
            border-radius: 4px;
            cursor: pointer;
            font-size: 12px;
        }
        
        .btn-cancelar {
            background: #95a5a6;
            color: white;
            border: none;
            padding: 8px 15px;
            border-radius: 4px;
            cursor: pointer;
            font-size: 12px;
        }
        
        .btn-aprovar:hover { background: #219a52; }
        .btn-recusar:hover { background: #c0392b; }
        .btn-cancelar:hover { background: #7f8c8d; }
        
        .alerta {
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 20px;
        }
        
        .alerta-sucesso {
            background: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }
        
        .alerta-erro {
            background: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }
        
        .stats {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 15px;
            margin-bottom: 30px;
        }
        
        .stat-card {
            background: white;
            padding: 20px;
            border-radius: 10px;
            text-align: center;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        
        .stat-numero {
            font-size: 2rem;
            font-weight: bold;
            color: #3498db;
        }
        
        .stat-label {
            color: #7f8c8d;
            font-size: 14px;
            margin-top: 5px;
        }
    </style>
</head>
<body>
    <div class="admin-header">
        <h1>Painel Administrativo - Projetos</h1>
        <p>Gerencie todos os projetos enviados pelos usuários</p>
    </div>
    
    <div class="container">
        <?php if ($sucesso): ?>
            <div class="alerta alerta-sucesso"><?php echo htmlspecialchars($sucesso); ?></div>
        <?php endif; ?>
        
        <?php if ($erro): ?>
            <div class="alerta alerta-erro"><?php echo htmlspecialchars($erro); ?></div>
        <?php endif; ?>
        
        <!-- Estatísticas -->
        <?php
        $stats_sql = "SELECT 
                        COUNT(*) as total,
                        SUM(CASE WHEN status = 'pendente' THEN 1 ELSE 0 END) as pendentes,
                        SUM(CASE WHEN status = 'aprovado' THEN 1 ELSE 0 END) as aprovados,
                        SUM(CASE WHEN status = 'recusado' THEN 1 ELSE 0 END) as recusados,
                        SUM(CASE WHEN status = 'cancelado' THEN 1 ELSE 0 END) as cancelados
                      FROM projetos";
        $stats_result = $conn->query($stats_sql);
        $stats = $stats_result->fetch_assoc();
        ?>
        
        <div class="stats">
            <div class="stat-card">
                <div class="stat-numero"><?php echo $stats['total']; ?></div>
                <div class="stat-label">Total de Projetos</div>
            </div>
            <div class="stat-card">
                <div class="stat-numero"><?php echo $stats['pendentes']; ?></div>
                <div class="stat-label">Pendentes</div>
            </div>
            <div class="stat-card">
                <div class="stat-numero"><?php echo $stats['aprovados']; ?></div>
                <div class="stat-label">Aprovados</div>
            </div>
            <div class="stat-card">
                <div class="stat-numero"><?php echo $stats['recusados']; ?></div>
                <div class="stat-label">Recusados</div>
            </div>
            <div class="stat-card">
                <div class="stat-numero"><?php echo $stats['cancelados']; ?></div>
                <div class="stat-label">Cancelados</div>
            </div>
        </div>
        
        <!-- Filtros -->
        <div class="filtros">
            <form method="GET" class="filtros-grid">
                <div class="form-grupo">
                    <label for="status">Status</label>
                    <select name="status" id="status">
                        <option value="">Todos os Status</option>
                        <option value="pendente" <?php echo $filtro_status == 'pendente' ? 'selected' : ''; ?>>Pendente</option>
                        <option value="aprovado" <?php echo $filtro_status == 'aprovado' ? 'selected' : ''; ?>>Aprovado</option>
                        <option value="recusado" <?php echo $filtro_status == 'recusado' ? 'selected' : ''; ?>>Recusado</option>
                        <option value="cancelado" <?php echo $filtro_status == 'cancelado' ? 'selected' : ''; ?>>Cancelado</option>
                    </select>
                </div>
                
                <div class="form-grupo">
                    <label for="busca">Buscar</label>
                    <input type="text" name="busca" id="busca" placeholder="Título, email ou descrição..." value="<?php echo htmlspecialchars($filtro_busca); ?>">
                </div>
                
                <button type="submit" class="btn-filtrar">Filtrar</button>
            </form>
        </div>
        
        <!-- Lista de Projetos -->
        <div class="projetos-grid">
            <?php if ($projetos->num_rows > 0): ?>
                <?php while ($projeto = $projetos->fetch_assoc()): ?>
                    <div class="projeto-card">
                        <?php if ($projeto['imagem']): ?>
                            <img src="../uploads/projetos/<?php echo htmlspecialchars($projeto['imagem']); ?>" 
                                 alt="<?php echo htmlspecialchars($projeto['titulo']); ?>" 
                                 class="projeto-imagem">
                        <?php endif; ?>
                        
                        <h3><?php echo htmlspecialchars($projeto['titulo']); ?></h3>
                        
                        <span class="status-badge status-<?php echo $projeto['status']; ?>">
                            <?php echo ucfirst($projeto['status']); ?>
                        </span>
                        
                        <div class="projeto-info">
                            <p><strong>Cliente:</strong> <?php echo htmlspecialchars($projeto['nome_usuario'] ?? 'N/A'); ?></p>
                            <p><strong>Email:</strong> <?php echo htmlspecialchars($projeto['email']); ?></p>
                            <p><strong>Email Contato:</strong> <?php echo htmlspecialchars($projeto['email_contato']); ?></p>
                            <p><strong>Tipo:</strong> <?php echo htmlspecialchars(ucfirst($projeto['tipo_projeto'])); ?></p>
                            <p><strong>Descrição:</strong> <?php echo htmlspecialchars($projeto['descricao']); ?></p>
                            <p><strong>Data de Criação:</strong> <?php echo date('d/m/Y H:i', strtotime($projeto['data_criacao'])); ?></p>
                            
                            <?php if ($projeto['data_agendamento']): ?>
                                <p><strong>Reunião Agendada:</strong> <?php echo date('d/m/Y', strtotime($projeto['data_agendamento'])); ?></p>
                            <?php endif; ?>
                            
                            <?php if ($projeto['resposta_admin']): ?>
                                <p><strong>Resposta Admin:</strong> <?php echo htmlspecialchars($projeto['resposta_admin']); ?></p>
                            <?php endif; ?>
                        </div>
                        
                        <?php if ($projeto['status'] == 'pendente' || $projeto['status'] == 'aprovado'): ?>
                            <div class="acoes-admin">
                                <h4>Ações do Administrador</h4>
                                
                                <?php if ($projeto['status'] == 'pendente'): ?>
                                    <!-- Aprovar -->
                                    <form method="POST" class="form-acao">
                                        <input type="hidden" name="action" value="aprovar">
                                        <input type="hidden" name="projeto_id" value="<?php echo $projeto['id']; ?>">
                                        <input type="date" name="data_agendamento" required min="<?php echo date('Y-m-d', strtotime('+1 day')); ?>">
                                        <textarea name="resposta_admin" placeholder="Mensagem para o cliente (opcional)..." rows="2"></textarea>
                                        <button type="submit" class="btn-aprovar" onclick="return confirm('Aprovar este projeto?')">
                                            Aprovar e Agendar
                                        </button>
                                    </form>
                                    
                                    <!-- Recusar -->
                                    <form method="POST" class="form-acao">
                                        <input type="hidden" name="action" value="recusar">
                                        <input type="hidden" name="projeto_id" value="<?php echo $projeto['id']; ?>">
                                        <textarea name="resposta_admin" placeholder="Motivo da recusa..." rows="2" required></textarea>
                                        <button type="submit" class="btn-recusar" onclick="return confirm('Recusar este projeto?')">
                                            Recusar
                                        </button>
                                    </form>
                                <?php endif; ?>
                                
                                <!-- Cancelar (disponível para pendente e aprovado) -->
                                <form method="POST" class="form-acao">
                                    <input type="hidden" name="action" value="cancelar">
                                    <input type="hidden" name="projeto_id" value="<?php echo $projeto['id']; ?>">
                                    <textarea name="resposta_admin" placeholder="Motivo do cancelamento (opcional)..." rows="2"></textarea>
                                    <button type="submit" class="btn-cancelar" onclick="return confirm('Cancelar este projeto?')">
                                        Cancelar Projeto
                                    </button>
                                </form>
                            </div>
                        <?php endif; ?>
                    </div>
                <?php endwhile; ?>
            <?php else: ?>
                <div style="grid-column: 1 / -1; text-align: center; padding: 40px; color: #7f8c8d;">
                    <h3>Nenhum projeto encontrado</h3>
                    <p>Não há projetos que correspondam aos filtros selecionados.</p>
                </div>
            <?php endif; ?>
        </div>
    </div>
    
    <script>
        // Auto-submit nos filtros quando mudarem
        document.getElementById('status').addEventListener('change', function() {
            this.form.submit();
        });
        
        // Confirmar ações importantes
        document.querySelectorAll('form').forEach(form => {
            form.addEventListener('submit', function(e) {
                const action = this.querySelector('input[name="action"]')?.value;
                if (action && ['aprovar', 'recusar', 'cancelar'].includes(action)) {
                    const button = this.querySelector('button[type="submit"]');
                    button.disabled = true;
                    button.textContent = 'Processando...';
                }
            });
        });
    </script>
</body>
</html>