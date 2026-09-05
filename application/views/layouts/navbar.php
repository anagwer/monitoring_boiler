<?php
$role = $this->session->userdata('role');
$user_name = $this->session->userdata('name') ? $this->session->userdata('name') : 'Pengguna';
$role_badge = ($role === 'pemilik') ? '<span class="badge bg-warning text-dark px-2 py-1">Pemilik / Owner</span>' : '<span class="badge bg-success px-2 py-1">Admin / ABK</span>';
?>

<!-- ==========================================
     START: Top Navbar Component
     ========================================== -->
<header class="navbar-custom">
  <div class="navbar-left">
    <!-- Desktop sidebar toggle (visible on large screens only) -->
    <button class="btn-desktop-toggle d-none d-xl-flex align-items-center justify-content-center me-3"
      id="desktop-sidebar-toggle" aria-label="Minimize Sidebar">
      <i class="bi bi-chevron-bar-left"></i>
    </button>
    <!-- Mobile sidebar toggle -->
    <button class="sidebar-toggle-btn me-2" id="sidebar-toggle" aria-label="Toggle Navigation">
      <i class="bi bi-list"></i>
    </button>

    <!-- Quick Actions Dropdown -->
    <div class="dropdown ms-2">
      <button class="btn-quick-action dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false"
        id="quick-actions-dropdown">
        <i class="bi bi-plus-lg"></i>
        <span>Aksi Cepat</span>
      </button>
      <ul class="dropdown-menu dropdown-menu-quick-action" aria-labelledby="quick-actions-dropdown">
        <li class="dropdown-header">Akses Cepat Peternakan</li>
        <?php if ($role === 'abk'): ?>
        <li><a class="dropdown-item" href="<?= base_url('harian/input') ?>"><i class="bi bi-pencil-square text-success"></i> Input Harian Baru</a></li>
        <?php endif; ?>
        <li><a class="dropdown-item" href="<?= base_url('periode/tambah') ?>"><i class="bi bi-clock-history text-primary"></i> Setup Periode Panen</a></li>
        <li><a class="dropdown-item" href="<?= base_url('kandang/tambah') ?>"><i class="bi bi-house-add text-warning"></i> Tambah Kandang</a></li>
        <li>
          <hr class="dropdown-divider">
        </li>
        <li><a class="dropdown-item" href="<?= base_url('laporan') ?>"><i class="bi bi-file-earmark-pdf text-danger"></i> Laporan Produksi</a></li>
      </ul>
    </div>
  </div>

  <!-- Mid navbar: Farm Info / Live Status -->
  <div class="d-none d-lg-flex align-items-center gap-2 px-3 py-1 rounded-pill" style="background-color: var(--app-card-bg); border: 1px solid var(--app-card-border);">
    <span class="status-dot"></span>
    <span style="font-size: 0.8rem; font-weight: 600; color: var(--app-text-muted);">PT BTS Broiler Monitoring Live</span>
  </div>

  <!-- Right actions -->
  <div class="navbar-actions">
    <!-- Dark / Light Mode Toggle Switcher -->
    <button class="theme-toggle-btn me-2" id="theme-toggle" type="button" title="Ganti Mode Gelap / Terang" aria-label="Ganti Tema">
      <i class="bi bi-moon-stars-fill" id="theme-icon"></i>
    </button>

    <!-- Fullscreen Toggle -->
    <button class="navbar-action-btn me-1" aria-label="Toggle Fullscreen" id="btn-fullscreen" title="Layar Penuh">
      <i class="bi bi-arrows-fullscreen"></i>
    </button>

    <!-- Profile Dropdown -->
    <div class="dropdown ms-2">
      <button class="navbar-profile-btn dropdown-toggle" type="button" data-bs-toggle="dropdown"
        aria-expanded="false" id="profile-dropdown">
        <div class="d-flex align-items-center justify-content-center bg-success text-white rounded-circle me-1" style="width: 32px; height: 32px; font-size: 0.85rem; font-weight: 700; background: linear-gradient(135deg, #10b981, #065f46) !important;">
          <?= strtoupper(substr($user_name, 0, 1)) ?>
        </div>
        <span class="navbar-profile-name d-none d-md-inline" style="color: var(--app-text-main); font-weight: 600;"><?= htmlspecialchars($user_name) ?></span>
        <i class="bi bi-chevron-down navbar-profile-caret"></i>
      </button>
      <ul class="dropdown-menu dropdown-menu-end dropdown-menu-profile" aria-labelledby="profile-dropdown">
        <li class="dropdown-header">
          <div class="fw-bold" style="color: var(--app-text-main);"><?= htmlspecialchars($user_name) ?></div>
          <div class="mt-1"><?= $role_badge ?></div>
        </li>
        <li>
          <hr class="dropdown-divider">
        </li>
        <li><a class="dropdown-item" href="<?= base_url('dashboard') ?>"><i class="bi bi-speedometer2"></i> Dashboard</a></li>
        <li><a class="dropdown-item" href="<?= base_url('laporan') ?>"><i class="bi bi-file-earmark-text"></i> Laporan</a></li>
        <li>
          <hr class="dropdown-divider">
        </li>
        <li>
          <a class="dropdown-item text-danger" href="<?= base_url('auth/logout') ?>" onclick="return confirm('Apakah Anda yakin ingin logout?');">
            <i class="bi bi-box-arrow-right"></i> Keluar (Logout)
          </a>
        </li>
      </ul>
    </div>
  </div>
</header>
<!-- END: Top Navbar Component -->
