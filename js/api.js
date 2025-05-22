function buscarEmailLogado(callback) {
fetch('http://localhost/constrular/frontLogado/Inicio.php', {
    method: 'POST',
    headers: {'Content-Type': 'application/x-www-form-urlencoded'},
    body: 'get_logged_email=1'
  })
  .then(response => response.json())
  .then(data => {
    if (callback) callback(data.email);

  console.log('Email logado:', data.email);
  });
}

// buscarEmailLogado(function(email) {
//   // Mostra o botão "Ver Usuários" apenas para o admin
//   var showUsersElems = document.querySelectorAll('.users');
//   if (email === 'admin@gmail.com') {
//     showUsersElems.forEach(function(elem) {
//       elem.style.display = 'block';
//     });
//   } else {
//     showUsersElems.forEach(function(elem) {
//       elem.style.display = 'none';
//     });
//   }
// });
