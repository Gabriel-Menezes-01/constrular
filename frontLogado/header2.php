<link rel="stylesheet" href="../css/header2.css">
<?php


// Buscar dados do usuário
require_once '../backend/conexao.php';

$email = $_SESSION['email'];
$query = "SELECT nome, apelido, email, created_at, perfil_foto FROM usuarios WHERE email = '" . mysqli_real_escape_string($conn, $email) . "'";
$result = mysqli_query($conn, $query);

if ($result && mysqli_num_rows($result) > 0) {
    $user = mysqli_fetch_assoc($result);
    $nome = $user['nome'];
    $apelido = $user['apelido'];
    $email = $user['email'];
    $data = $user['created_at'];
    $profile_photo = $user['perfil_foto'];
    $isAdmin = ($email === 'admin@gmail.com');
} else {
    error_log("Erro ao buscar usuário: " . mysqli_error($conn));
    $nome = 'Usuário';
    $apelido = '';
    $email = '';
    $created_at = '';
    $profile_photo = null;
    $isAdmin = false;
}

// Função para exibir avatar
function displayAvatar($nome, $profile_photo, $class = 'user-avatar') {
    if (!empty($profile_photo) && file_exists("../uploads/perfis/" . $profile_photo)) {
        return '<div class="' . $class . '" style="background-image: url(\'../uploads/perfis/' . htmlspecialchars($profile_photo) . '\'); background-size: cover; background-position: center;"></div>';
    } else {
        return '<div class="' . $class . '">' . strtoupper(substr($nome, 0, 2)) . '</div>';
    }
}
?>

<!-- nav bar -->
<header class="tod-cont">
    <h1><a href="./Inicio.php">Constru<span class="lar">Lar</span></a></h1>
    
    <nav id="nav-menu">
        <a href="./Inicio.php" data-page="Inicio">INÍCIO</a>
        <a href="./orcamento.php" data-page="orcamento">ORÇAMENTO</a>
        <a href="./contato.php" data-page="contato">CONTATOS</a>
        <a href="./sobre.php" data-page="sobre">SOBRE</a>
    </nav>
    
    <div class="logins" id="loginss">
        <!-- Menu do Usuário -->
        <div class="user-menu-container">
            <button class="user-btn" id="userMenuBtn">
                <?php echo displayAvatar($nome, $profile_photo, 'user-avatar'); ?>
                <span class="user-name-display"><?php echo htmlspecialchars($nome); ?></span>
                <i class="bi bi-chevron-down dropdown-icon"></i>
            </button>

            <!-- Dropdown Menu -->
            <div class="user-dropdown" id="userDropdown">
                <div class="dropdown-header">
                    <?php echo displayAvatar($nome, $profile_photo, 'user-avatar-large'); ?>
                    <div class="user-details">
                        <h4><?php echo htmlspecialchars($nome); ?></h4>
                        <p><?php echo htmlspecialchars($email); ?></p>
                        <span class="badge <?php echo $isAdmin ? 'badge-admin' : 'badge-user'; ?>">
                            <?php echo $isAdmin ? 'Administrador' : 'Cliente'; ?>
                        </span>
                    </div>
                </div>

                <div class="dropdown-divider"></div>

                <ul class="dropdown-menu">
                    <li>
                        <a href="#" class="dropdown-item" id="profileBtn">
                            <i class="bi bi-person"></i>
                            <span>Meu Perfil</span>
                        </a>
                    </li>
                    <?php if ($isAdmin): ?>
                    <li>
                        <a href="#" class="dropdown-item admin-item" id="manageUsersBtn">
                            <i class="bi bi-people"></i>
                            <span>Gerenciar Usuários</span>
                        </a>
                    </li>
                    <?php endif; ?>
                    <li>
                        <div class="dropdown-divider"></div>
                    </li>
                    <li>
                        <a href="../backend/config.php" class="dropdown-item logout-item" onclick="return confirm('Deseja realmente sair?')">
                            <i class="bi bi-box-arrow-right"></i>
                            <span>Sair</span>
                        </a>
                    </li>
                </ul>
            </div>
        </div>

        <!-- Mobile Menu Button -->
        <button class="mobile-menu-toggle" id="menu">
            <span></span>
            <span></span>
            <span></span>
        </button>
    </div>
