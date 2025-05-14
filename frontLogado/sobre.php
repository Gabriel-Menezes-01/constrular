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
  <link rel="stylesheet" href="../css/sobre.css">
  <link rel="stylesheet" href="../css/logado.css">
  <link rel="stylesheet" href="../css/list.css">


  <title>ConstruLar</title>
</head>

<body>

  <!-- nav bar -->
  <header class="tod-cont">
    <h1><a href="./Inicio.html">Constru<span class="lar" >Lar</span></a></h1>
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
  <section class="sobre">
    
    <div class="content-sobre">
      <div class="txt-sobre">
        <h3> O QUE NOS DEFINE?</h3>
        <p>Somos uma empresa que se associa aos nosso cliente na procura de soluções, pautados pela seriedade e rigor, sempre com a vontade de fazer mais e melhor em busca de um serviço personalizado</p>
      </div>

      <div class="txt-sobre">
        <h3>O NOSSO ESPAÇO!</h3>
        <p>Na construtora encontrará um espaço diversificado, onde poderá adquirir vários tipos de produtos e gamas. Madeiras, Tintas, Argamassas. Material elétrico, Ferragens e Acessórios, bem como um Showroom preparado para o receber de forma personalizada.</p>
      </div>

      <div class="txt-sobre">
        <h3>100% FOCADA NO CLIENTE</h3>
        <p>Fazemos o nosso melhor para responder e atender os nossos clientes da melhor maneira possível, apresentando um serviço de proximidade ao cliente com um leque de soluções disponíveis para solucionar as suas necessidades.</p>
      </div>
    </div>
  </section>  

    <section class="galeria">
      <div>
        <div class="gallery">
          <?php
          $directory = '../img/imgSobre/';
          $images = glob($directory . "*.{jpg,jpeg,png,gif}", GLOB_BRACE);

          // Limit the number of images to 6
          $images = array_slice($images, 0, 6);

          foreach ($images as $image) {
            echo '<div class="gallery-item-container">';
            echo '<img src="' . $image . '" alt="Imagem" class="gallery-item">';
            echo '<form action="sobre.php" method="post" enctype="multipart/form-data" class="upload-img" id="img-modal" style="display: none;">';
            echo '<input type="hidden" name="current_image" value="' . $image . '">';
            echo '<input type="file" name="imagem" class="btn_img" required>';
            echo '<button type="submit">Substituir</button>';
            echo '</form>';
            echo '</div>';
          }

        
          if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['imagem'])) {
            $currentImage = $_POST['current_image'];
            $newImage = $_FILES['imagem'];

           
            if ($newImage['error'] === UPLOAD_ERR_OK) {
              $newImagePath = $directory . basename($newImage['name']);

              
              if (move_uploaded_file($newImage['tmp_name'], $newImagePath)) {
               
                if (file_exists($currentImage)) {
                  unlink($currentImage);
                }

              
                copy($newImagePath, $currentImage);

              
                unlink($newImagePath);
              }
            }
          }

          
          $allImages = glob($directory . "*.{jpg,jpeg,png,gif}", GLOB_BRACE);
          if (count($allImages) > 6) {
            $excessImages = array_slice($allImages, 6);
            foreach ($excessImages as $excessImage) {
              unlink($excessImage);
            }
          }
          ?>
        </div>
   
        <div class="model">
          <span class="close">&times;</span>
          <img src="" class="modal-content" alt="">
        </div>
      </div>
    </section>

  
  
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
  <script src="../js/script.js"></script>
  <script src="../js/usuario.js"></script>
  <script src="../js/list.js"></script>
  <script src="../admin/autorizacao.js"></script>
</body>

</html>