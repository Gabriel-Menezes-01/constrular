class UserManager {
    constructor() {
        this.users = [];
        this.filteredUsers = [];
        this.currentPage = 1;
        this.itemsPerPage = 10;
        this.sortBy = 'nome';
        this.sortOrder = 'asc';
        this.searchTerm = '';
        this.filterStatus = 'all';
        
        this.modal = null;
        this.createModal();
    }

    createModal() {
        // Remove modal existente se houver
        const existingModal = document.getElementById('userManagementModal');
        if (existingModal) {
            existingModal.remove();
        }

        // Cria o modal
        this.modal = document.createElement('div');
        this.modal.id = 'userManagementModal';
        this.modal.className = 'modal-overlay';
        this.modal.innerHTML = `
            <div class="user-management-modal">
                <div class="modal-header">
                    <div class="header-content">
                        <div class="header-icon">
                            <i class="bi bi-people-fill"></i>
                        </div>
                        <div class="header-text">
                            <h2>Gerenciar Usuários</h2>
                            <p>Administre todos os usuários do sistema</p>
                        </div>
                    </div>
                    <button class="modal-close-btn" id="closeUserModal">
                        <i class="bi bi-x-lg"></i>
                    </button>
                </div>

                <div class="modal-content">
                    <!-- Toolbar -->
                    <div class="user-toolbar">
                        <div class="toolbar-left">
                            <div class="search-container">
                                <i class="bi bi-search"></i>
                                <input type="text" id="userSearch" placeholder="Buscar usuários..." class="search-input">
                            </div>
                            <select id="statusFilter" class="filter-select">
                                <option value="all">Todos os Status</option>
                                <option value="active">Ativos</option>
                                <option value="inactive">Inativos</option>
                            </select>
                        </div>
                        <div class="toolbar-right">
                            <button class="btn-refresh" id="refreshBtn">
                                <i class="bi bi-arrow-clockwise"></i>
                            </button>
                        </div>
                    </div>

                    <!-- Stats Cards -->
                    <div class="stats-container">
                        <div class="stat-card">
                            <div class="stat-icon total">
                                <i class="bi bi-people"></i>
                            </div>
                            <div class="stat-info">
                                <h3 id="totalUsers">0</h3>
                                <p>Total de Usuários</p>
                            </div>
                        </div>
                        <div class="stat-card">
                            <div class="stat-icon active">
                                <i class="bi bi-person-check"></i>
                            </div>
                            <div class="stat-info">
                                <h3 id="activeUsers">0</h3>
                                <p>Usuários Ativos</p>
                            </div>
                        </div>
                        <div class="stat-card">
                            <div class="stat-icon admin">
                                <i class="bi bi-shield-check"></i>
                            </div>
                            <div class="stat-info">
                                <h3 id="adminUsers">0</h3>
                                <p>Administradores</p>
                            </div>
                        </div>
                        <div class="stat-card">
                            <div class="stat-icon new">
                                <i class="bi bi-person-plus"></i>
                            </div>
                            <div class="stat-info">
                                <h3 id="newUsers">0</h3>
                                <p>Novos (30 dias)</p>
                            </div>
                        </div>
                    </div>

                    <!-- Users Table -->
                    <div class="table-container">
                        <div class="table-header">
                            <h3>Lista de Usuários</h3>
                            <div class="table-controls">
                                <select id="itemsPerPage" class="items-select">
                                    <option value="10">10 por página</option>
                                    <option value="25">25 por página</option>
                                    <option value="50">50 por página</option>
                                    <option value="100">100 por página</option>
                                </select>
                            </div>
                        </div>

                        <div class="table-wrapper">
                            <table class="users-table">
                                <thead>
                                    <tr>
                                        <th data-sort="nome" class="sortable active">
                                            Nome <i class="bi bi-chevron-up sort-icon"></i>
                                        </th>
                                        <th data-sort="email" class="sortable">
                                            Email <i class="bi bi-chevron-up sort-icon"></i>
                                        </th>
                                        <th data-sort="ativo" class="sortable">
                                            Status <i class="bi bi-chevron-up sort-icon"></i>
                                        </th>
                                        <th data-sort="created_at" class="sortable">
                                            Data Cadastro <i class="bi bi-chevron-up sort-icon"></i>
                                        </th>
                                        <th>Ações</th>
                                    </tr>
                                </thead>
                                <tbody id="usersTableBody">
                                    <!-- Usuários serão inseridos aqui -->
                                </tbody>
                            </table>
                        </div>

                        <!-- Loading State -->
                        <div class="loading-state" id="loadingState">
                            <div class="loading-spinner"></div>
                            <p>Carregando usuários...</p>
                        </div>

                        <!-- Empty State -->
                        <div class="empty-state" id="emptyState" style="display: none;">
                            <i class="bi bi-people"></i>
                            <h3>Nenhum usuário encontrado</h3>
                            <p>Tente ajustar os filtros de busca</p>
                        </div>

                        <!-- Pagination -->
                        <div class="pagination-container">
                            <div class="pagination-info">
                                <span id="paginationInfo">Mostrando 0 de 0 usuários</span>
                            </div>
                            <div class="pagination-controls" id="paginationControls">
                                <!-- Botões de paginação serão inseridos aqui -->
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        `;

        // Adiciona estilos CSS
        this.addStyles();
        
        // Adiciona ao body
        document.body.appendChild(this.modal);
        
        // Adiciona event listeners
        this.addEventListeners();
    }

    addStyles() {
        const existingStyle = document.getElementById('userManagerStyles');
        if (existingStyle) return;

        const style = document.createElement('style');
        style.id = 'userManagerStyles';
        style.textContent = `
            /* User Management Modal Styles */
            .user-management-modal {
                background: rgba(255, 255, 255, 0.98);
                backdrop-filter: blur(20px);
                border-radius: 20px;
                box-shadow: 0 25px 80px rgba(0, 0, 0, 0.3);
                border: 1px solid rgba(255, 255, 255, 0.3);
                max-width: 1200px;
                width: 95%;
                max-height: 95vh;
                overflow: hidden;
                transform: scale(0.8);
                transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            }

            .modal-overlay.show .user-management-modal {
                transform: scale(1);
            }

            /* Toolbar */
            .user-toolbar {
                display: flex;
                justify-content: space-between;
                align-items: center;
                padding: 20px 25px;
                border-bottom: 1px solid rgba(0, 0, 0, 0.1);
                gap: 20px;
            }

            .toolbar-left {
                display: flex;
                align-items: center;
                gap: 15px;
                flex: 1;
            }

            .toolbar-right {
                display: flex;
                align-items: center;
                gap: 10px;
            }

            .search-container {
                position: relative;
                flex: 1;
                max-width: 300px;
            }

            .search-container i {
                position: absolute;
                left: 12px;
                top: 50%;
                transform: translateY(-50%);
                color: #666;
                font-size: 0.9rem;
            }

            .search-input {
                width: 100%;
                padding: 10px 12px 10px 35px;
                border: 2px solid #e0e0e0;
                border-radius: 25px;
                font-size: 0.9rem;
                outline: none;
                transition: all 0.3s ease;
            }

            .search-input:focus {
                border-color: #FF6B6B;
                box-shadow: 0 0 0 3px rgba(255, 107, 107, 0.1);
            }

            .filter-select {
                padding: 8px 15px;
                border: 2px solid #e0e0e0;
                border-radius: 20px;
                background: white;
                font-size: 0.85rem;
                outline: none;
                cursor: pointer;
            }

            .btn-add-user {
                background: linear-gradient(135deg, #FF6B6B, #4ECDC4);
                color: white;
                border: none;
                padding: 10px 20px;
                border-radius: 20px;
                font-weight: 600;
                cursor: pointer;
                display: flex;
                align-items: center;
                gap: 8px;
                transition: all 0.3s ease;
                font-size: 0.9rem;
            }

            .btn-add-user:hover {
                transform: translateY(-2px);
                box-shadow: 0 5px 15px rgba(255, 107, 107, 0.3);
            }

            .btn-refresh {
                background: white;
                border: 2px solid #e0e0e0;
                padding: 8px 12px;
                border-radius: 50%;
                cursor: pointer;
                transition: all 0.3s ease;
                display: flex;
                align-items: center;
                justify-content: center;
            }

            .btn-refresh:hover {
                background: #f8f9fa;
                border-color: #FF6B6B;
                color: #FF6B6B;
                transform: rotate(180deg);
            }

            /* Stats Cards */
            .stats-container {
                display: grid;
                grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
                gap: 15px;
                padding: 20px 25px;
                background: rgba(248, 249, 250, 0.5);
            }

            .stat-card {
                background: white;
                padding: 20px;
                border-radius: 15px;
                display: flex;
                align-items: center;
                gap: 15px;
                box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
                transition: all 0.3s ease;
            }

            .stat-card:hover {
                transform: translateY(-2px);
                box-shadow: 0 5px 20px rgba(0, 0, 0, 0.1);
            }

            .stat-icon {
                width: 50px;
                height: 50px;
                border-radius: 12px;
                display: flex;
                align-items: center;
                justify-content: center;
                color: white;
                font-size: 1.2rem;
            }

            .stat-icon.total { background: linear-gradient(135deg, #667eea, #764ba2); }
            .stat-icon.active { background: linear-gradient(135deg, #4ECDC4, #44A08D); }
            .stat-icon.admin { background: linear-gradient(135deg, #FFC107, #FF8F00); }
            .stat-icon.new { background: linear-gradient(135deg, #FF6B6B, #E74C3C); }

            .stat-info h3 {
                margin: 0 0 5px 0;
                font-size: 1.8rem;
                font-weight: 700;
                color: #333;
            }

            .stat-info p {
                margin: 0;
                font-size: 0.85rem;
                color: #666;
            }

            /* Table Container */
            .table-container {
                padding: 25px;
            }

            .table-header {
                display: flex;
                justify-content: space-between;
                align-items: center;
                margin-bottom: 20px;
            }

            .table-header h3 {
                margin: 0;
                color: #333;
                font-size: 1.3rem;
            }

            .items-select {
                padding: 6px 12px;
                border: 1px solid #e0e0e0;
                border-radius: 8px;
                font-size: 0.85rem;
                outline: none;
            }

            /* Table Styles */
            .table-wrapper {
                border-radius: 15px;
                overflow: hidden;
                box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
                border: 1px solid rgba(0, 0, 0, 0.05);
            }

            .users-table {
                width: 100%;
                border-collapse: collapse;
                background: white;
            }

            .users-table th {
                background: linear-gradient(135deg, #667eea, #764ba2);
                color: white;
                padding: 15px 12px;
                text-align: left;
                font-weight: 600;
                font-size: 0.9rem;
                position: relative;
            }

            .users-table th.sortable {
                cursor: pointer;
                user-select: none;
                transition: background 0.3s ease;
            }

            .users-table th.sortable:hover {
                background: linear-gradient(135deg, #5a6fd8, #6a4190);
            }

            .users-table th.active {
                background: linear-gradient(135deg, #FF6B6B, #4ECDC4);
            }

            .sort-icon {
                margin-left: 5px;
                font-size: 0.7rem;
                transition: transform 0.3s ease;
            }

            .users-table th.desc .sort-icon {
                transform: rotate(180deg);
            }

            .users-table td {
                padding: 15px 12px;
                border-bottom: 1px solid rgba(0, 0, 0, 0.05);
                font-size: 0.9rem;
            }

            .users-table tr:hover {
                background: rgba(255, 107, 107, 0.02);
            }

            /* User Row Styles */
            .user-avatar-small {
                width: 35px;
                height: 35px;
                border-radius: 50%;
                background: linear-gradient(135deg, #FF6B6B, #4ECDC4);
                display: inline-flex;
                align-items: center;
                justify-content: center;
                color: white;
                font-weight: 700;
                font-size: 0.8rem;
                margin-right: 10px;
            }

            .user-name-cell {
                display: flex;
                align-items: center;
            }

            .user-name-info h4 {
                margin: 0;
                font-size: 0.9rem;
                color: #333;
            }

            .user-name-info p {
                margin: 2px 0 0 0;
                font-size: 0.75rem;
                color: #666;
            }

            .status-badge {
                padding: 4px 12px;
                border-radius: 20px;
                font-size: 0.75rem;
                font-weight: 600;
                text-transform: uppercase;
            }

            .status-badge.active {
                background: rgba(39, 174, 96, 0.1);
                color: #27ae60;
                border: 1px solid rgba(39, 174, 96, 0.3);
            }

            .status-badge.inactive {
                background: rgba(231, 76, 60, 0.1);
                color: #e74c3c;
                border: 1px solid rgba(231, 76, 60, 0.3);
            }

            .admin-badge {
                background: rgba(255, 193, 7, 0.1);
                color: #FFC107;
                border: 1px solid rgba(255, 193, 7, 0.3);
                padding: 2px 8px;
                border-radius: 10px;
                font-size: 0.7rem;
                font-weight: 600;
                text-transform: uppercase;
                margin-left: 8px;
            }

            /* Action Buttons */
            .action-buttons {
                display: flex;
                gap: 5px;
            }

            .action-btn {
                width: 32px;
                height: 32px;
                border: none;
                border-radius: 8px;
                cursor: pointer;
                display: flex;
                align-items: center;
                justify-content: center;
                font-size: 0.8rem;
                transition: all 0.3s ease;
            }

            .action-btn.edit {
                background: rgba(52, 152, 219, 0.1);
                color: #3498db;
            }

            .action-btn.edit:hover {
                background: #3498db;
                color: white;
                transform: translateY(-1px);
            }

            .action-btn.delete {
                background: rgba(231, 76, 60, 0.1);
                color: #e74c3c;
            }

            .action-btn.delete:hover {
                background: #e74c3c;
                color: white;
                transform: translateY(-1px);
            }

            .action-btn.toggle {
                background: rgba(255, 193, 7, 0.1);
                color: #FFC107;
            }

            .action-btn.toggle:hover {
                background: #FFC107;
                color: white;
                transform: translateY(-1px);
            }

            /* Loading and Empty States */
            .loading-state, .empty-state {
                text-align: center;
                padding: 60px 20px;
                color: #666;
            }

            .loading-spinner {
                width: 40px;
                height: 40px;
                border: 4px solid #f3f3f3;
                border-top: 4px solid #FF6B6B;
                border-radius: 50%;
                animation: spin 1s linear infinite;
                margin: 0 auto 20px;
            }

            @keyframes spin {
                0% { transform: rotate(0deg); }
                100% { transform: rotate(360deg); }
            }

            .empty-state i {
                font-size: 4rem;
                color: #ddd;
                margin-bottom: 20px;
            }

            .empty-state h3 {
                margin: 0 0 10px 0;
                color: #666;
            }

            /* Pagination */
            .pagination-container {
                display: flex;
                justify-content: space-between;
                align-items: center;
                margin-top: 25px;
                padding-top: 20px;
                border-top: 1px solid rgba(0, 0, 0, 0.1);
            }

            .pagination-info {
                color: #666;
                font-size: 0.9rem;
            }

            .pagination-controls {
                display: flex;
                gap: 5px;
            }

            .pagination-btn {
                padding: 8px 12px;
                border: 1px solid #e0e0e0;
                background: white;
                border-radius: 8px;
                cursor: pointer;
                font-size: 0.85rem;
                transition: all 0.3s ease;
                min-width: 40px;
                text-align: center;
            }

            .pagination-btn:hover {
                background: #f8f9fa;
                border-color: #FF6B6B;
            }

            .pagination-btn.active {
                background: #FF6B6B;
                color: white;
                border-color: #FF6B6B;
            }

            .pagination-btn:disabled {
                opacity: 0.5;
                cursor: not-allowed;
            }

            /* Responsive */
            @media (max-width: 768px) {
                .user-management-modal {
                    width: 98%;
                    margin: 10px;
                    max-height: 95vh;
                }

                .user-toolbar {
                    flex-direction: column;
                    gap: 15px;
                    align-items: stretch;
                }

                .toolbar-left {
                    flex-direction: column;
                    gap: 10px;
                }

                .search-container {
                    max-width: none;
                }

                .stats-container {
                    grid-template-columns: repeat(2, 1fr);
                    gap: 10px;
                    padding: 15px;
                }

                .table-container {
                    padding: 15px;
                }

                .table-wrapper {
                    overflow-x: auto;
                }

                .users-table {
                    min-width: 600px;
                }

                .pagination-container {
                    flex-direction: column;
                    gap: 15px;
                    text-align: center;
                }
            }
        `;

        document.head.appendChild(style);
    }

    addEventListeners() {
        // Fechar modal
        const closeBtn = this.modal.querySelector('#closeUserModal');
        closeBtn.addEventListener('click', () => this.closeModal());

        // Fechar ao clicar no overlay
        this.modal.addEventListener('click', (e) => {
            if (e.target === this.modal) this.closeModal();
        });

        // Busca
        const searchInput = this.modal.querySelector('#userSearch');
        searchInput.addEventListener('input', (e) => {
            this.searchTerm = e.target.value;
            this.filterAndRender();
        });

        // Filtro de status
        const statusFilter = this.modal.querySelector('#statusFilter');
        statusFilter.addEventListener('change', (e) => {
            this.filterStatus = e.target.value;
            this.filterAndRender();
        });

        // Refresh
        const refreshBtn = this.modal.querySelector('#refreshBtn');
        refreshBtn.addEventListener('click', () => this.loadUsers());

        // Items per page
        const itemsSelect = this.modal.querySelector('#itemsPerPage');
        itemsSelect.addEventListener('change', (e) => {
            this.itemsPerPage = parseInt(e.target.value);
            this.currentPage = 1;
            this.renderUsers();
        });

        // Ordenação
        const sortableHeaders = this.modal.querySelectorAll('.sortable');
        sortableHeaders.forEach(header => {
            header.addEventListener('click', () => {
                const sortField = header.dataset.sort;
                if (this.sortBy === sortField) {
                    this.sortOrder = this.sortOrder === 'asc' ? 'desc' : 'asc';
                } else {
                    this.sortBy = sortField;
                    this.sortOrder = 'asc';
                }
                this.updateSortIcons();
                this.filterAndRender();
            });
        });

        // ESC para fechar
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape' && this.modal.classList.contains('show')) {
                this.closeModal();
            }
        });
    }

    async openModal() {
        this.modal.classList.add('show');
        document.body.style.overflow = 'hidden';
        await this.loadUsers();
    }

    closeModal() {
        this.modal.classList.remove('show');
        document.body.style.overflow = 'auto';
    }

    async loadUsers() {
        const loadingState = this.modal.querySelector('#loadingState');
        const tableWrapper = this.modal.querySelector('.table-wrapper');
        const emptyState = this.modal.querySelector('#emptyState');

        // Mostrar loading
        loadingState.style.display = 'block';
        tableWrapper.style.display = 'none';
        emptyState.style.display = 'none';

        try {
            console.log('Iniciando carregamento de usuários...');
            
            const response = await fetch('../backend/get_users.php', {
                method: 'GET',
                headers: {
                    'Content-Type': 'application/json',
                    'Cache-Control': 'no-cache'
                },
                credentials: 'same-origin' // Importante para manter a sessão
            });

            console.log('Response status:', response.status);
            console.log('Response headers:', response.headers);

            if (!response.ok) {
                const errorText = await response.text();
                console.error('Erro na resposta:', errorText);
                
                try {
                    const errorJson = JSON.parse(errorText);
                    throw new Error(errorJson.error || `HTTP ${response.status}`);
                } catch (parseError) {
                    throw new Error(`HTTP ${response.status}: ${errorText}`);
                }
            }

            const responseText = await response.text();
            console.log('Response text:', responseText);

            let users;
            try {
                users = JSON.parse(responseText);
            } catch (parseError) {
                console.error('Erro ao fazer parse do JSON:', parseError);
                console.error('Response text:', responseText);
                throw new Error('Resposta inválida do servidor');
            }

            if (!Array.isArray(users)) {
                console.error('Resposta não é um array:', users);
                throw new Error('Formato de dados inválido');
            }

            console.log('Usuários carregados:', users.length);
            
            this.users = users;
            this.filteredUsers = [...this.users];
            
            this.updateStats();
            this.filterAndRender();

            // Esconder loading
            loadingState.style.display = 'none';
            tableWrapper.style.display = 'block';

        } catch (error) {
            console.error('Erro completo:', error);
            
            // Esconder loading
            loadingState.style.display = 'none';
            
            // Mostrar erro na interface
            const errorMessage = error.message || 'Erro desconhecido';
            this.showDetailedError(errorMessage);
        }
    }

    showDetailedError(message) {
        const loadingState = this.modal.querySelector('#loadingState');
        loadingState.innerHTML = `
            <div style="color: #e74c3c; text-align: center; padding: 40px;">
                <i class="bi bi-exclamation-triangle" style="font-size: 3rem; margin-bottom: 20px; display: block;"></i>
                <h3>Erro ao Carregar Usuários</h3>
                <p style="margin: 10px 0; font-size: 0.9rem;">${message}</p>
                <button onclick="userManager.loadUsers()" style="
                    margin-top: 15px;
                    padding: 8px 16px;
                    background: #FF6B6B;
                    color: white;
                    border: none;
                    border-radius: 8px;
                    cursor: pointer;
                    font-weight: 600;
                ">
                    <i class="bi bi-arrow-clockwise"></i> Tentar Novamente
                </button>
            </div>
        `;
        loadingState.style.display = 'block';
    }

    updateStats() {
        const now = new Date();
        const thirtyDaysAgo = new Date(now.getTime() - (30 * 24 * 60 * 60 * 1000));

        const stats = {
            total: this.users.length,
            active: this.users.filter(u => u.ativo).length,
            admin: this.users.filter(u => u.email === 'admin@gmail.com').length,
            new: this.users.filter(u => new Date(u.created_at) > thirtyDaysAgo).length
        };

        this.modal.querySelector('#totalUsers').textContent = stats.total;
        this.modal.querySelector('#activeUsers').textContent = stats.active;
        this.modal.querySelector('#adminUsers').textContent = stats.admin;
        this.modal.querySelector('#newUsers').textContent = stats.new;
    }

    filterAndRender() {
        // Aplicar filtros
        this.filteredUsers = this.users.filter(user => {
            const matchesSearch = user.nome.toLowerCase().includes(this.searchTerm.toLowerCase()) ||
                                user.email.toLowerCase().includes(this.searchTerm.toLowerCase());
            
            const matchesStatus = this.filterStatus === 'all' ||
                                (this.filterStatus === 'active' && user.ativo) ||
                                (this.filterStatus === 'inactive' && !user.ativo);

            return matchesSearch && matchesStatus;
        });

        // Aplicar ordenação
        this.filteredUsers.sort((a, b) => {
            let aVal = a[this.sortBy];
            let bVal = b[this.sortBy];

            if (this.sortBy === 'created_at') {
                aVal = new Date(aVal);
                bVal = new Date(bVal);
            }

            if (typeof aVal === 'string') {
                aVal = aVal.toLowerCase();
                bVal = bVal.toLowerCase();
            }

            if (this.sortOrder === 'asc') {
                return aVal > bVal ? 1 : -1;
            } else {
                return aVal < bVal ? 1 : -1;
            }
        });

        this.currentPage = 1;
        this.renderUsers();
    }

    renderUsers() {
        const tbody = this.modal.querySelector('#usersTableBody');
        const emptyState = this.modal.querySelector('#emptyState');
        const tableWrapper = this.modal.querySelector('.table-wrapper');

        if (this.filteredUsers.length === 0) {
            tableWrapper.style.display = 'none';
            emptyState.style.display = 'block';
            return;
        }

        tableWrapper.style.display = 'block';
        emptyState.style.display = 'none';

        // Paginação
        const startIndex = (this.currentPage - 1) * this.itemsPerPage;
        const endIndex = startIndex + this.itemsPerPage;
        const pageUsers = this.filteredUsers.slice(startIndex, endIndex);

        tbody.innerHTML = pageUsers.map(user => `
            <tr>
                <td>
                    <div class="user-name-cell">
                        <div class="user-avatar-small">
                            ${user.nome.substring(0, 2).toUpperCase()}
                        </div>
                        <div class="user-name-info">
                            <h4>
                                ${user.nome}
                                ${user.email === 'admin@gmail.com' ? '<span class="admin-badge">Admin</span>' : ''}
                            </h4>
                            <p>ID: ${user.id}</p>
                        </div>
                    </div>
                </td>
                <td>${user.email}</td>
                <td>
                    <span class="status-badge ${user.ativo ? 'active' : 'inactive'}">
                        ${user.ativo ? 'Ativo' : 'Inativo'}
                    </span>
                </td>
                <td>${new Date(user.created_at).toLocaleDateString('pt-BR')}</td>
                <td>
                    <div class="action-buttons">
                        <button class="action-btn edit" onclick="userManager.editUser(${user.id})" title="Editar">
                            <i class="bi bi-pencil"></i>
                        </button>
                        <button class="action-btn toggle" onclick="userManager.toggleUser(${user.id})" title="${user.ativo ? 'Desativar' : 'Ativar'}">
                            <i class="bi bi-${user.ativo ? 'pause' : 'play'}"></i>
                        </button>
                        ${user.email !== 'admin@gmail.com' ? `
                            <button class="action-btn delete" onclick="userManager.deleteUser(${user.id})" title="Excluir">
                                <i class="bi bi-trash"></i>
                            </button>
                        ` : ''}
                    </div>
                </td>
            </tr>
        `).join('');

        this.renderPagination();
    }

    renderPagination() {
        const totalPages = Math.ceil(this.filteredUsers.length / this.itemsPerPage);
        const startItem = (this.currentPage - 1) * this.itemsPerPage + 1;
        const endItem = Math.min(this.currentPage * this.itemsPerPage, this.filteredUsers.length);

        // Info
        const paginationInfo = this.modal.querySelector('#paginationInfo');
        paginationInfo.textContent = `Mostrando ${startItem} a ${endItem} de ${this.filteredUsers.length} usuários`;

        // Controls
        const controls = this.modal.querySelector('#paginationControls');
        let buttonsHTML = '';

        // Anterior
        buttonsHTML += `
            <button class="pagination-btn" ${this.currentPage === 1 ? 'disabled' : ''} 
                    onclick="userManager.changePage(${this.currentPage - 1})">
                <i class="bi bi-chevron-left"></i>
            </button>
        `;

        // Páginas
        const maxButtons = 5;
        let startPage = Math.max(1, this.currentPage - Math.floor(maxButtons / 2));
        let endPage = Math.min(totalPages, startPage + maxButtons - 1);

        if (endPage - startPage + 1 < maxButtons) {
            startPage = Math.max(1, endPage - maxButtons + 1);
        }

        for (let i = startPage; i <= endPage; i++) {
            buttonsHTML += `
                <button class="pagination-btn ${i === this.currentPage ? 'active' : ''}" 
                        onclick="userManager.changePage(${i})">
                    ${i}
                </button>
            `;
        }

        // Próximo
        buttonsHTML += `
            <button class="pagination-btn" ${this.currentPage === totalPages ? 'disabled' : ''} 
                    onclick="userManager.changePage(${this.currentPage + 1})">
                <i class="bi bi-chevron-right"></i>
            </button>
        `;

        controls.innerHTML = buttonsHTML;
    }

    changePage(page) {
        const totalPages = Math.ceil(this.filteredUsers.length / this.itemsPerPage);
        if (page >= 1 && page <= totalPages) {
            this.currentPage = page;
            this.renderUsers();
        }
    }

    updateSortIcons() {
        const headers = this.modal.querySelectorAll('.sortable');
        headers.forEach(header => {
            header.classList.remove('active', 'desc');
            if (header.dataset.sort === this.sortBy) {
                header.classList.add('active');
                if (this.sortOrder === 'desc') {
                    header.classList.add('desc');
                }
            }
        });
    }

    async editUser(userId) {
        // Implementar modal de edição
        console.log('Editar usuário:', userId);
        this.showNotification('Funcionalidade de edição em desenvolvimento', 'info');
    }

    async toggleUser(userId) {
        try {
            const user = this.users.find(u => u.id === userId);
            const newStatus = !user.ativo;

            const response = await fetch('../backend/toggle_user.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({
                    userId: userId,
                    status: newStatus
                })
            });

            if (response.ok) {
                user.ativo = newStatus;
                this.renderUsers();
                this.updateStats();
                this.showNotification(`Usuário ${newStatus ? 'ativado' : 'desativado'} com sucesso!`, 'success');
            } else {
                throw new Error('Erro ao alterar status');
            }
        } catch (error) {
            console.error('Erro:', error);
            this.showError('Erro ao alterar status do usuário');
        }
    }

    async deleteUser(userId) {
        if (!confirm('Tem certeza que deseja excluir este usuário?\nEsta ação não pode ser desfeita.')) {
            return;
        }

        try {
            const response = await fetch('../backend/delete_user.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({ userId })
            });

            if (response.ok) {
                this.users = this.users.filter(u => u.id !== userId);
                this.filterAndRender();
                this.updateStats();
                this.showNotification('Usuário excluído com sucesso!', 'success');
            } else {
                throw new Error('Erro ao excluir usuário');
            }
        } catch (error) {
            console.error('Erro:', error);
            this.showError('Erro ao excluir usuário');
        }
    }

    showNotification(message, type = 'info') {
        // Usar a função de notificação existente do header
        if (typeof showNotification === 'function') {
            showNotification(message, type);
        } else {
            alert(message);
        }
    }

    showError(message) {
        this.showNotification(message, 'error');
    }
}

// Criar instância global
window.userManager = new UserManager();