const usuarioId = document.querySelector('.nav__item');
const btnimg = document.querySelector('.upload-img');

// Função para validar se o usuário com ID 1 está logado
function validarUsuarioLogado() {
    fetch('./backend/validarUsuario.php') // Endpoint para validar o usuário logado
        .then(response => {
            if (!response.ok) {
                throw new Error('Erro ao validar o usuário logado.');
            }
            return response.json();
        })
        .then(data => {
            if (data.usuarioId === 1) {
                // Usuário com ID 1 está logado
                usuarioId.style.display = 'block';
                btnimg.style.display = 'block';
            } else {
                // Não é o usuário com ID 1
                usuarioId.style.display = 'none';
                btnimg.style.display = 'none';
            }
        })
        .catch(error => {
            console.error('Erro:', error);
        });
}

// Chamar a função para validar o usuário logado
validarUsuarioLogado();
