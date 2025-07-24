
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

  <?php include('./header.php'); ?>
  
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
<section class="projetos">
  <
  <div class="alert-login" style="margin-top: 30px; padding: 15px; background: #ffe5e5; border: 1px solid #ffb3b3; border-radius: 5px; color: #b30000;">
    Para criar um projeto ou agendar uma visita com nossos engenheiros, é necessário fazer login.
    <a href="../login/login.php" style="color: #b30000; text-decoration: underline; margin-left: 5px;">Clique aqui para acessar</a>
  </div>
</section>
  <!-- rodape -->
  <?php include('./footer.php'); ?>

  <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
  <script src="../js/noticias.js"></script>
  <script src="../js/script.js"></script>
  <script src="../js/logado.js"></script>
  <script src="../js/footer.js"></script>


  
</body>

</html>