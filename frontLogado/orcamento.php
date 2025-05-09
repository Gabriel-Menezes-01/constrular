<?php
session_start();
include '../backend/conexao.php';

if (!isset($_SESSION['email']) || !isset($_SESSION['senha'])) {
  header('Location: ../front /Inicio.php');
  exit;
}

$email = $_SESSION['email'];

$query = "SELECT nome, apelido FROM usuario WHERE email = :email";
$stmt = $conn->prepare($query);
$stmt->bindParam(':email', $email, PDO::PARAM_STR);
$stmt->execute();
$result = $stmt->fetch(PDO::FETCH_ASSOC);

if ($result) {
  $nome = $result['nome'];
  $apelido = $result['apelido'];
} else {
  $nome = 'Usuario';
}

?>
<!DOCTYPE html>
<html lang="pt">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
  <link rel="stylesheet" href="../css/styles.css">
  <link rel="stylesheet" href="../css/orcamento.css">
  <link rel="stylesheet" href="../css/logado.css">
  <link rel="stylesheet" href="../css/list.css">
  <title>Orçamento</title>
</head>

<body class="img-orcamento">
  <header class="tod-cont">
    <h1>
      <h1><a href="./Inicio.html">Constru<span class="lar" >Lar</span</a></h1>
    </h1>
    <nav>
      <a href="./Inicio.php" >INICIO</a>
      <a href="./orcamento.php">ORÇAMENTO</a>
      <a href="./contato.php">CONTATOS</a>
      <a href="./sobre.php">SOBRE</a>
    </nav>
    <button class="menu" id="menu">
      <i class="bi bi-person-circle"></i>
    </button>

    <div class="nav__menu" id="nav-menu">
      <ul class="nav__list">

      <li class="nav__item">
          <span class="list">
            <?php
              echo " $nome  $apelido";
            ?>
          </span>
        </li>

        <li class="nav__item">
          <span class="list" id="show-users">
            Ver Usuários
          </span>
        </li>

        <div id="user-modal" class="modal" style="display: none;">
          <div class="modal-content">
            <span class="close-modal" id="close-modal">&times;</span>
            <h2>Lista de Usuários</h2>
            <ul id="user-list">
              <?php
              $userQuery = "SELECT nome, email FROM usuario";
              $userStmt = $conn->prepare($userQuery);
              $userStmt->execute();
              $users = $userStmt->fetchAll(PDO::FETCH_ASSOC);

              foreach ($users as $user) {
          echo "<li>" . htmlspecialchars($user['nome']) . " - " . htmlspecialchars($user['email']) . "</li>";
              }
              ?>
            </ul>
          </div>
        </div>

        <li class="nav__item">
          <span class="list" id="sair">Sair</span>

        </li>
      </ul>

      <!-- Close button -->
      <button class="nav__close" id="nav-close">
        <i class="bi bi-x-circle"></i>
      </button>

    </div>

  </header>


  <section class="orcamento">
    
    <div class="dados">
        
        <div class="inputs"> 
          <h2>Orçamento</h2>
          
            <div class="info">
              <label for="name">Nome Completo</label>
              <input type="text" name="name" id="nome">

              <label for="telefone">Telefone</label>
              <input type="tel" name="telefone" id="telefone">
            </div>
            

            <div class="info">
              <label for="largura">Largura Terreno</label>
              <input type="text" name="largura" id="largura">
              
              <label for="comprimento">Comprimento Terreno</label>
              <input type="text" name="comrpimento" id="comprimento">
            </div>
            <div class="val">
              <span>Por favor, preencha todos os campos com valores válidos</span>
            </div>
            
            <button class="btn-resultado">Calcular</button>
            <p class="valor">Valor de 350€ por m2.</p> 
        
            <div class="result"></div>
            <div class="obs">
          <p>Observação:O valor apresentado neste orçamento não representa o custo total da obra. Os materiais de construção não estão inclusos nesta estimativa inicial.</p>
        </div>
        </div>
        
        
    </div>
    
    
  </section>







  <footer>
    <div class="conteudo-footer">
      <h2>
        Constru<span class="lar">Lar</span>
      </h2>
      <div class="items-contatos">
        <i class="bi bi-facebook"> ConstruLar</i>
        <i class="bi bi-instagram"> @ConstruLar_oficial</i>
        <i class="bi bi-telephone"> 123 456 789</i>
        <i class="bi bi-envelope"> construla@gmail.com</i>
      </div>
      <div></div>
  </footer>

  <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
  <script src="../js/script.js"></script>
  <script src="../js/usuario.js"></script>
  <script src="../js/list.js"></script>
  <script src="../admin/autorizacao.js"></script>
</body>

</html>