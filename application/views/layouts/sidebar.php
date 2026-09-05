<?php
$role = $this->session->userdata('role');
$user_name = $this->session->userdata('name') ? $this->session->userdata('name') : 'Pengguna';
$role_label = ($role === 'pemilik') ? 'Pemilik / Manajemen' : 'Admin / ABK';
$active = isset($active_menu) ? $active_menu : '';
?>

<!-- ==========================================
     START: Sidebar Component
     ========================================== -->
<div class="sidebar-wrapper" id="sidebar">
  <!-- Brand Logo / Identity -->
  <a href="<?= base_url('dashboard') ?>" class="sidebar-brand">
    <i class="bi bi-egg-fried text-warning fs-3"></i>
    <div class="d-flex flex-column">
      <span style="font-size: 1.1rem; font-weight: 800; line-height: 1.2;">PT BTS</span>
      <span style="font-size: 0.68rem; letter-spacing: 0.08em; color: #B4F105; font-weight: 600;">BROILER MONITORING</span>
    </div>
  </a>

  <!-- Navigation Menu -->
  <div class="flex-grow-1 overflow-y-auto">
    <!-- Group: Menu Utama -->
    <div class="sidebar-menu-section">
      <div class="sidebar-menu-title">Menu Utama</div>
      <ul class="sidebar-menu-list">
        <li class="sidebar-menu-item">
          <a href="<?= base_url('dashboard') ?>" class="sidebar-menu-link <?= ($active === 'dashboard') ? 'active' : '' ?>" id="menu-dashboard" title="Dashboard">
            <i class="bi bi-grid-fill"></i>
            <span>Dashboard</span>
          </a>
        </li>
      </ul>
    </div>

    <!-- Group: Operasional Harian -->
    <div class="sidebar-menu-section">
      <div class="sidebar-menu-title">Operasional Kandang</div>
      <ul class="sidebar-menu-list">
        <?php if ($role === 'abk'): ?>
        <li class="sidebar-menu-item">
          <a href="<?= base_url('harian/input') ?>" class="sidebar-menu-link <?= ($active === 'harian_input') ? 'active' : '' ?>" id="menu-input-harian" title="Form Input Harian">
            <i class="bi bi-pencil-square"></i>
            <span>Input Harian</span>
          </a>
        </li>
        <?php endif; ?>
        <li class="sidebar-menu-item">
          <a href="<?= base_url('harian') ?>" class="sidebar-menu-link <?= ($active === 'harian') ? 'active' : '' ?>" id="menu-riwayat-harian" title="Riwayat Input Harian">
            <i class="bi bi-journal-text"></i>
            <span>Riwayat Harian</span>
          </a>
        </li>
      </ul>
    </div>

    <!-- Group: Data Master & Setup -->
    <div class="sidebar-menu-section">
      <div class="sidebar-menu-title">Data Master & Batch</div>
      <ul class="sidebar-menu-list">
        <li class="sidebar-menu-item">
          <a href="<?= base_url('periode') ?>" class="sidebar-menu-link <?= ($active === 'periode') ? 'active' : '' ?>" id="menu-periode" title="Data Periode Panen">
            <i class="bi bi-clock-history"></i>
            <span>Periode Panen (Batch)</span>
          </a>
        </li>
        <li class="sidebar-menu-item">
          <a href="<?= base_url('kandang') ?>" class="sidebar-menu-link <?= ($active === 'kandang') ? 'active' : '' ?>" id="menu-kandang" title="Data Kandang">
            <i class="bi bi-house-door-fill"></i>
            <span>Data Kandang</span>
          </a>
        </li>
        <li class="sidebar-menu-item">
          <a href="<?= base_url('pengguna') ?>" class="sidebar-menu-link <?= ($active === 'pengguna') ? 'active' : '' ?>" id="menu-pengguna" title="Manajemen Pengguna">
            <i class="bi bi-people-fill"></i>
            <span>Data Pengguna</span>
          </a>
        </li>
      </ul>
    </div>

    <!-- Group: Laporan & Analitik -->
    <div class="sidebar-menu-section">
      <div class="sidebar-menu-title">Laporan & Output PT</div>
      <ul class="sidebar-menu-list">
        <li class="sidebar-menu-item">
          <a href="<?= base_url('laporan') ?>" class="sidebar-menu-link <?= ($active === 'laporan') ? 'active' : '' ?>" id="menu-laporan" title="Laporan Produksi">
            <i class="bi bi-file-earmark-bar-graph-fill"></i>
            <span>Laporan Produksi</span>
          </a>
        </li>
      </ul>
    </div>
  </div>

  <!-- Sidebar Profile Card (Dynamic Footer) -->
  <div class="sidebar-profile">
    <div class="d-flex align-items-center justify-content-center bg-success text-white rounded-circle me-2" style="width: 40px; height: 40px; font-weight: 700; flex-shrink: 0; background: linear-gradient(135deg, #10b981, #065f46) !important;">
      <?= strtoupper(substr($user_name, 0, 1)) ?>
    </div>
    <div class="sidebar-profile-info flex-grow-1 overflow-hidden">
      <div class="sidebar-profile-name text-truncate"><?= htmlspecialchars($user_name) ?></div>
      <div class="sidebar-profile-email" style="font-size: 0.72rem; color: #B4F105;"><?= $role_label ?></div>
    </div>
    <a href="<?= base_url('auth/logout') ?>" class="text-danger ms-1" title="Logout" onclick="return confirm('Apakah Anda yakin ingin logout?');">
      <i class="bi bi-box-arrow-right fs-5"></i>
    </a>
  </div>
</div>
<!-- ==========================================
     END: Sidebar Component
     ========================================== -->
