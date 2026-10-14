<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Lupa Password - PT BTS Monitoring Ayam Broiler</title>

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
       START: Reset Password Container & Card
       ========================================== -->
  <div class="login-wrapper">
    <!-- Glowing background shapes for modern visual appearance -->
    <div class="login-bg-shape login-bg-shape-1"></div>
    <div class="login-bg-shape login-bg-shape-2"></div>

    <!-- Main centered reset password card -->
    <div class="login-card" style="max-width: 480px;">

      <!-- Brand Identity -->
      <div class="text-center mb-4">
        <div class="d-inline-flex align-items-center justify-content-center bg-dark p-3 rounded-circle border border-success mb-2 shadow" style="width: 70px; height: 70px;">
          <i class="bi bi-key-fill text-warning" style="font-size: 2.2rem;"></i>
        </div>
        <h2 class="text-white fw-bold mb-1" style="letter-spacing: -0.02em;">Reset Password</h2>
        <p class="text-muted-green mb-0" style="font-size: 0.85rem;">Verifikasi identitas akun untuk membuat password baru</p>
      </div>

      <!-- Flash Notification Alerts -->
      <?php if ($this->session->flashdata('error')): ?>
        <div class="alert alert-danger border-0 d-flex align-items-center gap-2 mb-3" role="alert" style="background: rgba(239, 68, 68, 0.15); border-left: 4px solid #ef4444 !important; color: #fca5a5; border-radius: 8px; font-size: 0.875rem;">
          <i class="bi bi-exclamation-triangle-fill text-danger flex-shrink-0"></i>
          <div><?= $this->session->flashdata('error') ?></div>
        </div>
      <?php endif; ?>

      <?php if ($this->session->flashdata('success')): ?>
        <div class="alert alert-success border-0 d-flex align-items-center gap-2 mb-3" role="alert" style="background: rgba(16, 185, 129, 0.15); border-left: 4px solid #10b981 !important; color: #a7f3d0; border-radius: 8px; font-size: 0.875rem;">
          <i class="bi bi-check-circle-fill text-success flex-shrink-0"></i>
          <div><?= $this->session->flashdata('success') ?></div>
        </div>
      <?php endif; ?>

      <!-- Form Reset Password -->
      <form action="<?= base_url('auth/lupa_password') ?>" method="POST" id="resetPasswordForm">

        <!-- Username Input Group -->
        <div class="login-form-group mb-3">
          <label for="username" class="login-form-label">Username Akun</label>
          <div class="login-input-group">
            <i class="bi bi-person input-icon"></i>
            <input type="text" name="username" id="username" class="login-input" placeholder="Masukkan username Anda" value="<?= set_value('username') ?>" required autofocus autocomplete="username">
          </div>
          <?= form_error('username', '<small class="text-danger mt-1 d-block">', '</small>') ?>
        </div>

        <!-- Phone / WhatsApp Input Group -->
        <div class="login-form-group mb-3">
          <label for="phone" class="login-form-label">No. WhatsApp / HP Terdaftar</label>
          <div class="login-input-group">
            <i class="bi bi-phone input-icon"></i>
            <input type="text" name="phone" id="phone" class="login-input" placeholder="Contoh: 081234567890" value="<?= set_value('phone') ?>" required autocomplete="tel">
          </div>
          <small class="text-muted-green d-block mt-1" style="font-size: 0.75rem;">*Nomor HP yang tercatat saat pendaftaran akun</small>
          <?= form_error('phone', '<small class="text-danger mt-1 d-block">', '</small>') ?>
        </div>

        <!-- New Password Input Group -->
        <div class="login-form-group mb-3">
          <label for="new_password" class="login-form-label">Password Baru</label>
          <div class="login-input-group">
            <i class="bi bi-shield-lock input-icon"></i>
            <input type="password" name="password" id="new_password" class="login-input login-input-password" placeholder="Minimal 4 karakter" required autocomplete="new-password">
            <button type="button" class="password-toggle-btn" id="toggle-new-password" aria-label="Lihat password">
              <i class="bi bi-eye" id="eye-icon-new"></i>
            </button>
          </div>
          <?= form_error('password', '<small class="text-danger mt-1 d-block">', '</small>') ?>
        </div>

        <!-- Confirm New Password Input Group -->
        <div class="login-form-group mb-4">
          <label for="passconf" class="login-form-label">Konfirmasi Password Baru</label>
          <div class="login-input-group">
            <i class="bi bi-shield-check input-icon"></i>
            <input type="password" name="passconf" id="passconf" class="login-input login-input-password" placeholder="Ulangi password baru" required autocomplete="new-password">
            <button type="button" class="password-toggle-btn" id="toggle-conf-password" aria-label="Lihat password">
              <i class="bi bi-eye" id="eye-icon-conf"></i>
            </button>
          </div>
          <?= form_error('passconf', '<small class="text-danger mt-1 d-block">', '</small>') ?>
        </div>

        <!-- Submit Button -->
        <button type="submit" class="btn-login w-100 mb-3" id="btn-submit">
          <span>Simpan & Reset Password</span>
          <i class="bi bi-arrow-right"></i>
        </button>

        <!-- Back to Login Link -->
        <a href="<?= base_url('auth') ?>" class="btn w-100 d-flex align-items-center justify-content-center gap-2" style="background: rgba(255,255,255,0.05); color: #cbd5e1; border: 1px solid rgba(255,255,255,0.1); border-radius: 14px; padding: 0.75rem; font-weight: 600; font-size: 0.9rem; text-decoration: none; transition: all 0.2s;">
          <i class="bi bi-arrow-left"></i>
          <span>Kembali ke Halaman Login</span>
        </a>

      </form>

      <!-- Petunjuk Kontak Pemilik / Bantuan Offline -->
      <?php if (!empty($pemilik) && !empty($pemilik->phone)): ?>
        <?php
          $raw_phone = preg_replace('/[^0-9]/', '', $pemilik->phone);
          $wa_phone = preg_replace('/^0/', '62', $raw_phone);
        ?>
        <div class="mt-4 p-3 rounded" style="background-color: rgba(255,255,255,0.03); border: 1px dashed rgba(180, 241, 5, 0.25);">
          <div class="text-muted-green fw-semibold mb-1" style="font-size: 0.78rem; text-transform: uppercase;">
            <i class="bi bi-headset me-1 text-warning"></i> Mengalami Kendala Reset?
          </div>
          <div class="small text-light mb-2" style="font-size: 0.8rem;">
            Jika Anda lupa nomor HP yang terdaftar, hubungi Pemilik Peternakan untuk bantuan reset manual:
          </div>
          <a href="https://wa.me/<?= $wa_phone ?>?text=Halo%20Pak%20<?= urlencode($pemilik->name) ?>,%20saya%20butuh%20bantuan%20reset%20password%20akun%20aplikasi%20monitoring%20ayam." target="_blank" class="btn btn-sm btn-outline-success d-inline-flex align-items-center gap-1 w-100 justify-content-center" style="font-size: 0.8rem; border-radius: 8px;">
            <i class="bi bi-whatsapp text-success"></i>
            <span>Hubungi Pemilik (<?= htmlspecialchars($pemilik->name) ?>)</span>
          </a>
        </div>
      <?php endif; ?>

    </div>
  </div>
  <!-- END: Reset Password Container -->

  <!-- Script dependencies -->
  <script src="<?= base_url('assets/libs/bootstrap/js/bootstrap.bundle.min.js') ?>"></script>
  <script>
    function setupTogglePassword(btnId, inputId, iconId) {
      const toggleBtn = document.getElementById(btnId);
      const input = document.getElementById(inputId);
      const icon = document.getElementById(iconId);

      if (toggleBtn && input && icon) {
        toggleBtn.addEventListener('click', function () {
          const type = input.getAttribute('type') === 'password' ? 'text' : 'password';
          input.setAttribute('type', type);
          icon.classList.toggle('bi-eye');
          icon.classList.toggle('bi-eye-slash');
        });
      }
    }

    setupTogglePassword('toggle-new-password', 'new_password', 'eye-icon-new');
    setupTogglePassword('toggle-conf-password', 'passconf', 'eye-icon-conf');
  </script>
</body>
</html>
