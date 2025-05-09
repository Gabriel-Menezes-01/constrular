document.getElementById('show-users').addEventListener('click', function (e) {
    e.preventDefault();
    document.getElementById('user-modal').style.display = 'block';
  });

  document.getElementById('close-modal').addEventListener('click', function () {
    document.getElementById('user-modal').style.display = 'none';
  });

  window.addEventListener('click', function (e) {
    if (e.target === document.getElementById('user-modal')) {
      document.getElementById('user-modal').style.display = 'none';
    }
  });