<div class="page-header d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
  <div>
    <h1 class="page-title text-white fw-bold">Edit Laporan Harian: Hari ke-<?= $log->hari_ke ?></h1>
    <p class="page-subtitle text-muted-green mb-0">Batch: <strong><?= htmlspecialchars($log->kode_periode) ?></strong> • Kandang: <?= htmlspecialchars($log->nama_kandang) ?> • Tanggal: <?= date('d/m/Y', strtotime($log->tgl_input)) ?></p>
  </div>
  <a href="<?= base_url('harian?periode_id=' . $log->periode_id) ?>" class="btn-brand-outline">
    <i class="bi bi-arrow-left me-1"></i> Kembali ke Riwayat
  </a>
</div>

<div class="row justify-content-center">
  <div class="col-lg-9">
    <div class="card p-4" style="background-color: #0c1a14; border: 1px solid #1e3627; border-radius: 16px;">
      <form action="<?= base_url('harian/edit/' . $log->id) ?>" method="POST">
        <div class="row g-3">
          
          <!-- Kematian -->
          <div class="col-md-6">
            <label for="jumlah_mati" class="form-label form-label-custom">Jumlah Ayam Mati (Ekor) <span class="text-danger">*</span></label>
            <input type="number" name="jumlah_mati" id="jumlah_mati" class="form-control form-control-modern" value="<?= set_value('jumlah_mati', $log->jumlah_mati) ?>" min="0" required>
          </div>

          <div class="col-md-6">
            <label for="penyebab_kematian" class="form-label form-label-custom">Penyebab Kematian</label>
            <input type="text" name="penyebab_kematian" id="penyebab_kematian" class="form-control form-control-modern" value="<?= set_value('penyebab_kematian', $log->penyebab_kematian) ?>">
          </div>

          <!-- Pakan -->
          <div class="col-md-4">
            <label for="jenis_pakan" class="form-label form-label-custom">Jenis Pakan <span class="text-danger">*</span></label>
            <select name="jenis_pakan" id="jenis_pakan" class="form-select form-select-modern" required>
              <option value="Starter" <?= ($log->jenis_pakan === 'Starter') ? 'selected' : '' ?>>Starter</option>
              <option value="Grower" <?= ($log->jenis_pakan === 'Grower') ? 'selected' : '' ?>>Grower</option>
              <option value="Finisher" <?= ($log->jenis_pakan === 'Finisher') ? 'selected' : '' ?>>Finisher</option>
            </select>
          </div>

          <div class="col-md-4">
            <label for="pakan_kg" class="form-label form-label-custom">Jumlah Pakan (Kg) <span class="text-danger">*</span></label>
            <input type="number" step="0.1" name="pakan_kg" id="pakan_kg" class="form-control form-control-modern" value="<?= set_value('pakan_kg', $log->pakan_kg) ?>" required>
          </div>

          <div class="col-md-4">
            <label for="pakan_karung" class="form-label form-label-custom">Setara Karung</label>
            <input type="number" step="0.01" name="pakan_karung" id="pakan_karung" class="form-control form-control-modern" value="<?= set_value('pakan_karung', $log->pakan_karung) ?>">
          </div>

          <!-- Obat / Vitamin -->
          <div class="col-md-4">
            <label for="obat_vitamin" class="form-label form-label-custom">Obat / Vitamin</label>
            <input type="text" name="obat_vitamin" id="obat_vitamin" class="form-control form-control-modern" value="<?= set_value('obat_vitamin', $log->obat_vitamin) ?>">
          </div>

          <div class="col-md-4">
            <label for="dosis_obat" class="form-label form-label-custom">Dosis</label>
            <input type="text" name="dosis_obat" id="dosis_obat" class="form-control form-control-modern" value="<?= set_value('dosis_obat', $log->dosis_obat) ?>">
          </div>

          <div class="col-md-4">
            <label for="cara_pemberian" class="form-label form-label-custom">Cara Pemberian</label>
            <input type="text" name="cara_pemberian" id="cara_pemberian" class="form-control form-control-modern" value="<?= set_value('cara_pemberian', $log->cara_pemberian) ?>">
          </div>

          <!-- Suhu & Bobot -->
          <div class="col-md-4">
            <label for="suhu_kandang" class="form-label form-label-custom">Suhu (°C)</label>
            <input type="number" step="0.1" name="suhu_kandang" id="suhu_kandang" class="form-control form-control-modern" value="<?= set_value('suhu_kandang', $log->suhu_kandang) ?>">
          </div>

          <div class="col-md-4">
            <label for="kelembaban" class="form-label form-label-custom">Kelembaban (%)</label>
            <input type="number" step="0.1" name="kelembaban" id="kelembaban" class="form-control form-control-modern" value="<?= set_value('kelembaban', $log->kelembaban) ?>">
          </div>

          <div class="col-md-4">
            <label for="bobot_sampel_gram" class="form-label form-label-custom">Bobot Sampel (Gram)</label>
            <input type="number" step="0.1" name="bobot_sampel_gram" id="bobot_sampel_gram" class="form-control form-control-modern" value="<?= set_value('bobot_sampel_gram', $log->bobot_sampel_gram) ?>">
          </div>

          <div class="col-12">
            <label for="catatan" class="form-label form-label-custom">Catatan Tambahan</label>
            <input type="text" name="catatan" id="catatan" class="form-control form-control-modern" value="<?= set_value('catatan', $log->catatan) ?>">
          </div>

          <div class="col-12 mt-4 pt-3 border-top border-dark d-flex justify-content-end gap-2">
            <a href="<?= base_url('harian?periode_id=' . $log->periode_id) ?>" class="btn btn-outline-secondary">Batal</a>
            <button type="submit" class="btn-brand-accent">
              <i class="bi bi-save me-1"></i> Simpan Perubahan
            </button>
          </div>
        </div>
      </form>
    </div>
  </div>
</div>

<script>
  document.addEventListener('DOMContentLoaded', function () {
    const pakanKg = document.getElementById('pakan_kg');
    const pakanKarung = document.getElementById('pakan_karung');

    pakanKg.addEventListener('input', function () {
      const kg = parseFloat(pakanKg.value) || 0;
      pakanKarung.value = (kg / 50).toFixed(2);
    });

    pakanKarung.addEventListener('input', function () {
      const karung = parseFloat(pakanKarung.value) || 0;
      pakanKg.value = (karung * 50).toFixed(1);
    });
  });
</script>
