const login = document.querySelector('.login');
const openlogin = document.querySelector('.openlogin'); 
const abrircadastro = document.querySelector('.abrircadastro');
const fechaLogin = document.querySelector('.fechalogin');


const cadastro = document.querySelector('.cadastro');
const opencadastro = document.querySelector('.openCadastro');
const abrirlogin = document.querySelector('.abrirLogin');
const fechaCadastro = document.querySelector('.fechacadastro');


login.addEventListener('click', () => {
   openlogin.show();   
   opencadastro.close();
})
abrircadastro.addEventListener('click', () => {
   opencadastro.show();
   openlogin.close();
})
cadastro.addEventListener('click', () => {
   opencadastro.show();
   openlogin.close();
})
abrirlogin.addEventListener('click', () => {
   openlogin.show();
   opencadastro.close();
})
fechaLogin.addEventListener('click', () => {
   openlogin.close();
})
fechaCadastro.addEventListener('click', () => {
   opencadastro.close();
})
