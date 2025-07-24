<?php
// filepath: c:\wamp64\www\constrular\frontLogado\sobre.php
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

// Processar upload de imagens
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['imagem']) && $email === 'admin@gmail.com') {
  $response = ['success' => false, 'message' => ''];
  
  try {
    $currentImage = $_POST['current_image'] ?? '';
    $newImage = $_FILES['imagem'];
    $directory = '../img/imgSobre/';

    // Validar arquivo
    $allowedTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif'];
    $maxSize = 5 * 1024 * 1024; // 5MB

    if (!in_array($newImage['type'], $allowedTypes)) {
      throw new Exception('Tipo de arquivo não permitido. Use JPG, PNG ou GIF.');
    }

    if ($newImage['size'] > $maxSize) {
      throw new Exception('Arquivo muito grande. Máximo 5MB.');
    }

    if ($newImage['error'] !== UPLOAD_ERR_OK) {
      throw new Exception('Erro no upload do arquivo.');
    }

    // Gerar nome único
    $extension = pathinfo($newImage['name'], PATHINFO_EXTENSION);
    $filename = 'gallery_' . time() . '_' . rand(1000, 9999) . '.' . $extension;
    $newPath = $directory . $filename;

    // Mover arquivo
    if (move_uploaded_file($newImage['tmp_name'], $newPath)) {
      // Remover imagem antiga se existir
      if (!empty($currentImage) && file_exists($currentImage)) {
        unlink($currentImage);
      }
      
      $response['success'] = true;
      $response['message'] = 'Imagem alterada com sucesso!';
      $response['new_image'] = $newPath;
    } else {
      throw new Exception('Erro ao salvar o arquivo.');
    }

  } catch (Exception $e) {
    $response['message'] = $e->getMessage();
  }

  // Retornar resposta AJAX
  if (isset($_POST['ajax'])) {
    header('Content-Type: application/json');
    echo json_encode($response);
    exit;
  }
}

