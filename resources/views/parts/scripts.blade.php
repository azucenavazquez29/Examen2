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