<!DOCTYPE html>
<html lang="pt">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
  <link rel="stylesheet" href="../css/styles.css">
  <link rel="stylesheet" href="../css/orcamento.css">
  <title>Orçamento</title>
</head>

<body class="img-orcamento">

  <!-- nav bar -->
   <?php include_once "../front/header.php" ?>


  <section class="orcamento">
    
    <div class="dados">
        
        <div class="inputs"> 
          <h2>Orçamento</h2>
          
            <div class="info">
              <label for="name">Nome Completo</label>
              <input type="text" name="name" id="nome">

              <label for="telefone">Telefone</label>
              <input type="tel" name="telefone" id="telefone">
            </div>
            

            <div class="info">
              <label for="largura">Largura Terreno</label>
              <input type="text" name="largura" id="largura">
              
              <label for="comprimento">Comprimento Terreno</label>
              <input type="text" name="comrpimento" id="comprimento">
            </div>
            <div class="val">
              <span>Por favor, preencha todos os campos com valores válidos</span>
            </div>
            
            <button class="btn-resultado">Calcular</button>
            <p class="valor">Valor de 350€ por m2.</p> 
        
            <div class="result"></div>
            <div class="obs">
          <p>Observação:O valor apresentado neste orçamento não representa o custo total da obra. Os materiais de construção não estão inclusos nesta estimativa inicial.</p>
        </div>
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