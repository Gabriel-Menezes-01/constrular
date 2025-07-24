<!DOCTYPE html>
<html lang="pt">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
  <link rel="stylesheet" href="../css/styles.css">
  <link rel="stylesheet" href="../css/sobre.css">

  <title>Sobre Nós - ConstruLar</title>
</head>

<body>

  <!-- nav bar -->
  <?php include("./header.php"); ?>
  
  <!-- Seção Hero -->
  <section class="hero-sobre">
    <div class="hero-content">
      <h1><i class="bi bi-building"></i> Sobre a ConstruLar</h1>
      <p>Transformando sonhos em realidade há mais de 20 anos</p>
    </div>
  </section>
  
  <!-- Seção Principal -->
  <section class="sobre-main">
    <div class="container">
      
      <!-- Cards de Informações -->
      <div class="info-cards">
        <div class="info-card" data-aos="fade-up" data-aos-delay="100">
          <div class="card-icon">
            <i class="bi bi-people-fill"></i>
          </div>
          <div class="card-content">
            <h3>O QUE NOS DEFINE?</h3>
            <p>Somos uma empresa que se associa aos nossos clientes na procura de soluções, pautados pela seriedade e rigor, sempre com a vontade de fazer mais e melhor em busca de um serviço personalizado.</p>
          </div>
        </div>

        <div class="info-card" data-aos="fade-up" data-aos-delay="200">
          <div class="card-icon">
            <i class="bi bi-house-gear"></i>
          </div>
          <div class="card-content">
            <h3>O NOSSO ESPAÇO!</h3>
            <p>Na construtora encontrará um espaço diversificado, onde poderá adquirir vários tipos de produtos e gamas. Madeiras, Tintas, Argamassas, Material elétrico, Ferragens e Acessórios, bem como um Showroom preparado para o receber de forma personalizada.</p>
          </div>
        </div>

        <div class="info-card" data-aos="fade-up" data-aos-delay="300">
          <div class="card-icon">
            <i class="bi bi-heart-fill"></i>
          </div>
          <div class="card-content">
            <h3>100% FOCADA NO CLIENTE</h3>
            <p>Fazemos o nosso melhor para responder e atender os nossos clientes da melhor maneira possível, apresentando um serviço de proximidade ao cliente com um leque de soluções disponíveis para solucionar as suas necessidades.</p>
          </div>
        </div>
      </div>

      <!-- Estatísticas -->
      <div class="stats-section">
        <div class="stats-container">
          <div class="stat-item" data-count="20">
            <div class="stat-number">0</div>
            <div class="stat-label">Anos de Experiência</div>
          </div>
          <div class="stat-item" data-count="500">
            <div class="stat-number">0</div>
            <div class="stat-label">Projetos Concluídos</div>
          </div>
          <div class="stat-item" data-count="1000">
            <div class="stat-number">0</div>
            <div class="stat-label">Clientes Satisfeitos</div>
          </div>
          <div class="stat-item" data-count="50">
            <div class="stat-number">0</div>
            <div class="stat-label">Profissionais</div>
          </div>
        </div>
      </div>

    </div>
  </section>

  <!-- Galeria -->
  <section class="galeria-section">
    <div class="container">
      <div class="galeria-header">
        <h2><i class="bi bi-images"></i> Nossa Galeria</h2>
        <p>Conheça alguns dos nossos projetos realizados</p>
      </div>

      <!-- Filtros -->
      <div class="gallery-filters">
        <button class="filter-btn active" data-filter="all">
          <i class="bi bi-grid-3x3-gap"></i> Todos
        </button>
        <button class="filter-btn" data-filter="residencial">
          <i class="bi bi-house"></i> Residencial
        </button>
        <button class="filter-btn" data-filter="comercial">
          <i class="bi bi-building"></i> Comercial
        </button>
        <button class="filter-btn" data-filter="reforma">
          <i class="bi bi-tools"></i> Reformas
        </button>
      </div>

      <!-- Grid da Galeria -->
      <div class="gallery-grid">
        <div class="gallery-item" data-category="residencial">
          <img src="../img/imgSobre/1.jpg" alt="Projeto Residencial 1">
          <div class="item-overlay">
            <div class="overlay-content">
              <h4>Casa Residencial</h4>
              <p>Projeto moderno com acabamentos de luxo</p>
              <button class="view-btn" data-image="../img/imgSobre/1.jpg">
                <i class="bi bi-eye"></i>
              </button>
            </div>
          </div>
        </div>

        <div class="gallery-item" data-category="comercial">
          <img src="../img/imgSobre/2.jpg" alt="Projeto Comercial 1">
          <div class="item-overlay">
            <div class="overlay-content">
              <h4>Edifício Comercial</h4>
              <p>Arquitetura corporativa inovadora</p>
              <button class="view-btn" data-image="../img/imgSobre/2.jpg">
                <i class="bi bi-eye"></i>
              </button>
            </div>
          </div>
        </div>

        <div class="gallery-item" data-category="residencial">
          <img src="../img/imgSobre/3.jpeg" alt="Projeto Residencial 2">
          <div class="item-overlay">
            <div class="overlay-content">
              <h4>Sobrado Moderno</h4>
              <p>Design contemporâneo e funcional</p>
              <button class="view-btn" data-image="../img/imgSobre/3.jpeg">
                <i class="bi bi-eye"></i>
              </button>
            </div>
          </div>
        </div>

        <div class="gallery-item" data-category="reforma">
          <img src="../img/imgSobre/4.jpg" alt="Reforma 1">
          <div class="item-overlay">
            <div class="overlay-content">
              <h4>Reforma Completa</h4>
              <p>Transformação total do espaço</p>
              <button class="view-btn" data-image="../img/imgSobre/4.jpg">
                <i class="bi bi-eye"></i>
              </button>
            </div>
          </div>
        </div>

        <div class="gallery-item" data-category="comercial">
          <img src="../img/imgSobre/5.jpg" alt="Projeto Comercial 2">
          <div class="item-overlay">
            <div class="overlay-content">
              <h4>Centro Comercial</h4>
              <p>Espaço multifuncional moderno</p>
              <button class="view-btn" data-image="../img/imgSobre/5.jpg">
                <i class="bi bi-eye"></i>
              </button>
            </div>
          </div>
        </div>

        <div class="gallery-item" data-category="residencial">
          <img src="../img/imgSobre/6.jpeg" alt="Projeto Residencial 3">
          <div class="item-overlay">
            <div class="overlay-content">
              <h4>Casa de Campo</h4>
              <p>Integração com a natureza</p>
              <button class="view-btn" data-image="../img/imgSobre/6.jpeg">
                <i class="bi bi-eye"></i>
              </button>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- Modal da Galeria -->
  <div class="gallery-modal" id="galleryModal">
    <div class="modal-overlay">
      <div class="modal-content">
        <button class="modal-close">
          <i class="bi bi-x-lg"></i>
        </button>
        <img id="modalImage" src="" alt="">
        <div class="modal-nav">
          <button class="nav-btn prev-btn">
            <i class="bi bi-chevron-left"></i>
          </button>
          <button class="nav-btn next-btn">
            <i class="bi bi-chevron-right"></i>
          </button>
        </div>
      </div>
    </div>
  </div>
  
  <!-- rodape -->
  <?php include("./footer.php"); ?>

  <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
  <script src="../js/script.js"></script>
  <script src="../js/sobre.js"></script>
  <script src="../js/usuario.js"></script>
  <script src="../js/logado.js"></script>
</body>

</html>