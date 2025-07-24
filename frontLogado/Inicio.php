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
  
  <title>ConstruLar</title>
</head>

<body>

  <?php include('./header2.php'); ?>
  
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

  <!-- reels de noticias -->
  <aside class="noticias">
    <div class="swiper-container">

      <div class="slid-conteudo" id="conteine" ></div>

    </div>
  </aside>
  </section>
  <!-- projetos -->
  <?php
    if ($email === 'admin@gmail.com') {
      include('../admin/gerenciar_projetos.php');
    } else {
      include('./projetos.php');
    }
  ?>

  <!-- rodape -->
  <?php include('../front/footer.php'); ?>

  <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
  <script src="../js/noticias.js"></script>
  <script src="../js/script.js"></script>
  <script src="../js/logado.js"></script>
  <script src="../js/footer.js"></script>
  <script src="../js/list.js"></script>

</body>

</html>