<!DOCTYPE html>
<html lang="pt">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
  <link rel="stylesheet" href="../css/styles.css">
  <link rel="stylesheet" href="../css/sobre.css">

  <title>ConstruLar</title>
</head>

<body>

  <!-- nav bar -->
  <?php include_once "../front/header.php" ?>
  
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
              <img src="../img/imgSobre/1.jpg" alt="Imagem 2" class="gallery-item">
              <img src="../img/imgSobre/2.jpg" alt="Imagem 4" class="gallery-item">
              <img src="../img/imgSobre/3.jpeg" alt="Imagem 5" class="gallery-item">
              <img src="../img/imgSobre/4.jpg" alt="Imagem 6" class="gallery-item">
              <img src="../img/imgSobre/5.jpg" alt="Imagem 7" class="gallery-item">
              <img src="../img/imgSobre/6.jpeg" alt="Imagem 8" class="gallery-item">
            
          </div>

          <div class="model">
            <span class="close" >&times;</span>
            <img  src="../img/imgSobre/casa1.jpg" class="modal-content" alt="">
          </div>
      </div>
    </section>
  
  <!-- rodape -->
  <?php include_once "../front/footer.php" ?>

  <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
  <script src="../js/script.js"></script>
  <script src="../js/usuario.js"></script>
  <script src="../js/logado.js"></script>
</body>

</html>