</header>

<!-- Modal de Perfil do Usuário -->
<div class="modal-overlay" id="profileModal">
    <div class="profile-modal">
        <div class="modal-header">
            <div class="header-content">
                <div class="header-icon">
                    <i class="bi bi-person-circle"></i>
                </div>
                <div class="header-text">
                    <h2>Meu Perfil</h2>
                    <p>Gerencie suas informações pessoais</p>
                </div>
            </div>
            <button class="modal-close-btn" id="closeProfileModal">
                <i class="bi bi-x-lg"></i>
            </button>
        </div>

        <div class="profile-content">
            <div class="profile-sidebar">
                <div class="profile-avatar-section">
                    <?php echo displayAvatar($nome, $profile_photo, 'profile-avatar-large'); ?>
                    <button class="change-avatar-btn">
                        <i class="bi bi-camera"></i>
                        Alterar Foto
                    </button>
                </div>

                <div class="profile-stats">
                    <div class="stat-item">
                        <i class="bi bi-calendar-check"></i>
                        <div>
                            <span class="stat-label">Membro desde</span>
                            <span class="stat-value"><?php echo date('d/m/Y', strtotime($data)); ?></span>
                        </div>
                    </div>
                    <div class="stat-item">
                        <i class="bi bi-shield-check"></i>
                        <div>
                            <span class="stat-label">Tipo de conta</span>
                            <span class="stat-value"><?php echo $isAdmin ? 'Administrador' : 'Cliente'; ?></span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="profile-main">
                <div class="profile-tabs">
                    <button class="tab-btn active" data-tab="personal">
                        <i class="bi bi-person"></i>
                        Informações Pessoais
                    </button>
                    <button class="tab-btn" data-tab="security">
                        <i class="bi bi-shield-lock"></i>
                        Segurança
                    </button>
                    <button class="tab-btn" data-tab="preferences">
                        <i class="bi bi-gear"></i>
                        Preferências
                    </button>
                </div>

                <div class="tab-content">
                    <!-- Tab Informações Pessoais -->
                    <div class="tab-panel active" id="personal">
                        <form class="profile-form" id="personalForm">
                            <div class="form-row">
                                <div class="form-group">
                                    <label for="name">Nome Completo</label>
                                    <input type="text" id="name" name="name" value="<?php echo htmlspecialchars($nome); ?>" required>
                                </div>
                                <div class="form-group">
                                    <label for="email">E-mail</label>
                                    <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($email); ?>" required>
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="form-group">
                                    <label for="phone">Telefone</label>
                                    <input type="tel" id="phone" name="phone" placeholder="+351 912 345 678" pattern="^\+351 \d{3} \d{3} \d{3}$" required>
                                </div>
                                <div class="form-group">
                                    <label for="birthdate">Data de Nascimento</label>
                                    <input type="date" id="birthdate" name="birthdate">
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="address">Endereço</label>
                                <textarea id="address" name="address" rows="3" placeholder="Endereço completo"></textarea>
                            </div>
                            <div class="form-actions">
                                <button type="button" class="btn-secondary">Cancelar</button>
                                <button type="submit" class="btn-primary">
                                    <i class="bi bi-check-lg"></i>
                                    Salvar Alterações
                                </button>
                            </div>
                        </form>
                    </div>

                    <!-- Tab Segurança -->
                    <div class="tab-panel" id="security">
                        <form class="profile-form" id="securityForm">
                            <div class="security-section">
                                <h3>Alterar Senha</h3>
                                <div class="form-group">
                                    <label for="currentPassword">Senha Atual</label>
                                    <input type="password" id="currentPassword" name="currentPassword" required>
                                </div>
                                <div class="form-row">
                                    <div class="form-group">
                                        <label for="newPassword">Nova Senha</label>
                                        <input type="password" id="newPassword" name="newPassword" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="confirmPassword">Confirmar Nova Senha</label>
                                        <input type="password" id="confirmPassword" name="confirmPassword" required>
                                    </div>
                                </div>
                            </div>

                            <div class="security-section">
                                <h3>Autenticação de Dois Fatores</h3>
                                <div class="security-option">
                                    <div class="option-info">
                                        <i class="bi bi-shield-plus"></i>
                                        <div>
                                            <h4>SMS</h4>
                                            <p>Receba códigos via SMS</p>
                                        </div>
                                    </div>
                                    <label class="toggle-switch">
                                        <input type="checkbox" id="smsAuth">
                                        <span class="slider"></span>
                                    </label>
                                </div>
                                <div class="security-option">
                                    <div class="option-info">
                                        <i class="bi bi-envelope-check"></i>
                                        <div>
                                            <h4>E-mail</h4>
                                            <p>Receba códigos por e-mail</p>
                                        </div>
                                    </div>
                                    <label class="toggle-switch">
                                        <input type="checkbox" id="emailAuth">
                                        <span class="slider"></span>
                                    </label>
                                </div>
                            </div>

                            <div class="form-actions">
                                <button type="submit" class="btn-primary">
                                    <i class="bi bi-shield-check"></i>
                                    Atualizar Segurança
                                </button>
                            </div>
                        </form>
                    </div>

                    <!-- Tab Preferências -->
                    <div class="tab-panel" id="preferences">
                        <form class="profile-form" id="preferencesForm">
                            <div class="preferences-section">
                                <h3>Notificações</h3>
                                <div class="preference-item">
                                    <div class="pref-info">
                                        <i class="bi bi-bell"></i>
                                        <div>
                                            <h4>Notificações por E-mail</h4>
                                            <p>Receber atualizações sobre projetos</p>
                                        </div>
                                    </div>
                                    <label class="toggle-switch">
                                        <input type="checkbox" id="emailNotifications" checked>
                                        <span class="slider"></span>
                                    </label>
                                </div>
                                <div class="preference-item">
                                    <div class="pref-info">
                                        <i class="bi bi-phone-vibrate"></i>
                                        <div>
                                            <h4>Notificações Push</h4>
                                            <p>Alertas em tempo real</p>
                                        </div>
                                    </div>
                                    <label class="toggle-switch">
                                        <input type="checkbox" id="pushNotifications">
                                        <span class="slider"></span>
                                    </label>
                                </div>
                            </div>


                            <div class="form-actions">
                                <button type="submit" class="btn-primary">
                                    <i class="bi bi-check-lg"></i>
                                    Salvar Preferências
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
    // Elementos do header
    const userBtn = document.getElementById('userMenuBtn');
    const userDropdown = document.getElementById('userDropdown');
    const menuToggle = document.getElementById('menu');
    
    // Elementos dos modais
    const profileModal = document.getElementById('profileModal');
    const profileBtn = document.getElementById('profileBtn');
    const closeProfileModal = document.getElementById('closeProfileModal');
    
    // Gerenciar usuários
    const manageUsersBtn = document.getElementById('manageUsersBtn');
    
    // Dropdown do usuário
    if (userBtn && userDropdown) {
        userBtn.addEventListener('click', function(e) {
            e.stopPropagation();
            userDropdown.classList.toggle('show');
        });
        
        document.addEventListener('click', function(e) {
            if (!userBtn.contains(e.target) && !userDropdown.contains(e.target)) {
                userDropdown.classList.remove('show');
            }
        });
    }
    
    // Modal de Perfil
    function openProfileModal() {
        profileModal.classList.add('show');
        document.body.style.overflow = 'hidden';
        userDropdown.classList.remove('show');
    }
    
    function closeProfileModalFunc() {
        profileModal.classList.remove('show');
        document.body.style.overflow = 'auto';
    }
    
    if (profileBtn) {
        profileBtn.addEventListener('click', function(e) {
            e.preventDefault();
            openProfileModal();
        });
    }
    
    if (closeProfileModal) {
        closeProfileModal.addEventListener('click', closeProfileModalFunc);
    }
    
    // Fechar modal ao clicar no overlay
    profileModal.addEventListener('click', function(e) {
        if (e.target === profileModal) {
            closeProfileModalFunc();
        }
    });
    
    // Tabs do perfil
    const tabBtns = document.querySelectorAll('.tab-btn');
    const tabPanels = document.querySelectorAll('.tab-panel');
    
    tabBtns.forEach(btn => {
        btn.addEventListener('click', function() {
            const targetTab = this.dataset.tab;
            
            // Remove active de todas as tabs
            tabBtns.forEach(b => b.classList.remove('active'));
            tabPanels.forEach(p => p.classList.remove('active'));
            
            // Ativa a tab clicada
            this.classList.add('active');
            document.getElementById(targetTab).classList.add('active');
        });
    });
    
    // Theme selector
    const themeOptions = document.querySelectorAll('.theme-option');
    themeOptions.forEach(option => {
        option.addEventListener('click', function() {
            themeOptions.forEach(o => o.classList.remove('active'));
            this.classList.add('active');
        });
    });
    
    // Gerenciar usuários
    function openUserModal() {
        if (window.userManager) {
            window.userManager.openModal();
        } else {
            // Carregar o script se não estiver carregado
            const script = document.createElement('script');
            script.src = '../js/list.js';
            script.onload = function() {
                if (window.userManager) {
                    window.userManager.openModal();
                }
            };
            document.head.appendChild(script);
        }
        userDropdown.classList.remove('show');
    }
    
    if (manageUsersBtn) {
        manageUsersBtn.addEventListener('click', function(e) {
            e.preventDefault();
            openUserModal();
        });
    }
    
    // Marcar página ativa
    const currentPage = window.location.pathname.split('/').pop().replace('.php', '');
    const navLinks = document.querySelectorAll('nav a[data-page]');
    
    navLinks.forEach(link => {
        if (link.dataset.page === currentPage) {
            link.style.color = '#FF6B6B';
        }
    });
    
    // Form submissions com envio para o backend
    document.getElementById('personalForm').addEventListener('submit', function(e) {
        e.preventDefault();
        
        const formData = new FormData(this);
        formData.append('operation', 'personal');
        
        // Adicionar apelido se não estiver no form
        const apelidoInput = document.querySelector('#apelido');
        if (apelidoInput) {
            formData.append('apelido', apelidoInput.value);
        }
        
        submitForm(formData, 'Atualizando informações...');
    });
    
    document.getElementById('securityForm').addEventListener('submit', function(e) {
        e.preventDefault();
        
        // Validar senhas antes de enviar
        const newPassword = document.getElementById('newPassword').value;
        const confirmPassword = document.getElementById('confirmPassword').value;
        
        if (newPassword !== confirmPassword) {
            showNotification('As senhas não coincidem', 'error');
            return;
        }
        
        if (newPassword.length < 6) {
            showNotification('A senha deve ter pelo menos 6 caracteres', 'error');
            return;
        }
        
        const formData = new FormData(this);
        formData.append('operation', 'security');
        
        submitForm(formData, 'Atualizando senha...');
    });
    
    document.getElementById('preferencesForm').addEventListener('submit', function(e) {
        e.preventDefault();
        showNotification('Preferências salvas com sucesso!', 'success');
    });
    
    // Upload de foto de perfil
    const changeAvatarBtn = document.querySelector('.change-avatar-btn');
    if (changeAvatarBtn) {
        changeAvatarBtn.addEventListener('click', function() {
            const input = document.createElement('input');
            input.type = 'file';
            input.accept = 'image/*';
            input.style.display = 'none';
            
            input.addEventListener('change', function(e) {
                const file = e.target.files[0];
                if (file) {
                    uploadProfilePhoto(file);
                }
            });
            
            document.body.appendChild(input);
            input.click();
            document.body.removeChild(input);
        });
    }
    
    function submitForm(formData, loadingMessage = 'Processando...') {
        // Mostrar loading
        showNotification(loadingMessage, 'info');
        
        fetch('../backend/update_profile.php', {
            method: 'POST',
            body: formData,
            credentials: 'same-origin'
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showNotification(data.message, 'success');
                
                // Atualizar interface se necessário
                if (data.data) {
                    updateUserInterface(data.data);
                }
                
                // Limpar formulário de senha
                if (formData.get('operation') === 'security') {
                    document.getElementById('securityForm').reset();
                }
                
            } else {
                showNotification(data.message, 'error');
            }
        })
        .catch(error => {
            console.error('Erro:', error);
            showNotification('Erro de conexão. Tente novamente.', 'error');
        });
    }
    
    function uploadProfilePhoto(file) {
        // Validar arquivo
        const allowedTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif'];
        const maxSize = 5 * 1024 * 1024; // 5MB
        
        if (!allowedTypes.includes(file.type)) {
            showNotification('Tipo de arquivo não permitido. Use JPG, PNG ou GIF.', 'error');
            return;
        }
        
        if (file.size > maxSize) {
            showNotification('Arquivo muito grande. Máximo 5MB.', 'error');
            return;
        }
        
        // Mostrar preview
        const reader = new FileReader();
        reader.onload = function(e) {
            // Atualizar avatares com preview
            const avatars = document.querySelectorAll('.profile-avatar-large, .user-avatar, .user-avatar-large');
            avatars.forEach(avatar => {
                avatar.style.backgroundImage = `url(${e.target.result})`;
                avatar.style.backgroundSize = 'cover';
                avatar.style.backgroundPosition = 'center';
                avatar.textContent = '';
            });
        };
        reader.readAsDataURL(file);
        
        // Enviar arquivo - NOME CORRETO DO CAMPO
        const formData = new FormData();
        formData.append('perfil_foto', file); // Mesmo nome esperado no PHP
        formData.append('operation', 'photo');
        
        submitForm(formData, 'Atualizando foto...');
    }
    
    function updateUserInterface(data) {
        // Atualizar nome na interface
        if (data.nome) {
            const nameElements = document.querySelectorAll('.user-name-display, .user-details h4');
            nameElements.forEach(el => {
                el.textContent = data.nome;
            });
            
            // Atualizar avatares com iniciais
            const avatars = document.querySelectorAll('.user-avatar, .user-avatar-large, .profile-avatar-large');
            avatars.forEach(avatar => {
                if (!avatar.style.backgroundImage) {
                    avatar.textContent = data.nome.substring(0, 2).toUpperCase();
                }
            });
        }
        
        // Atualizar email na interface
        if (data.email) {
            const emailElements = document.querySelectorAll('.user-details p');
            emailElements.forEach(el => {
                if (el.textContent.includes('@')) {
                    el.textContent = data.email;
                }
            });
        }
    }
    
    // Função melhorada para mostrar notificações
    function showNotification(message, type = 'info') {
        // Remover notificação existente
        const existingNotification = document.querySelector('.notification');
        if (existingNotification) {
            existingNotification.remove();
        }
        
        const notification = document.createElement('div');
        notification.className = `notification notification-${type}`;
        
        const icons = {
            success: 'check-circle',
            error: 'exclamation-triangle',
            info: 'info-circle'
        };
        
        const colors = {
            success: 'linear-gradient(135deg, #4ECDC4, #27AE60)',
            error: 'linear-gradient(135deg, #FF6B6B, #E74C3C)',
            info: 'linear-gradient(135deg, #667eea, #764ba2)'
        };
        
        notification.innerHTML = `
            <div class="notification-content">
                <i class="bi bi-${icons[type]}"></i>
                <span>${message}</span>
            </div>
        `;
        
        Object.assign(notification.style, {
            position: 'fixed',
            top: '20px',
            right: '20px',
            background: colors[type],
            color: 'white',
            padding: '15px 20px',
            borderRadius: '12px',
            display: 'flex',
            alignItems: 'center',
            gap: '10px',
            zIndex: '3000',
            transform: 'translateX(100%)',
            transition: 'all 0.3s ease',
            boxShadow: '0 8px 25px rgba(0, 0, 0, 0.15)',
            backdropFilter: 'blur(10px)',
            fontWeight: '500',
            minWidth: '300px'
        });
        
        notification.querySelector('.notification-content').style.cssText = `
            display: flex;
            align-items: center;
            gap: 10px;
        `;
        
        notification.querySelector('i').style.fontSize = '18px';
        
        document.body.appendChild(notification);
        
        // Animar entrada
        setTimeout(() => {
            notification.style.transform = 'translateX(0)';
        }, 100);
        
        // Auto remover
        setTimeout(() => {
            notification.style.transform = 'translateX(100%)';
            setTimeout(() => {
                if (notification.parentNode) {
                    notification.remove();
                }
            }, 300);
        }, type === 'error' ? 5000 : 3000);
        
        // Click para fechar
        notification.addEventListener('click', () => {
            notification.style.transform = 'translateX(100%)';
            setTimeout(() => {
                if (notification.parentNode) {
                    notification.remove();
                }
            }, 300);
        });
    }
    
    // Fechar modais com ESC
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            userDropdown.classList.remove('show');
            closeProfileModalFunc();
        }
    });
});
</script>