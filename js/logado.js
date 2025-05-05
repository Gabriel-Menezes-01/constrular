const openLogin = document.querySelector('.login');
const openCadrasto = document.querySelector('.cadrasto');

openLogin.addEventListener('click', function() {
    window.location.href = '../login/login.php';
});
openCadrasto.addEventListener('click', function() {
    window.location.href = '../login/cadastro.php';
});