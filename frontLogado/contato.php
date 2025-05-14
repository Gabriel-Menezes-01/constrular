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
  <link rel="stylesheet" href="../css/contatos.css">
  <link rel="stylesheet" href="../css/styles.css">
  <link rel="stylesheet" href="../css/logado.css">
  <link rel="stylesheet" href="../css/list.css">
  <title>Contatos</title>
</head>
<body>
    <!-- nav bar -->
    <header class="tod-cont">
      <h1>
        Constru<span>Lar</span>
      </h1>
      <nav>
        <a href="./Inicio.php">INICIO</a>
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

    <section>
      <div class="contatos">
        <div class="envio-contato">
            <iframe class="map" src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3109.809644980234!2d-9.38311272434183!3d38.79099797174691!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0xd1edabb102a4ca3%3A0x59c8f3a91b6f95df!2s2710-512%20Sintra!5e0!3m2!1spt-BR!2spt!4v1733683100032!5m2!1spt-BR!2spt" width="500" height="350" style="border:0; margin: 0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>

            <form>

            <h3>Entra em Contato</h3>

            <div class="input-contatos">
                <label for="nome">Nome</label>
                <input type="text" name="nome" placeholder="" required>
                <i class="bi bi-person"></i>
            </div>

            <div class="input-contatos">
                <label for="email">E-mail</label>
                <input type="text" name="email" placeholder="">
                <i class="bi bi-envelope"></i>
                
            </div>

            <div class="input-contatos">
                <label for="telefone">Telefone</label>
                <input type="text" name="telefone" placeholder="">
                <i class="bi bi-phone" ></i> 
            </div>
            
            <div class="input-button">
                <button>ENVIAR</button>
            </div>
            <div >
                <p class="enviar-pedido">Por favor, deixe suas informações para que possamos entra em contato.</p>
            </div>

            </form>
        </div>
      </div>

    </section>

      <!-- rodape -->
  <footer>
    <div class="conteudo-footer">
      <h2>
        Constru<span>Lar</span>
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