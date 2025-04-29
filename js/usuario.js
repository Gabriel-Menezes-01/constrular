const inputuser = document.querySelector(".input");
const btncadrasto = document.querySelector(".btn-cadastro"); 
const erroSpan = document.querySelector(".span");

btncadrasto.addEventListener("click", function (event) {
  event.preventDefault(); // Impede o envio do formulário
  const inputValue = inputuser.value.trim(); // Remove espaços em branco do início e do fim

  if (inputValue === "") {
    erroSpan.textContent = "Campo obrigatório"; // Exibe mensagem de erro
    erroSpan.style.color = "red"; // Define a cor da mensagem de erro como vermelho
  } else {
    erroSpan.textContent = ""; // Limpa a mensagem de erro
    window.location.href = "../login/cadrasto.html"; // Redireciona para a página de login
  }
});

