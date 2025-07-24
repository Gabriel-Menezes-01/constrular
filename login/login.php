
<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - ConstruLar</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="../csslogin/login.css">
</head>
<body>
    
    <section class="container">
        <div class="login-card">
            <div class="card-header">
                <h2><i class="bi bi-building"></i> Constru<span class="lar">Lar</span></h2>
                <p>Acesse sua conta</p>
            </div>
            
            <form action="../backend/usuarios.php" method="POST" class="formulario" id="loginForm"> 
                <input type="hidden" name="action" value="login">
                
                <div class="input-group">
                    <div class="input_text">
                        <input type="email" name="email" id="email" class="input" required>
                        <label for="email" class="label">E-mail</label>
                        <i class="bi bi-envelope"></i>
                        <span class="input-focus"></span>
                    </div>
                </div>
                
                <div class="input-group">
                    <div class="input_text">
                        <input type="password" name="senha" id="senha" class="input" required>
                        <label for="senha" class="label">Senha</label>
                        <i class="bi bi-lock"></i>
                        <span class="toggle-password" onclick="togglePassword()">
                            <i class="bi bi-eye" id="eyeIcon"></i>
                        </span>
                        <span class="input-focus"></span>
                    </div>
                </div>

                <button type="submit" name="login" id="btnlogin" class="btn-login">
                    <span class="btn-text">ENTRAR</span>
                    <div class="btn-loading">
                        <i class="bi bi-arrow-repeat"></i>
                    </div>
                </button>
                
                <div class="divider">
                    <span>ou</span>
                </div>
                
                <div class="register-link">
                    <p>Não tem uma conta? <a href="./cadastro.php" class="link">Cadastre-se</a></p>
                    <p><a href="../front/Inicio.php" class="link">← Voltar para Início</a></p>
                </div>

                <!-- Mensagens de erro/sucesso -->
                <div class="message-container">
                    <?php if(isset($_GET['error'])): ?>
                        <div class="alert alert-error">
                            <i class="bi bi-exclamation-triangle"></i>
                            <span>
                                <?php 
                                    switch($_GET['error']) {
                                        case 'invalid':
                                            echo 'E-mail ou senha incorretos!';
                                            break;
                                        case 'empty':
                                            echo 'Por favor, preencha todos os campos!';
                                            break;
                                        case 'db':
                                            echo 'Erro no banco de dados. Tente novamente!';
                                            break;
                                        default:
                                            echo 'Erro desconhecido!';
                                    }
                                ?>
                            </span>
                        </div>
                    <?php endif; ?>
                    
                    <?php if(isset($_GET['success'])): ?>
                        <div class="alert alert-success">
                            <i class="bi bi-check-circle"></i>
                            <span>
                                <?php 
                                    switch($_GET['success']) {
                                        case 'registered':
                                            echo 'Cadastro realizado com sucesso!';
                                            break;
                                        case 'logout':
                                            echo 'Logout realizado com sucesso!';
                                            break;
                                        default:
                                            echo 'Operação realizada com sucesso!';
                                    }
                                ?>
                            </span>
                        </div>
                    <?php endif; ?>
                </div>
            </form>
        </div>
    </section>

    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="../js/login.js"></script>
</body>
</html>