// API para buscar email logado
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['get_logged_email']) && $_POST['get_logged_email'] == '1') {
  header('Access-Control-Allow-Origin: *');
  header('Content-Type: application/json');
  echo json_encode(['email' => $email]);
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
  <link rel="stylesheet" href="../css/sobre.css">

  <title>Sobre Nós - ConstruLar</title>
</head>

<body>

  <!-- nav bar -->
  <?php include('./header2.php'); ?>

  <!-- Indicador de Admin -->
  <div class="admin-indicator" id="adminIndicator">
    <i class="bi bi-shield-check"></i> Modo Administrador
  </div>

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
            <p>Somos uma empresa que se associa aos nosso cliente na procura de soluções, pautados pela seriedade e rigor, sempre com a vontade de fazer mais e melhor em busca de um serviço personalizado</p>
          </div>
        </div>

        <div class="info-card" data-aos="fade-up" data-aos-delay="200">
          <div class="card-icon">
            <i class="bi bi-house-gear"></i>
          </div>
          <div class="card-content">
            <h3>O NOSSO ESPAÇO!</h3>
            <p>Na construtora encontrará um espaço diversificado, onde poderá adquirir vários tipos de produtos e gamas. Madeiras, Tintas, Argamassas. Material elétrico, Ferragens e Acessórios, bem como um Showroom preparado para o receber de forma personalizada.</p>
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
        <?php
        $directory = '../img/imgSobre/';
        $images = glob($directory . "*.{jpg,jpeg,png,gif}", GLOB_BRACE);

        // Limit the number of images to 6
        $images = array_slice($images, 0, 6);

        $categories = ['residencial', 'comercial', 'residencial', 'reforma', 'comercial', 'residencial'];
        $titles = [
          'Casa Residencial',
          'Edifício Comercial',
          'Sobrado Moderno',
          'Reforma Completa',
          'Centro Comercial',
          'Casa de Campo'
        ];
        $descriptions = [
          'Projeto moderno com acabamentos de luxo',
          'Arquitetura corporativa inovadora',
          'Design contemporâneo e funcional',
          'Transformação total do espaço',
          'Espaço multifuncional moderno',
          'Integração com a natureza'
        ];

        foreach ($images as $index => $image) {
          $category = $categories[$index] ?? 'residencial';
          $title = $titles[$index] ?? 'Projeto';
          $description = $descriptions[$index] ?? 'Descrição do projeto';

          echo '<div class="gallery-item" data-category="' . $category . '" data-image-path="' . $image . '">';
          echo '<img src="' . $image . '" alt="' . $title . '">';
          echo '<div class="item-overlay">';
          echo '<div class="overlay-content">';
          echo '<h4>' . $title . '</h4>';
          echo '<p>' . $description . '</p>';
          echo '<button class="view-btn" data-image="' . $image . '">';
          echo '<i class="bi bi-eye"></i>';
          echo '</button>';
          echo '</div>';
          echo '</div>';

          // Controles de admin (visíveis apenas para admin)
          echo '<div class="admin-controls">';
          echo '<button class="btn-edit-image" data-image="' . $image . '" data-title="' . $title . '">';
          echo '<i class="bi bi-pencil"></i>';
          echo '</button>';
          echo '</div>';

          echo '</div>';
        }

        // Limpeza de imagens excessivas
        $allImages = glob($directory . "*.{jpg,jpeg,png,gif}", GLOB_BRACE);
        if (count($allImages) > 6) {
          $excessImages = array_slice($allImages, 6);
          foreach ($excessImages as $excessImage) {
            if (file_exists($excessImage)) {
              unlink($excessImage);
            }
          }
        }
        ?>
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

  <!-- Modal de Upload de Imagem -->
  <div class="image-upload-modal" id="imageUploadModal">
    <div class="upload-modal-content">
      <button class="close-upload-modal" id="closeUploadModal">
        <i class="bi bi-x-lg"></i>
      </button>
      
      <div class="upload-modal-header">
        <h3><i class="bi bi-image"></i> Alterar Imagem</h3>
        <p>Selecione uma nova imagem para substituir a atual</p>
      </div>

      <img id="currentImagePreview" class="current-image-preview" src="" alt="Imagem atual">

      <div class="upload-area" id="uploadArea">
        <div class="upload-icon">
          <i class="bi bi-cloud-upload"></i>
        </div>
        <div class="upload-text">
          <h4>Clique ou arraste uma imagem aqui</h4>
          <p>JPG, PNG ou GIF - Máximo 5MB</p>
        </div>
      </div>

      <input type="file" id="imageInput" accept="image/*">
      
      <div class="upload-progress" id="uploadProgress">
        <div class="upload-progress-bar" id="uploadProgressBar"></div>
      </div>

      <div class="upload-actions">
        <button class="btn-cancel" id="cancelUpload">Cancelar</button>
        <button class="btn-upload" id="confirmUpload" disabled>
          <i class="bi bi-upload"></i>
          Alterar Imagem
        </button>
      </div>
    </div>
  </div>

  <!-- rodape -->
  <?php include("../front/footer.php"); ?>

  <script>
    let isAdmin = false;
    let currentEditingImage = null;

    function buscarEmailLogado(callback) {
      fetch(window.location.pathname, {
          method: 'POST',
          headers: {
            'Content-Type': 'application/x-www-form-urlencoded'
          },
          body: 'get_logged_email=1'
        })
        .then(response => response.json())
        .then(data => {
          if (callback) callback(data.email);
        })
        .catch(error => {
          console.error('Erro ao buscar email:', error);
        });
    }

    function showNotification(message, type = 'success') {
      const notification = document.createElement('div');
      notification.className = `notification ${type}`;
      notification.innerHTML = `
        <div style="display: flex; align-items: center; gap: 10px;">
          <i class="bi bi-${type === 'success' ? 'check-circle' : 'exclamation-triangle'}"></i>
          <span>${message}</span>
        </div>
      `;
      
      document.body.appendChild(notification);
      
      setTimeout(() => notification.classList.add('show'), 100);
      
      setTimeout(() => {
        notification.classList.remove('show');
        setTimeout(() => notification.remove(), 300);
      }, 3000);
    }

    function initAdminMode() {
      if (!isAdmin) return;
      
      // Mostrar indicador de admin
      document.getElementById('adminIndicator').classList.add('show');
      
      // Mostrar controles de admin
      document.querySelectorAll('.admin-controls').forEach(control => {
        control.style.display = 'block';
      });

      // Event listeners para botões de edição
      document.querySelectorAll('.btn-edit-image').forEach(btn => {
        btn.addEventListener('click', function(e) {
          e.stopPropagation();
          const imagePath = this.dataset.image;
          const title = this.dataset.title;
          openImageUploadModal(imagePath, title);
        });
      });
    }

    function openImageUploadModal(imagePath, title) {
      currentEditingImage = imagePath;
      const modal = document.getElementById('imageUploadModal');
      const preview = document.getElementById('currentImagePreview');
      
      preview.src = imagePath;
      preview.alt = title;
      
      modal.classList.add('show');
      document.body.style.overflow = 'hidden';
    }

    function closeImageUploadModal() {
      const modal = document.getElementById('imageUploadModal');
      modal.classList.remove('show');
      document.body.style.overflow = 'auto';
      
      // Reset form
      document.getElementById('imageInput').value = '';
      document.getElementById('confirmUpload').disabled = true;
      currentEditingImage = null;
    }

    function uploadImage() {
      const fileInput = document.getElementById('imageInput');
      const file = fileInput.files[0];
      
      if (!file || !currentEditingImage) return;
      
      const formData = new FormData();
      formData.append('imagem', file);
      formData.append('current_image', currentEditingImage);
      formData.append('ajax', '1');
      
      const uploadBtn = document.getElementById('confirmUpload');
      const progressContainer = document.getElementById('uploadProgress');
      const progressBar = document.getElementById('uploadProgressBar');
      
      uploadBtn.disabled = true;
      uploadBtn.innerHTML = '<i class="bi bi-arrow-repeat" style="animation: spin 1s linear infinite;"></i> Enviando...';
      progressContainer.style.display = 'block';
      
      // Simular progresso
      let progress = 0;
      const progressInterval = setInterval(() => {
        progress += Math.random() * 15;
        if (progress > 90) progress = 90;
        progressBar.style.width = progress + '%';
      }, 100);
      
      fetch(window.location.pathname, {
        method: 'POST',
        body: formData
      })
      .then(response => response.json())
      .then(data => {
        clearInterval(progressInterval);
        progressBar.style.width = '100%';
        
        if (data.success) {
          // Atualizar imagem na galeria
          const galleryItem = document.querySelector(`[data-image-path="${currentEditingImage}"]`);
          if (galleryItem) {
            const img = galleryItem.querySelector('img');
            const viewBtn = galleryItem.querySelector('.view-btn');
            const editBtn = galleryItem.querySelector('.btn-edit-image');
            
            // Adicionar timestamp para forçar reload
            const newSrc = data.new_image + '?t=' + new Date().getTime();
            img.src = newSrc;
            viewBtn.dataset.image = data.new_image;
            editBtn.dataset.image = data.new_image;
            galleryItem.dataset.imagePath = data.new_image;
          }
          
          showNotification(data.message, 'success');
          closeImageUploadModal();
        } else {
          showNotification(data.message, 'error');
        }
      })
      .catch(error => {
        clearInterval(progressInterval);
        console.error('Erro:', error);
        showNotification('Erro ao enviar imagem', 'error');
      })
      .finally(() => {
        uploadBtn.disabled = false;
        uploadBtn.innerHTML = '<i class="bi bi-upload"></i> Alterar Imagem';
        progressContainer.style.display = 'none';
        progressBar.style.width = '0%';
      });
    }

    // Inicializar quando o DOM estiver pronto
    document.addEventListener('DOMContentLoaded', function() {
      // Buscar email do usuário logado
      buscarEmailLogado(function(email) {
        isAdmin = (email === 'admin@gmail.com');
        
        if (isAdmin) {
          initAdminMode();
        }
        
        // Mostrar/ocultar elementos de usuário
        var showUsersElems = document.querySelectorAll('.users');
        showUsersElems.forEach(function(elem) {
          elem.style.display = isAdmin ? 'block' : 'none';
        });
      });
      
      // Event listeners para o modal de upload
      document.getElementById('closeUploadModal').addEventListener('click', closeImageUploadModal);
      document.getElementById('cancelUpload').addEventListener('click', closeImageUploadModal);
      
      // Upload area drag & drop
      const uploadArea = document.getElementById('uploadArea');
      const fileInput = document.getElementById('imageInput');
      
      uploadArea.addEventListener('click', () => fileInput.click());
      
      uploadArea.addEventListener('dragover', function(e) {
        e.preventDefault();
        this.classList.add('dragover');
      });
      
      uploadArea.addEventListener('dragleave', function(e) {
        e.preventDefault();
        this.classList.remove('dragover');
      });
      
      uploadArea.addEventListener('drop', function(e) {
        e.preventDefault();
        this.classList.remove('dragover');
        
        const files = e.dataTransfer.files;
        if (files.length > 0) {
          fileInput.files = files;
          handleFileSelect();
        }
      });
      
      fileInput.addEventListener('change', handleFileSelect);
      
      function handleFileSelect() {
        const file = fileInput.files[0];
        if (file) {
          // Validar arquivo
          const allowedTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif'];
          const maxSize = 5 * 1024 * 1024; // 5MB
          
          if (!allowedTypes.includes(file.type)) {
            showNotification('Tipo de arquivo não permitido. Use JPG, PNG ou GIF.', 'error');
            fileInput.value = '';
            return;
          }
          
          if (file.size > maxSize) {
            showNotification('Arquivo muito grande. Máximo 5MB.', 'error');
            fileInput.value = '';
            return;
          }
          
          document.getElementById('confirmUpload').disabled = false;
          
          // Mostrar preview
          const reader = new FileReader();
          reader.onload = function(e) {
            document.getElementById('currentImagePreview').src = e.target.result;
          };
          reader.readAsDataURL(file);
        }
      }
      
      document.getElementById('confirmUpload').addEventListener('click', uploadImage);
      
      // Fechar modal ao clicar fora
      document.getElementById('imageUploadModal').addEventListener('click', function(e) {
        if (e.target === this) {
          closeImageUploadModal();
        }
      });
    });

    // CSS para animação de spin
    const style = document.createElement('style');
    style.textContent = `
      @keyframes spin {
        from { transform: rotate(0deg); }
        to { transform: rotate(360deg); }
      }
    `;
    document.head.appendChild(style);
  </script>

  <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
  <script src="../js/script.js"></script>
  <script src="../js/sobre.js"></script>
  <script src="../js/usuario.js"></script>
  <script src="../js/list.js"></script>

</body>

</html>