<?php
$is_edit = isset($periode);
$action_url = $is_edit ? base_url('periode/edit/' . $periode->id) : base_url('periode/tambah');
$auto_code = 'BATCH-' . date('Y-m-') . sprintf('%03d', rand(1, 999));
?>

<div class="page-header d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
  <div>
    <h1 class="page-title text-white fw-bold"><?= $is_edit ? 'Edit Data Periode Panen' : 'Setup Periode / Batch Baru' ?></h1>
    <p class="page-subtitle text-muted-green mb-0">Atur penerimaan bibit DOC (Day Old Chick), jumlah populasi awal, dan target pemeliharaan.</p>
  </div>
  <a href="<?= base_url('periode') ?>" class="btn-brand-outline">
    <i class="bi bi-arrow-left me-1"></i> Kembali ke Daftar
  </a>
</div>

<div class="row justify-content-center">
  <div class="col-lg-9">
    <div class="card p-4" style="background-color: #0c1a14; border: 1px solid #1e3627; border-radius: 16px;">
      <form action="<?= $action_url ?>" method="POST">
        <div class="row g-3">
          <!-- Kode Periode -->
          <div class="col-md-6">
            <label for="kode_periode" class="form-label form-label-custom">Kode Batch / Periode <span class="text-danger">*</span></label>
            <input type="text" name="kode_periode" id="kode_periode" class="form-control form-control-modern" placeholder="Contoh: BATCH-2026-01-ALPHA" value="<?= set_value('kode_periode', $is_edit ? $periode->kode_periode : $auto_code) ?>" required>
            <?= form_error('kode_periode', '<small class="text-danger">', '</small>') ?>
          </div>

          <!-- Pilih Kandang -->
          <div class="col-md-6">
            <label for="kandang_id" class="form-label form-label-custom">Unit Kandang <span class="text-danger">*</span></label>
            <select name="kandang_id" id="kandang_id" class="form-select form-select-modern" required>
              <option value="">-- Pilih Unit Kandang --</option>
              <?php foreach ($kandang_list as $k): ?>
                <option value="<?= $k->id ?>" <?= (set_value('kandang_id', $is_edit ? $periode->kandang_id : '') == $k->id) ? 'selected' : '' ?>>
                  <?= htmlspecialchars($k->kode_kandang) ?> - <?= htmlspecialchars($k->nama_kandang) ?> (Kapasitas: <?= number_format($k->kapasitas, 0, ',', '.') ?> ekor, <?= $k->tipe_kandang ?>)
                </option>
              <?php endforeach; ?>
            </select>
            <?= form_error('kandang_id', '<small class="text-danger">', '</small>') ?>
          </div>

          <!-- Strain Bibit Ayam -->
          <div class="col-md-6">
            <label for="strain" class="form-label form-label-custom">Jenis Strain Bibit Ayam</label>
            <select name="strain" id="strain" class="form-select form-select-modern">
              <option value="Cobb 500" <?= (set_value('strain', $is_edit ? $periode->strain : '') === 'Cobb 500') ? 'selected' : '' ?>>Cobb 500 (Pertumbuhan Cepat & FCR Rendah)</option>
              <option value="Ross 308" <?= (set_value('strain', $is_edit ? $periode->strain : '') === 'Ross 308') ? 'selected' : '' ?>>Ross 308 (Daya Tahan Tinggi & Daging Dada Tebal)</option>
              <option value="Hubbard Classic" <?= (set_value('strain', $is_edit ? $periode->strain : '') === 'Hubbard Classic') ? 'selected' : '' ?>>Hubbard Classic</option>
              <option value="Lainnya" <?= (set_value('strain', $is_edit ? $periode->strain : '') === 'Lainnya') ? 'selected' : '' ?>>Lainnya / Lokal</option>
            </select>
          </div>

          <!-- Tanggal Masuk DOC -->
          <div class="col-md-6">
            <label for="tgl_masuk_doc" class="form-label form-label-custom">Tanggal Masuk DOC (Hari ke-0) <span class="text-danger">*</span></label>
            <input type="date" name="tgl_masuk_doc" id="tgl_masuk_doc" class="form-control form-control-modern" value="<?= set_value('tgl_masuk_doc', $is_edit ? $periode->tgl_masuk_doc : date('Y-m-d')) ?>" required>
            <?= form_error('tgl_masuk_doc', '<small class="text-danger">', '</small>') ?>
          </div>

          <!-- Jumlah Populasi Awal DOC -->
          <div class="col-md-6">
            <label for="populasi_awal" class="form-label form-label-custom">Jumlah Populasi Awal DOC (Ekor) <span class="text-danger">*</span></label>
            <input type="number" name="populasi_awal" id="populasi_awal" class="form-control form-control-modern" placeholder="Contoh: 10000" min="100" value="<?= set_value('populasi_awal', $is_edit ? $periode->populasi_awal : '10000') ?>" required>
            <?= form_error('populasi_awal', '<small class="text-danger">', '</small>') ?>
          </div>

          <!-- Target Panen (Hari) -->
          <div class="col-md-3">
            <label for="target_panen_hari" class="form-label form-label-custom">Target Umur Panen (Hari)</label>
            <input type="number" name="target_panen_hari" id="target_panen_hari" class="form-control form-control-modern" placeholder="35" min="20" max="60" value="<?= set_value('target_panen_hari', $is_edit ? $periode->target_panen_hari : '35') ?>">
          </div>

          <!-- Target Bobot Panen (Gram) -->
          <div class="col-md-3">
            <label for="target_bobot_panen_gram" class="form-label form-label-custom">Target Bobot (Gram)</label>
            <input type="number" name="target_bobot_panen_gram" id="target_bobot_panen_gram" class="form-control form-control-modern" placeholder="2000" min="500" value="<?= set_value('target_bobot_panen_gram', $is_edit ? $periode->target_bobot_panen_gram : '2000') ?>">
          </div>

          <!-- Harga Kontrak Per Kg -->
          <div class="col-md-6">
            <label for="harga_kontrak_per_kg" class="form-label form-label-custom">Harga Kontrak / Jual (Rp/Kg)</label>
            <div class="input-group">
              <span class="input-group-text bg-dark text-white border-secondary">Rp</span>
              <input type="number" name="harga_kontrak_per_kg" id="harga_kontrak_per_kg" class="form-control form-control-modern" placeholder="21000" value="<?= set_value('harga_kontrak_per_kg', $is_edit ? $periode->harga_kontrak_per_kg : '21000') ?>">
            </div>
          </div>

          <!-- Catatan -->
          <div class="col-md-6">
            <label for="catatan" class="form-label form-label-custom">Catatan / Keterangan Batch</label>
            <input type="text" name="catatan" id="catatan" class="form-control form-control-modern" placeholder="Contoh: DOC Grade A PT BTS, Suhu brooding optimal" value="<?= set_value('catatan', $is_edit ? $periode->catatan : '') ?>">
          </div>

          <div class="col-12 mt-4 pt-3 border-top border-dark d-flex justify-content-end gap-2">
            <a href="<?= base_url('periode') ?>" class="btn btn-outline-secondary">Batal</a>
            <button type="submit" class="btn-brand-accent">
              <i class="bi bi-save me-1"></i> <?= $is_edit ? 'Simpan Perubahan' : 'Buat Periode Baru' ?>
            </button>
          </div>
        </div>
      </form>
    </div>
  </div>
</div>
