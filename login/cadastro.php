<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="../csslogin/cadrasto.css">
    <title>Constrular</title>
</head>
<body>
    <section class="container">
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
            
            <button type="submit" name="cadrasto" class="btn-cadrasto" id="entre" >CADRASTA-SE</button>
            <p class="p">Já tem uma conta? <a href="./login.php">Entre</a></p>
            <p><a href="../front/Inicio.php" class="a">← Voltar para Início</a></p>

        </form>
    </section>

<script src="../js/usuario.js"></script>
</body>
</html>