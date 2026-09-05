<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login Peternakan Ayam Broiler - PT BTS</title>

  <!-- Favicon -->
  <link rel="icon" type="image/png" href="<?= base_url('assets/images/favicon.ico') ?>">

  <!-- Local Third-Party Libraries (100% Offline Compatible) -->
  <link rel="stylesheet" href="<?= base_url('assets/libs/bootstrap/css/bootstrap.min.css') ?>">
  <link rel="stylesheet" href="<?= base_url('assets/libs/bootstrap-icons/bootstrap-icons.css') ?>">

  <!-- Main Design System & Custom Stylesheet -->
  <link rel="stylesheet" href="<?= base_url('assets/css/main.css') ?>">
</head>
<body>

  <!-- ==========================================
       START: Authentication Container & Login Card
       ========================================== -->
  <div class="login-wrapper">
    <!-- Glowing background shapes for modern visual appearance -->
    <div class="login-bg-shape login-bg-shape-1"></div>
    <div class="login-bg-shape login-bg-shape-2"></div>

    <!-- Main centered login card -->
    <div class="login-card" style="max-width: 460px;">

      <!-- Brand Identity -->
      <div class="text-center mb-4">
        <div class="d-inline-flex align-items-center justify-content-center bg-dark p-3 rounded-circle border border-success mb-2 shadow" style="width: 70px; height: 70px;">
          <i class="bi bi-egg-fried text-warning" style="font-size: 2.5rem;"></i>
        </div>
        <h2 class="text-white fw-bold mb-1" style="letter-spacing: -0.02em;">PT BTS</h2>
        <p class="text-muted-green mb-0" style="font-size: 0.85rem; letter-spacing: 0.05em; text-transform: uppercase;">Sistem Monitoring Ayam Broiler</p>
      </div>

      <!-- Flash Notification Alerts -->
      <?php if ($this->session->flashdata('error')): ?>
        <div class="alert alert-danger border-0 d-flex align-items-center gap-2 mb-3" role="alert" style="background: rgba(239, 68, 68, 0.15); border-left: 4px solid #ef4444 !important; color: #fca5a5; border-radius: 8px; font-size: 0.875rem;">
          <i class="bi bi-exclamation-triangle-fill text-danger"></i>
          <div><?= $this->session->flashdata('error') ?></div>
        </div>
      <?php endif; ?>

      <?php if ($this->session->flashdata('success')): ?>
        <div class="alert alert-success border-0 d-flex align-items-center gap-2 mb-3" role="alert" style="background: rgba(16, 185, 129, 0.15); border-left: 4px solid #10b981 !important; color: #a7f3d0; border-radius: 8px; font-size: 0.875rem;">
          <i class="bi bi-check-circle-fill text-success"></i>
          <div><?= $this->session->flashdata('success') ?></div>
        </div>
      <?php endif; ?>

      <!-- Login Form -->
      <form action="<?= base_url('auth/login') ?>" method="POST" id="loginForm">

        <!-- Username Input Group -->
        <div class="login-form-group mb-3">
          <label for="username" class="login-form-label">Username</label>
          <div class="login-input-group">
            <i class="bi bi-person input-icon"></i>
            <input type="text" name="username" id="username" class="login-input" placeholder="Masukkan username" value="<?= set_value('username') ?>" required autofocus autocomplete="username">
          </div>
          <?= form_error('username', '<small class="text-danger mt-1 d-block">', '</small>') ?>
        </div>

        <!-- Password Input Group -->
        <div class="login-form-group mb-4">
          <label for="password" class="login-form-label">Password</label>
          <div class="login-input-group">
            <i class="bi bi-shield-lock input-icon"></i>
            <input type="password" name="password" id="password" class="login-input login-input-password" placeholder="••••••••" required autocomplete="current-password">
            <button type="button" class="password-toggle-btn" id="toggle-password" aria-label="Lihat password">
              <i class="bi bi-eye" id="eye-icon"></i>
            </button>
          </div>
          <?= form_error('password', '<small class="text-danger mt-1 d-block">', '</small>') ?>
        </div>

        <!-- Submit Button -->
        <button type="submit" class="btn-login w-100" id="btn-submit">
          <span>Masuk ke Dashboard</span>
          <i class="bi bi-arrow-right"></i>
        </button>

      </form>

      <!-- Petunjuk Akun Demo / Default -->
      <div class="mt-4 p-3 rounded" style="background-color: rgba(255,255,255,0.03); border: 1px dashed rgba(180, 241, 5, 0.25);">
        <div class="text-muted-green fw-semibold mb-2" style="font-size: 0.78rem; text-transform: uppercase;">
          <i class="bi bi-info-circle me-1 text-warning"></i> Akun Pengguna Default:
        </div>
        <div class="d-flex justify-content-between align-items-center mb-1" style="font-size: 0.8rem; color: #cbd5e1;">
          <span><strong>Pemilik:</strong> <code class="text-warning">pemilik</code></span>
          <span>Password: <code class="text-light">password</code></span>
        </div>
        <div class="d-flex justify-content-between align-items-center" style="font-size: 0.8rem; color: #cbd5e1;">
          <span><strong>ABK / Admin:</strong> <code class="text-success">abk</code></span>
          <span>Password: <code class="text-light">password</code></span>
        </div>
      </div>

    </div>
  </div>
  <!-- END: Authentication Container -->

  <!-- Script dependencies -->
  <script src="<?= base_url('assets/libs/bootstrap/js/bootstrap.bundle.min.js') ?>"></script>
  <script>
    const togglePassword = document.getElementById('toggle-password');
    const passwordInput = document.getElementById('password');
    const eyeIcon = document.getElementById('eye-icon');

    if (togglePassword && passwordInput) {
      togglePassword.addEventListener('click', function () {
        const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
        passwordInput.setAttribute('type', type);
        eyeIcon.classList.toggle('bi-eye');
        eyeIcon.classList.toggle('bi-eye-slash');
      });
    }
  </script>
</body>
</html>
