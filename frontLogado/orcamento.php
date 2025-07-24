<?php
session_start();

include '../backend/conexao.php';
if (!isset($_SESSION['email']) || !isset($_SESSION['senha'])) {
  header('Location: ../front/Inicio.php');
  exit;
}
$email = $_SESSION['email'];



$query = "SELECT nome, apelido, email FROM usuarios WHERE email = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("s", $email);
$stmt->execute();
$resultado = $stmt->get_result()->fetch_assoc();

if ($resultado) {
  $nome = $resultado['nome'];
  $apelido = $resultado['apelido'];
  $email = $resultado['email'];
} else {
  header('Location: ../front/Inicio.php');
  exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['get_logged_email']) && $_POST['get_logged_email'] == '1') {
  header('Access-Control-Allow-Origin: *');
  header('Content-Type: application/json');
  echo json_encode(['email' => $email]);
  exit;
}
?>
<script>

function buscarEmailLogado(callback) {
  fetch(window.location.pathname, {
    method: 'POST',
    headers: {'Content-Type': 'application/x-www-form-urlencoded'},
    body: 'get_logged_email=1'
  })
  .then(response => response.json())
  .then(data => {
    if (callback) callback(data.email);
  });
}

buscarEmailLogado(function(email) {
  // Mostra o botão "Ver Usuários" apenas para o admin
  var showUsersElems = document.querySelectorAll('.users');
  if (email === 'admin@gmail.com') {
    showUsersElems.forEach(function(elem) {
      elem.style.display = 'block';
    });
  } else {
    showUsersElems.forEach(function(elem) {
      elem.style.display = 'none';
    });
  }
});

</script>
<!DOCTYPE html>
<html lang="pt">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
  <link rel="stylesheet" href="../css/styles.css">
  <link rel="stylesheet" href="../css/orcamento.css">
  <title>Orçamento - ConstruLar</title>
</head>

<body>
  <!-- nav bar -->
    <?php include('./header2.php'); ?>

  <section class="orcamento-section">
    <div class="container">
      <div class="orcamento-card">
        <div class="card-header">
          <h2><i class="bi bi-calculator"></i> Calcule seu Orçamento</h2>
          <p>Descubra quanto custa realizar seu sonho</p>
        </div>
        
        <form class="orcamento-form" id="orcamentoForm">
          <div class="form-group">
            <div class="input-wrapper">
              <label for="nome"><i class="bi bi-person"></i> Nome Completo</label>
              <input type="text" name="nome" id="nome" placeholder="Digite seu nome completo" required>
              <span class="input-focus"></span>
            </div>
            
            <div class="input-wrapper">
              <label for="telefone"><i class="bi bi-telephone"></i> Telefone</label>
              <input type="tel" name="telefone" id="telefone" placeholder="+351 999 999 999" required>
              <span class="input-focus"></span>
            </div>
          </div>

          <div class="form-group">
            <div class="input-wrapper">
              <label for="largura"><i class="bi bi-arrows-expand"></i> Largura do Terreno (m)</label>
              <input type="number" name="largura" id="largura" placeholder="Ex: 10" min="1" step="0.1" required>
              <span class="input-focus"></span>
            </div>
            
            <div class="input-wrapper">
              <label for="comprimento"><i class="bi bi-arrows-expand"></i> Comprimento do Terreno (m)</label>
              <input type="number" name="comprimento" id="comprimento" placeholder="Ex: 20" min="1" step="0.1" required>
              <span class="input-focus"></span>
            </div>
          </div>

          <div class="form-group">
            <div class="input-wrapper">
              <label for="tipo-construcao"><i class="bi bi-house"></i> Tipo de Construção</label>
              <select name="tipo-construcao" id="tipo-construcao" required>
                <option value="">Selecione o tipo</option>
                <option value="residencial">Casa Residencial</option>
                <option value="comercial">Estabelecimento Comercial</option>
                <option value="apartamento">Apartamento</option>
                <option value="sobrado">Sobrado</option>
              </select>
              <span class="input-focus"></span>
            </div>
            
            <div class="input-wrapper">
              <label for="acabamento"><i class="bi bi-palette"></i> Padrão de Acabamento</label>
              <select name="acabamento" id="acabamento" required>
                <option value="">Selecione o padrão</option>
                <option value="basico">Básico (R$ 1.200/m²)</option>
                <option value="medio">Médio (R$ 1.500/m²)</option>
                <option value="alto">Alto (R$ 2.000/m²)</option>
                <option value="luxo">Luxo (R$ 2.800/m²)</option>
              </select>
              <span class="input-focus"></span>
            </div>
          </div>

          <div class="error-message" id="errorMessage">
            <i class="bi bi-exclamation-triangle"></i>
            <span>Por favor, preencha todos os campos corretamente</span>
          </div>

          <button type="submit" class="btn-calcular">
            <i class="bi bi-calculator"></i>
            <span>Calcular Orçamento</span>
            <div class="btn-loading">
              <i class="bi bi-arrow-repeat"></i>
            </div>
          </button>

          <div class="result-section" id="resultSection">
            <div class="result-card">
              <h3><i class="bi bi-graph-up"></i> Resultado do Orçamento</h3>
              <div class="result-details">
                <div class="detail-item">
                  <span class="label">Área Total:</span>
                  <span class="value" id="areaTotal">-</span>
                </div>
                <div class="detail-item">
                  <span class="label">Tipo:</span>
                  <span class="value" id="tipoResult">-</span>
                </div>
                <div class="detail-item">
                  <span class="label">Padrão:</span>
                  <span class="value" id="padraoResult">-</span>
                </div>
                <div class="detail-item total">
                  <span class="label">Valor Estimado:</span>
                  <span class="value" id="valorTotal">R$ 0,00</span>
                </div>
              </div>
              
              <div class="action-buttons">
                <button type="button" class="btn-whatsapp" id="btnWhatsapp">
                  <i class="bi bi-whatsapp"></i>
                  Enviar via WhatsApp
                </button>
                <button type="button" class="btn-pdf" id="btnPdf">
                  <i class="bi bi-file-pdf"></i>
                  Baixar PDF
                </button>
              </div>
            </div>
          </div>

          <div class="observacao">
            <div class="obs-icon">
              <i class="bi bi-info-circle"></i>
            </div>
            <div class="obs-content">
              <h4>Observação Importante</h4>
              <p>O valor apresentado é uma estimativa inicial baseada na área e padrão selecionado. O orçamento final pode variar conforme especificações do projeto, materiais escolhidos e condições do terreno. Entre em contato para uma avaliação detalhada.</p>
            </div>
          </div>
        </form>
      </div>
    </div>
  </section>

  <!-- footer -->
  <?php include('../front/footer.php'); ?>

  <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
  <script src="../js/script.js"></script>
  <script src="../js/orcamento.js"></script>
  <script src="../js/usuario.js"></script>
  <script src="../js/logado.js"></script>

</body>

</html>