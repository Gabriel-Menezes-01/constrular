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
  <link rel="stylesheet" href="../css/sobre.css">
  <link rel="stylesheet" href="../css/logado.css">

  <title>ConstruLar</title>
</head>

<body>

  <!-- nav bar -->
  <header class="tod-cont">
    <h1><a href="./Inicio.html">Constru<span class="lar" >Lar</span</a></h1>
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
          <span class="list"><?php
                              echo " $nome  $apelido";
                              ?></span>
        </li>

        <li class="nav__item">
          <span class="list">list</span>
        </li>

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
            echo '<form action="sobre.php" method="post" enctype="multipart/form-data" class="upload-img">';
            echo '<input type="hidden" name="current_image" value="' . $image . '">';
            echo '<input type="file" name="imagem" class="btn_img" required>';
            echo '<button type="submit">Substituir</button>';
            echo '</form>';

            echo '<form action="sobre.php" method="post" class="delete-img">';
            echo '<input type="hidden" name="image_to_delete" value="' . $image . '">';
            echo '<button type="submit" class="delete-button">Deletar</button>';
            echo '</form>';
            echo '</div>';
          }

            // Manipulação automática de substituição de imagem
          if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['imagem'])) {
            $currentImage = $_POST['current_image'];
            $newImage = $_FILES['imagem'];

            // Verifique se o arquivo enviado é válido
            if ($newImage['error'] === UPLOAD_ERR_OK) {
              $newImagePath = $directory . basename($newImage['name']);

              // Move the new image to the directory
              if (move_uploaded_file($newImage['tmp_name'], $newImagePath)) {
                // Replace the old image with the new one
                if (file_exists($currentImage)) {
                  unlink($currentImage);
                }

                // Move the new image to the location of the old image
                copy($newImagePath, $currentImage);

                // Delete the temporary uploaded file
                unlink($newImagePath);
              }
            }
          }

          // Automatically delete excess images to maintain a maximum of 6
          $allImages = glob($directory . "*.{jpg,jpeg,png,gif}", GLOB_BRACE);
          if (count($allImages) > 6) {
            $excessImages = array_slice($allImages, 6);
            foreach ($excessImages as $excessImage) {
              unlink($excessImage); // Delete the excess image
            }
          }
          ?>
        </div>
        <form action="sobre.php" method="post" enctype="multipart/form-data" class="add-img-form">
          <input type="file" name="new_image" class="btn_img" required>
          <button type="submit" class="add-button">Adicionar Imagem</button>
        </form>
        <?php
        // Handle new image upload
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['new_image'])) {
          $newImage = $_FILES['new_image'];

          // Check if the uploaded file is valid
          if ($newImage['error'] === UPLOAD_ERR_OK) {
            $newImagePath = $directory . basename($newImage['name']);

            // Move the new image to the directory
            if (move_uploaded_file($newImage['tmp_name'], $newImagePath)) {
              // Ensure the total number of images does not exceed 6
              $allImages = glob($directory . "*.{jpg,jpeg,png,gif}", GLOB_BRACE);
              if (count($allImages) > 6) {
                $excessImages = array_slice($allImages, 6);
                foreach ($excessImages as $excessImage) {
                  unlink($excessImage); // Delete the excess image
                }
              }
            }
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
</body>

</html>