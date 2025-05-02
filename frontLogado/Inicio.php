<?php 
    session_start();
  
    include_once('../backend/usuarios.php');
    // Verifica se o usuário está logado
    if (!isset($_SESSION['email']) || !isset($_SESSION['senha'])) {
      unset($_SESSION['email']);
      unset($_SESSION['senha']);
        header("Location: ./Inicio.php");
        exit;
    }
    
    $email = $_SESSION['email'];
    
    // Consulta SQL para buscar o usuário pelo email
    $mysql = "SELECT nome, apelido FROM user WHERE email = '$email'";
    $Result = $conect->query($mysql);
    
    // Verifica se encontrou o usuário
    if ($Result && mysqli_num_rows($Result) > 0) {
        $user = mysqli_fetch_assoc($Result);
        $nome = $user['nome'];
        $apelido = $user['apelido'];
    } else {
        echo "Usuário não encontrado.";
        exit;
    }
    
    
?>
<!DOCTYPE html>
<html lang="pt">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
  <link rel="stylesheet" href="../css/styles.css">
  <link rel="stylesheet" href="../css/logado.css">
  <title>ConstruLar</title>
</head>

<body>

  <!-- nav bar -->
  <header class="tod-cont">
    <h1><a href="./Inicio.html">Constru<span class="lar" >Lar</span</a></h1>
    <nav>
      <a href="./Inicio.php">INÍCIO</a>
      <a href="./orcamento.php">ORÇAMENTO</a>
      <a href="./contato.php">CONTATOS</a>
      <a href="./sobre.php">SOBRE</a>
    </nav>

    <button class="menu" id="menu"  >
      <i class="bi bi-person-circle"></i>
    </button>
   
    <div class="nav__menu" id="nav-menu">
      <ul class="nav__list">

          <li class="nav__item">
              <span><?php
              
              ?></span>
          </li>

          <li class="nav__item">
              <span>list</span>
          </li>

          <li class="nav__item">
              <span>Sair</span>
          
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

      <div class="slid-conteudo" id="conteine" ></div>

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
  
  
</body>

</html>