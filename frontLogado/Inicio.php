<?php
session_start();
include '../backend/conexao.php';

if (!isset($_SESSION['email']) || !isset($_SESSION['senha'])) {
  header('Location: ../front/Inicio.php');
  exit;
}
$email = $_SESSION['email'];

 

// Consulta o usuário logado
$query = "SELECT nome, apelido, email FROM usuario WHERE email = :email";
$stmt = $conn->prepare($query);
$stmt->bindParam(':email', $email);
$stmt->execute();
$resultado = $stmt->fetch(PDO::FETCH_ASSOC);
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
  <link rel="stylesheet" href="../css/logado.css">
  <link rel="stylesheet" href="../css/list.css">

  <title>ConstruLar</title>
</head>

<body>

  <!-- nav bar -->
  <header class="tod-cont">
    <h1><a href="./Inicio.html">Constru<span class="lar">Lar</span></a>
    </h1>
    <nav>
      <a href="./Inicio.php">INÍCIO</a>
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
          <span class="list users" id="show-users" style="display: none;">
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

  <!-- imagem com texto -->
  <section class="conteudo">

    <div class="conte-inicio">
      <h2 class="">Realizando Sonhos</h2>
      <p class="text">
        A Construtora Contrular, com mais de 20 anos de experiência, é especialista em transformar sonhos em realidade.
        Nosso compromisso é construir mais do que casas, é construir lares. Oferecemos soluções completas, desde o
        projeto arquitetônico até a entrega das chaves, com qualidade, segurança e respeito aos prazos. Nossa equipe de
        profissionais altamente qualificados está pronta para te ajudar a construir o futuro que você deseja.
      </p>
    </div>
  </section>

  <!-- reels de noticias -->
  <aside class="noticias">
    <div class="swiper-container">

      <div class="slid-conteudo" id="conteine"></div>

    </div>
  </aside>

  <!-- rodape -->
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
  <script src="../js/noticias.js"></script>
  <script src="../js/script.js"></script>
  <script src="../js/usuario.js"></script>
  <script src="../js/list.js"></script>
  


</body>

</html>