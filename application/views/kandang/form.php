<?php
$is_edit = isset($kandang);
$action_url = $is_edit ? base_url('kandang/edit/' . $kandang->id) : base_url('kandang/tambah');
?>

<div class="page-header d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
  <div>
    <h1 class="page-title text-white fw-bold"><?= $is_edit ? 'Edit Data Kandang' : 'Tambah Kandang Baru' ?></h1>
    <p class="page-subtitle text-muted-green mb-0">Isi informasi spesifikasi teknis unit kandang dan kapasitas populasi.</p>
  </div>
  <a href="<?= base_url('kandang') ?>" class="btn-brand-outline">
    <i class="bi bi-arrow-left me-1"></i> Kembali ke Daftar
  </a>
</div>

<div class="row justify-content-center">
  <div class="col-lg-8">
    <div class="card p-4" style="background-color: #0c1a14; border: 1px solid #1e3627; border-radius: 16px;">
      <form action="<?= $action_url ?>" method="POST">
        <div class="row g-3">
          <!-- Kode Kandang -->
          <div class="col-md-6">
            <label for="kode_kandang" class="form-label form-label-custom">Kode Kandang <span class="text-danger">*</span></label>
            <input type="text" name="kode_kandang" id="kode_kandang" class="form-control form-control-modern" placeholder="Contoh: KDG-04" value="<?= set_value('kode_kandang', $is_edit ? $kandang->kode_kandang : '') ?>" required>
            <?= form_error('kode_kandang', '<small class="text-danger">', '</small>') ?>
          </div>

          <!-- Nama Kandang -->
          <div class="col-md-6">
            <label for="nama_kandang" class="form-label form-label-custom">Nama / Unit Kandang <span class="text-danger">*</span></label>
            <input type="text" name="nama_kandang" id="nama_kandang" class="form-control form-control-modern" placeholder="Contoh: Kandang Unit Delta" value="<?= set_value('nama_kandang', $is_edit ? $kandang->nama_kandang : '') ?>" required>
            <?= form_error('nama_kandang', '<small class="text-danger">', '</small>') ?>
          </div>

          <!-- Kapasitas -->
          <div class="col-md-6">
            <label for="kapasitas" class="form-label form-label-custom">Kapasitas Populasi (Ekor) <span class="text-danger">*</span></label>
            <input type="number" name="kapasitas" id="kapasitas" class="form-control form-control-modern" placeholder="Contoh: 10000" min="100" value="<?= set_value('kapasitas', $is_edit ? $kandang->kapasitas : '') ?>" required>
            <?= form_error('kapasitas', '<small class="text-danger">', '</small>') ?>
          </div>

          <!-- Tipe Kandang -->
          <div class="col-md-6">
            <label for="tipe_kandang" class="form-label form-label-custom">Tipe Kandang <span class="text-danger">*</span></label>
            <select name="tipe_kandang" id="tipe_kandang" class="form-select form-select-modern" required>
              <option value="Closed House" <?= (set_value('tipe_kandang', $is_edit ? $kandang->tipe_kandang : '') === 'Closed House') ? 'selected' : '' ?>>Closed House (Modern Terutup)</option>
              <option value="Open House" <?= (set_value('tipe_kandang', $is_edit ? $kandang->tipe_kandang : '') === 'Open House') ? 'selected' : '' ?>>Open House (Terbuka / Tradisional)</option>
            </select>
          </div>

          <!-- Lokasi / Blok -->
          <div class="col-md-6">
            <label for="lokasi" class="form-label form-label-custom">Lokasi / Sektor</label>
            <input type="text" name="lokasi" id="lokasi" class="form-control form-control-modern" placeholder="Contoh: Blok D - Sektor Selatan" value="<?= set_value('lokasi', $is_edit ? $kandang->lokasi : '') ?>">
          </div>

          <!-- Status -->
          <div class="col-md-6">
            <label for="status" class="form-label form-label-custom">Status Kandang</label>
            <select name="status" id="status" class="form-select form-select-modern">
              <option value="tersedia" <?= (set_value('status', $is_edit ? $kandang->status : '') === 'tersedia') ? 'selected' : '' ?>>Tersedia (Kosong)</option>
              <option value="terisi" <?= (set_value('status', $is_edit ? $kandang->status : '') === 'terisi') ? 'selected' : '' ?>>Terisi Ayam</option>
              <option value="maintenance" <?= (set_value('status', $is_edit ? $kandang->status : '') === 'maintenance') ? 'selected' : '' ?>>Maintenance / Sterilisasi</option>
            </select>
          </div>

          <div class="col-12 mt-4 pt-3 border-top border-dark d-flex justify-content-end gap-2">
            <a href="<?= base_url('kandang') ?>" class="btn btn-outline-secondary">Batal</a>
            <button type="submit" class="btn-brand-accent">
              <i class="bi bi-save me-1"></i> <?= $is_edit ? 'Simpan Perubahan' : 'Tambah Kandang' ?>
            </button>
          </div>
        </div>
      </form>
    </div>
  </div>
</div>
