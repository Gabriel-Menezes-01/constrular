
<!DOCTYPE html>
<html lang="pt">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
  <link rel="stylesheet" href="../css/styles.css">
  <link rel="stylesheet" href="../css/contatos.css">
  
  <title>Contatos - ConstruLar</title>
</head>
<body>
    <!-- nav bar -->
    <?php include('./header.php'); ?>

    <section class="contato-section">
      <div class="container">
        <div class="contato-header">
          <h2><i class="bi bi-chat-dots"></i> Entre em Contato</h2>
          <p>Transforme seu sonho em realidade. Estamos aqui para ajudar!</p>
        </div>
        
        <div class="contato-content">
          <!-- Formulário de Contato -->
          <div class="form-container">
            <div class="form-card">
              <div class="form-header">
                <h3><i class="bi bi-envelope-heart"></i> Envie sua Mensagem</h3>
                <p>Preencha o formulário e retornaremos em breve</p>
              </div>
              
              <form class="contato-form" id="contatoForm">
                <div class="input-group">
                  <div class="input-wrapper">
                    <label for="nome"><i class="bi bi-person"></i> Nome Completo</label>
                    <input type="text" name="nome" id="nome" placeholder="Digite seu nome completo" required>
                    <span class="input-focus"></span>
                  </div>
                </div>

                <div class="input-group">
                  <div class="input-wrapper">
                    <label for="email"><i class="bi bi-envelope"></i> E-mail</label>
                    <input type="email" name="email" id="email" placeholder="seu@email.com" required>
                    <span class="input-focus"></span>
                  </div>
                </div>

                <div class="input-group">
                  <div class="input-wrapper">
                    <label for="telefone"><i class="bi bi-telephone"></i> Telefone</label>
                    <input type="tel" name="telefone" id="telefone" placeholder="(11) 99999-9999" required>
                    <span class="input-focus"></span>
                  </div>
                </div>

                <div class="input-group">
                  <div class="input-wrapper">
                    <label for="assunto"><i class="bi bi-tag"></i> Assunto</label>
                    <select name="assunto" id="assunto" required>
                      <option value="">Selecione o assunto</option>
                      <option value="orcamento">Solicitação de Orçamento</option>
                      <option value="projeto">Consulta sobre Projeto</option>
                      <option value="reforma">Reforma/Ampliação</option>
                      <option value="duvida">Dúvidas Gerais</option>
                      <option value="outro">Outro</option>
                    </select>
                    <span class="input-focus"></span>
                  </div>
                </div>

                <div class="input-group">
                  <div class="input-wrapper">
                    <label for="mensagem"><i class="bi bi-chat-text"></i> Mensagem</label>
                    <textarea name="mensagem" id="mensagem" rows="5" placeholder="Conte-nos sobre seu projeto ou dúvida..." required></textarea>
                    <span class="input-focus"></span>
                  </div>
                </div>

                <div class="form-actions">
                  <button type="submit" class="btn-enviar">
                    <i class="bi bi-send"></i>
                    <span>Enviar Mensagem</span>
                    <div class="btn-loading">
                      <i class="bi bi-arrow-repeat"></i>
                    </div>
                  </button>
                </div>

                <div class="success-message" id="successMessage">
                  <i class="bi bi-check-circle"></i>
                  <span>Mensagem enviada com sucesso! Retornaremos em breve.</span>
                </div>
              </form>
            </div>
          </div>

          <!-- Informações de Contato -->
          <div class="info-container">
            <!-- Mapa -->
            <div class="map-container">
              <div class="map-header">
                <h3><i class="bi bi-geo-alt"></i> Nossa Localização</h3>
              </div>
              <div class="map-wrapper">
                <iframe 
                  src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3109.809644980234!2d-9.38311272434183!3d38.79099797174691!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0xd1edabb102a4ca3%3A0x59c8f3a91b6f95df!2s2710-512%20Sintra!5e0!3m2!1spt-BR!2spt!4v1733683100032!5m2!1spt-BR!2spt" 
                  allowfullscreen="" 
                  loading="lazy" 
                  referrerpolicy="no-referrer-when-downgrade">
                </iframe>
              </div>
            </div>

            <!-- Cards de Contato -->
            <div class="contact-cards">
              <div class="contact-card" data-contact="phone">
                <div class="card-icon">
                  <i class="bi bi-telephone"></i>
                </div>
                <div class="card-content">
                  <h4>Telefone</h4>
                  <p>+55 (11) 99999-9999</p>
                  <span>Seg - Sex: 8h às 18h</span>
                </div>
              </div>

              <div class="contact-card" data-contact="email">
                <div class="card-icon">
                  <i class="bi bi-envelope"></i>
                </div>
                <div class="card-content">
                  <h4>E-mail</h4>
                  <p>constrular@gmail.com</p>
                  <span>Resposta em até 24h</span>
                </div>
              </div>

              <div class="contact-card" data-contact="whatsapp">
                <div class="card-icon">
                  <i class="bi bi-whatsapp"></i>
                </div>
                <div class="card-content">
                  <h4>WhatsApp</h4>
                  <p>+351 912 345 678</p>
                  <span>Atendimento imediato</span>
                </div>
              </div>

              <div class="contact-card" data-contact="location">
                <div class="card-icon">
                  <i class="bi bi-geo-alt"></i>
                </div>
                <div class="card-content">
                  <h4>Endereço</h4>
                  <p>Rua das Construções, 123</p>
                  <span>São Paulo - SP</span>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- footer -->
    <?php include('./footer.php'); ?>

    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="../js/script.js"></script>
    <script src="../js/contatos.js"></script>
    <script src="../js/usuario.js"></script>
    <script src="../js/logado.js"></script>

</body>
</html>