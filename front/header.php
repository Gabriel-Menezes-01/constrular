
    <link rel="stylesheet" href="../../constrular/css/header/header.css">
    <link rel="stylesheet" href="style.css">

<!-- nav bar -->
  <header class="tod-cont">
    <h1><a href="./Inicio.php">Constru<span class="lar" >Lar</span></a></h1>
    <nav>
      <a href="../../constrular/index.php">INÍCIO</a>
      <a href="../../constrular/pages/orcamento.php">ORÇAMENTO</a>
      <a href="../../constrular/pages/contato.php">CONTATOS</a>
      <a href="../../constrular/pages/sobre.php">SOBRE</a>
    </nav>
    <div class="logins" id="loginss" >


      <button class="login">LOGIN</button>

      <dialog class="openlogin">
       <form action="../backend/usuarios.php?email" method="POST" class="formulario" id="loginForm" > 
            <div class="login">
                <h2 class="h2">LOGIN</h2>
            </div>           
            
            <div class="input_text">
                <input type="email" name="email" id="email" class="input" required>
                <label for="email" class="label">E-mail</label>
                <i class="bi bi-person"></i>
                
            </div>
            
            <div class="input_text">
                <input type="password" name="senha" id="senha" class="input" required>
                <label for="senha" class="label">Senha</label>
                <i class="bi bi-key"></i>
                
            </div>

            <button type="submit"  name="login" id="btnlogin" class="btn-login">ENTRAR</button>
            
            <p >Não tem uma conta? <a href="#" class="abrircadastro">Cadastre-se</p></a>
            <p><a href="#" class="fechalogin">Voltar</a></p>
        </form>
      </dialog>
<!-- cadastro -->

      <button class = "cadastro">CADASTRA-SE</button>

      <dialog class="openCadastro">
        <form action="../backend/autenticacao.php" method="POST" id="form" class="formulario" >
            <div class="cadrasto">
                <h2 class="h2">CADRASTA-SE</h2>
            </div>

            <div class="input_text">
                <input type="text" name="nome" class="input required"  id="nome" required>
                <label for="nome" class="label">Nome</label>
                <i class="bi bi-person"></i>
                
            </div>
            
            <div class="input_text">
                <input  type="text" name="apelido" class="input required" id="apelido" required>
                <label for="apelido" class="label">Apelido</label>
                <i class="bi bi-person"></i>
                
            </div>
            
            <div class="input_text">
                <input type="email" name="email" class="input required" id="email" required>
                <label for="email" class="label">E-mail</label>
                <i class="bi bi-envelope-at"></i>
                
            </div>

            <div class="input_text">
                <input type="password" name="senha" class="input required" id="senha" required>
                <label for="senha" class="label">Senha</label>
                <i class="bi bi-key"></i>
                
            </div>
            
            <button type="submit" name="cadrasto" class="btn-cadrasto" id="entre" onclick="" >CADRASTA-SE</button>
            <p class="p">Já tem uma conta? <a href="#" class="abrirLogin">Entre</a></p>
            <p><a href="" class="fechacadastro">Volta</a></p>

      </dialog>
    </div>
  </header>

  