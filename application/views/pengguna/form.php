<?php
$is_edit = isset($user);
$action_url = $is_edit ? base_url('pengguna/edit/' . $user->id) : base_url('pengguna/tambah');
?>

<div class="page-header d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
  <div>
    <h1 class="page-title text-white fw-bold"><?= $is_edit ? 'Edit Data Pengguna' : 'Tambah Pengguna Baru' ?></h1>
    <p class="page-subtitle text-muted-green mb-0">Atur kredensial login, nama akun, dan penetapan role hak akses.</p>
  </div>
  <a href="<?= base_url('pengguna') ?>" class="btn-brand-outline">
    <i class="bi bi-arrow-left me-1"></i> Kembali ke Daftar
  </a>
</div>

<div class="row justify-content-center">
  <div class="col-lg-8">
    <div class="card p-4" style="background-color: #0c1a14; border: 1px solid #1e3627; border-radius: 16px;">
      <form action="<?= $action_url ?>" method="POST">
        <div class="row g-3">
          <!-- Nama Lengkap -->
          <div class="col-md-6">
            <label for="name" class="form-label form-label-custom">Nama Lengkap <span class="text-danger">*</span></label>
            <input type="text" name="name" id="name" class="form-control form-control-modern" placeholder="Contoh: Agus Setiawan" value="<?= set_value('name', $is_edit ? $user->name : '') ?>" required>
            <?= form_error('name', '<small class="text-danger">', '</small>') ?>
          </div>

          <!-- Username -->
          <div class="col-md-6">
            <label for="username" class="form-label form-label-custom">Username Login <span class="text-danger">*</span></label>
            <input type="text" name="username" id="username" class="form-control form-control-modern" placeholder="Contoh: agus_abk" value="<?= set_value('username', $is_edit ? $user->username : '') ?>" required>
            <?= form_error('username', '<small class="text-danger">', '</small>') ?>
          </div>

          <!-- Password -->
          <div class="col-md-6">
            <label for="password" class="form-label form-label-custom">Password <?= $is_edit ? '<small class="text-muted-green">(Kosongkan jika tidak ingin mengubah)</small>' : '<span class="text-danger">*</span>' ?></label>
            <input type="password" name="password" id="password" class="form-control form-control-modern" placeholder="••••••••" <?= $is_edit ? '' : 'required' ?>>
            <?= form_error('password', '<small class="text-danger">', '</small>') ?>
          </div>

          <!-- Role -->
          <div class="col-md-6">
            <label for="role" class="form-label form-label-custom">Role Hak Akses <span class="text-danger">*</span></label>
            <select name="role" id="role" class="form-select form-select-modern" required>
              <option value="abk" <?= (set_value('role', $is_edit ? $user->role : '') === 'abk') ? 'selected' : '' ?>>Admin / ABK (Anak Buah Kandang - Input Harian)</option>
              <option value="pemilik" <?= (set_value('role', $is_edit ? $user->role : '') === 'pemilik') ? 'selected' : '' ?>>Pemilik / Manajemen (Monitoring & Laporan)</option>
            </select>
          </div>

          <!-- No HP -->
          <div class="col-md-6">
            <label for="phone" class="form-label form-label-custom">No. WhatsApp / HP</label>
            <input type="text" name="phone" id="phone" class="form-control form-control-modern" placeholder="08xxxxxxxxxx" value="<?= set_value('phone', $is_edit ? $user->phone : '') ?>">
          </div>

          <!-- Status -->
          <div class="col-md-6">
            <label for="status" class="form-label form-label-custom">Status Akun</label>
            <select name="status" id="status" class="form-select form-select-modern">
              <option value="aktif" <?= (set_value('status', $is_edit ? $user->status : '') === 'aktif') ? 'selected' : '' ?>>Aktif (Dapat Login)</option>
              <option value="nonaktif" <?= (set_value('status', $is_edit ? $user->status : '') === 'nonaktif') ? 'selected' : '' ?>>Non-Aktif (Diblokir)</option>
            </select>
          </div>

          <div class="col-12 mt-4 pt-3 border-top border-dark d-flex justify-content-end gap-2">
            <a href="<?= base_url('pengguna') ?>" class="btn btn-outline-secondary">Batal</a>
            <button type="submit" class="btn-brand-accent">
              <i class="bi bi-save me-1"></i> <?= $is_edit ? 'Simpan Perubahan' : 'Tambah Pengguna' ?>
            </button>
          </div>
        </div>
      </form>
    </div>
  </div>
</div>
