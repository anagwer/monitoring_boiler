    <!-- START: Footer Component -->
    <footer class="footer-custom mt-5">
      <div class="footer-left">
        <span class="footer-logo">
          <i class="bi bi-egg-fried text-warning"></i> PT BTS • Farm Monitoring
        </span>
        <span class="footer-separator">|</span>
        <span class="footer-copy">&copy; <?= date('Y') ?> PT Berkah Ternak Sejahtera (PT BTS) • Sistem Informasi Peternakan Ayam Broiler</span>
      </div>
      <div class="footer-right">
        <ul class="footer-links">
          <li><a href="<?= base_url('dashboard') ?>" class="footer-link">Dashboard</a></li>
          <li><a href="<?= base_url('laporan') ?>" class="footer-link">Laporan Produksi</a></li>
          <li><a href="<?= base_url('periode') ?>" class="footer-link">Status Batch <span class="status-dot"></span></a></li>
        </ul>
      </div>
    </footer>
    <!-- END: Footer Component -->

  </div>
  <!-- ==========================================
       END: Main Content Area
       ========================================== -->

  <!-- Local Third-Party Libraries Script dependencies -->
  <script src="<?= base_url('assets/libs/jquery/jquery.min.js') ?>"></script>
  <script src="<?= base_url('assets/libs/bootstrap/js/bootstrap.bundle.min.js') ?>"></script>
  <script src="<?= base_url('assets/libs/apexcharts/apexcharts.min.js') ?>"></script>
  <script src="<?= base_url('assets/libs/flatpickr/flatpickr.min.js') ?>"></script>

  <!-- DataTables Scripts -->
  <script src="<?= base_url('assets/libs/datatables/jquery.dataTables.min.js') ?>"></script>
  <script src="<?= base_url('assets/libs/datatables-bs4/js/dataTables.bootstrap4.min.js') ?>"></script>
  <script src="<?= base_url('assets/libs/datatables-responsive/js/dataTables.responsive.min.js') ?>"></script>
  <script src="<?= base_url('assets/libs/datatables-responsive/js/responsive.bootstrap4.min.js') ?>"></script>

  <script>
    document.addEventListener('DOMContentLoaded', function () {
      // Toggle sidebar mobile
      const sidebarToggle = document.getElementById('sidebar-toggle');
      const sidebar = document.getElementById('sidebar');
      if (sidebarToggle && sidebar) {
        sidebarToggle.addEventListener('click', function (e) {
          e.stopPropagation();
          sidebar.classList.toggle('show');
        });

        // Close mobile sidebar on click outside
        document.addEventListener('click', function (e) {
          if (window.innerWidth < 1200 && sidebar.classList.contains('show')) {
            if (!sidebar.contains(e.target) && !sidebarToggle.contains(e.target)) {
              sidebar.classList.remove('show');
            }
          }
        });
      }

      // Toggle sidebar desktop minimize
      const desktopToggle = document.getElementById('desktop-sidebar-toggle');
      if (desktopToggle) {
        desktopToggle.addEventListener('click', function () {
          document.body.classList.toggle('sidebar-minimized');

          const icon = desktopToggle.querySelector('i');
          if (icon) {
            if (document.body.classList.contains('sidebar-minimized')) {
              icon.className = 'bi bi-chevron-bar-right';
            } else {
              icon.className = 'bi bi-chevron-bar-left';
            }
          }

          // Trigger window resize so ApexCharts & DataTables redraw nicely
          setTimeout(function () {
            window.dispatchEvent(new Event('resize'));
          }, 200);
        });
      }

      // Fullscreen toggle
      const fullscreenBtn = document.getElementById('btn-fullscreen');
      if (fullscreenBtn) {
        fullscreenBtn.addEventListener('click', function () {
          if (!document.fullscreenElement) {
            document.documentElement.requestFullscreen().catch(err => console.log(err));
          } else {
            if (document.exitFullscreen) {
              document.exitFullscreen();
            }
          }
        });
      }

      // Dark / Light Mode Switcher
      const themeToggle = document.getElementById('theme-toggle');
      const themeIcon = document.getElementById('theme-icon');

      function updateThemeIcon(theme) {
        if (!themeIcon) return;
        if (theme === 'dark') {
          themeIcon.className = 'bi bi-sun-fill text-warning';
        } else {
          themeIcon.className = 'bi bi-moon-stars-fill text-dark';
        }
      }

      const currentTheme = localStorage.getItem('bts_theme') || 'light';
      updateThemeIcon(currentTheme);

      if (themeToggle) {
        themeToggle.addEventListener('click', function () {
          const activeTheme = document.documentElement.getAttribute('data-bs-theme');
          const newTheme = (activeTheme === 'dark') ? 'light' : 'dark';

          document.documentElement.setAttribute('data-bs-theme', newTheme);
          if (newTheme === 'dark') {
            document.documentElement.classList.add('dark-mode');
          } else {
            document.documentElement.classList.remove('dark-mode');
          }

          localStorage.setItem('bts_theme', newTheme);
          updateThemeIcon(newTheme);
        });
      }

      // Initialize DataTables on all .datatable elements
      if (typeof $ !== 'undefined' && $.fn.DataTable) {
        $('.datatable').DataTable({
          responsive: true,
          autoWidth: false,
          pageLength: 10,
          lengthMenu: [[10, 25, 50, -1], [10, 25, 50, "Semua"]],
          language: {
            search: "_INPUT_",
            searchPlaceholder: "Cari data...",
            lengthMenu: "Tampilkan _MENU_ data",
            info: "Menampilkan _START_ sampai _END_ dari _TOTAL_ data",
            infoEmpty: "Menampilkan 0 sampai 0 dari 0 data",
            infoFiltered: "(difilter dari _MAX_ total data)",
            zeroRecords: "Tidak ada data yang cocok",
            emptyTable: "Tidak ada data yang tersedia pada tabel",
            paginate: {
              first: "Awal",
              last: "Akhir",
              next: "<i class='bi bi-chevron-right'></i>",
              previous: "<i class='bi bi-chevron-left'></i>"
            }
          }
        });
      }
    });
  </script>
</body>
</html>
