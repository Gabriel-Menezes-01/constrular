<link rel="stylesheet" href="../../css/csslogin/login.css">
    <section class="container">
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
            
            <p>Não tem uma conta? <a href="./cadrasto.php" class="a">Cadastre-se</a></p>
            <p><a href="../front/Inicio.php" class="a">Voltar para Início</a></p>
        </form>
    </section>
