</div> <!-- 🔁 Cierra el <div class="flex"> abierto en header -->

<!-- ✅ Script para colapsar el sidebar -->
<script>
  document.addEventListener('DOMContentLoaded', () => {
    const btnToggleSidebar = document.getElementById('toggleSidebar');
    const sidebar = document.getElementById('sidebar');

    if (btnToggleSidebar && sidebar) {
      btnToggleSidebar.addEventListener('click', () => {
        sidebar.classList.toggle('-translate-x-full');
      });
    }
  });
</script>

</body>
</html>
