<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js" integrity="sha384-ndDqU0Gzau9qJ1lfW4pNLlhNTkCfHzAVBReH9diLvGRem5+R9g2FzA8ZGN954O5Q" crossorigin="anonymous"></script>

<script>
  const navbar = document.getElementById('navbar');
  const navbarOffsetTop = navbar.offsetTop;

  window.addEventListener('scroll', () => {
    if (window.scrollY >= navbarOffsetTop + navbar.offsetHeight) {
      navbar.classList.add('fixed-top', 'shadow');
    } else {
      navbar.classList.remove('fixed-top', 'shadow');
    }
  });
</script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const loginForm = document.getElementById('loginForm');
        const registerForm = document.getElementById('registerForm');
        const modalTitle = document.getElementById('modalTitle');
        const showRegisterBtn = document.getElementById('showRegister');
        const showLoginBtn = document.getElementById('showLogin');

        if (showRegisterBtn) {
            showRegisterBtn.addEventListener('click', function(e) {
                e.preventDefault();
                loginForm.style.display = 'none';
                registerForm.style.display = 'block';
                modalTitle.textContent = 'Registrarme';
            });
        }

        if (showLoginBtn) {
            showLoginBtn.addEventListener('click', function(e) {
                e.preventDefault();
                registerForm.style.display = 'none';
                loginForm.style.display = 'block';
                modalTitle.textContent = 'Iniciar Sesión';
            });
        }

        // Resetear al cerrar el modal
        const modalElement = document.getElementById('modalLogin');
        if (modalElement) {
            modalElement.addEventListener('hidden.bs.modal', function() {
                if (registerForm && loginForm) {
                    registerForm.style.display = 'none';
                    loginForm.style.display = 'block';
                    modalTitle.textContent = 'Iniciar Sesión';
                }
            });
        }
    });
</script>
