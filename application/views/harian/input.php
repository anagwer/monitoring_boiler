<div class="page-header d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
  <div>
    <h1 class="page-title text-white fw-bold">Form Input Laporan Harian</h1>
    <p class="page-subtitle text-muted-green mb-0">Input terintegrasi harian untuk ABK (Hari ke-1 s/d Hari ke-40): pakan, mortalitas, obat, dan suhu.</p>
  </div>
  <a href="<?= base_url('harian') ?>" class="btn-brand-outline">
    <i class="bi bi-clock-history me-1"></i> Lihat Riwayat Input
  </a>
</div>

<div class="row g-4">
  <!-- Main Form Area -->
  <div class="col-lg-8">
    <div class="card p-4" style="background-color: #0c1a14; border: 1px solid #1e3627; border-radius: 16px;">
      <form action="<?= base_url('harian/input') ?>" method="POST" id="formHarian">
        
        <!-- SECTION 1: Identifikasi Periode & Hari Ke -->
        <div class="mb-4 pb-3 border-bottom border-dark">
          <h5 class="text-white fw-bold mb-3 d-flex align-items-center gap-2">
            <i class="bi bi-1-circle-fill text-warning"></i> Identifikasi Periode & Umur Ayam
          </h5>
          <div class="row g-3">
            <div class="col-md-6">
              <label for="periode_id" class="form-label form-label-custom">Pilih Kandang & Batch Aktif <span class="text-danger">*</span></label>
              <select name="periode_id" id="periode_id" class="form-select form-select-modern" required>
                <?php foreach ($active_periods as $ap): ?>
                  <option value="<?= $ap->id ?>" <?= ($default_periode_id == $ap->id) ? 'selected' : '' ?>>
                    <?= htmlspecialchars($ap->nama_kandang) ?> (<?= htmlspecialchars($ap->kode_periode) ?>)
                  </option>
                <?php endforeach; ?>
              </select>
            </div>

            <div class="col-md-6">
              <label for="tgl_input" class="form-label form-label-custom">Tanggal Laporan <span class="text-danger">*</span></label>
              <input type="date" name="tgl_input" id="tgl_input" class="form-control form-control-modern" value="<?= $today ?>" required>
            </div>

            <div class="col-md-12">
              <div class="p-3 rounded d-flex align-items-center justify-content-between" style="background: rgba(180, 241, 5, 0.08); border: 1px solid rgba(180, 241, 5, 0.3);">
                <div class="d-flex align-items-center gap-3">
                  <div class="fs-2 text-warning"><i class="bi bi-stopwatch"></i></div>
                  <div>
                    <span class="text-muted-green small d-block">Umur Ayam Terhitung Otomatis:</span>
                    <h4 class="text-success fw-bold mb-0" id="display_umur">Hari ke-<?= $calculated_age ?></h4>
                  </div>
                </div>
                <input type="hidden" name="hari_ke" id="input_hari_ke" value="<?= $calculated_age ?>">
                <span class="badge" id="badge_rekomendasi" style="background-color: #06130d; color: #B4F105; border: 1px solid #1e3627; font-size: 0.8rem; padding: 6px 12px;">
                  Pakan: Starter
                </span>
              </div>
            </div>
          </div>
        </div>

        <!-- SECTION 2: Kematian (Mortalitas) -->
        <div class="mb-4 pb-3 border-bottom border-dark">
          <h5 class="text-white fw-bold mb-3 d-flex align-items-center gap-2">
            <i class="bi bi-2-circle-fill text-danger"></i> Data Kematian (Mortalitas) Hari Ini
          </h5>
          <div class="row g-3">
            <div class="col-md-6">
              <label for="jumlah_mati" class="form-label form-label-custom">Jumlah Ayam Mati (Ekor) <span class="text-danger">*</span></label>
              <input type="number" name="jumlah_mati" id="jumlah_mati" class="form-control form-control-modern" placeholder="0" min="0" value="0" required>
            </div>

            <div class="col-md-6">
              <label for="penyebab_kematian" class="form-label form-label-custom">Penyebab Kematian Utama</label>
              <select name="penyebab_kematian" id="penyebab_kematian" class="form-select form-select-modern">
                <option value="Normal / Seleksi">Normal / Seleksi Alam</option>
                <option value="Heat Stress / Suhu Panas">Heat Stress / Suhu Panas</option>
                <option value="Tertindih Sekat">Tertindih Sekat / Padat</option>
                <option value="Kerdil / Culling">Kerdil / Tidak Berkembang</option>
                <option value="Penyakit Pernapasan / CRD">Penyakit Pernapasan / Ngorok (CRD)</option>
                <option value="Problem Kaki / Lumpuh">Problem Kaki / Lumpuh</option>
                <option value="Lainnya">Penyebab Lainnya</option>
              </select>
            </div>
          </div>
        </div>

        <!-- SECTION 3: Konsumsi Pakan -->
        <div class="mb-4 pb-3 border-bottom border-dark">
          <h5 class="text-white fw-bold mb-3 d-flex align-items-center gap-2">
            <i class="bi bi-3-circle-fill text-success"></i> Pemberian Pakan Hari Ini
          </h5>
          <div class="row g-3">
            <div class="col-md-4">
              <label for="jenis_pakan" class="form-label form-label-custom">Jenis Pakan <span class="text-danger">*</span></label>
              <select name="jenis_pakan" id="jenis_pakan" class="form-select form-select-modern" required>
                <option value="Starter">Starter (Umur 1 - 10 Hari)</option>
                <option value="Grower">Grower (Umur 11 - 21 Hari)</option>
                <option value="Finisher">Finisher (Umur 22 - Panen)</option>
              </select>
            </div>

            <div class="col-md-4">
              <label for="pakan_kg" class="form-label form-label-custom">Jumlah Pakan (Kg) <span class="text-danger">*</span></label>
              <input type="number" step="0.1" name="pakan_kg" id="pakan_kg" class="form-control form-control-modern" placeholder="0" min="0" required>
            </div>

            <div class="col-md-4">
              <label for="pakan_karung" class="form-label form-label-custom">Setara Karung (1 Karung = 50 Kg)</label>
              <input type="number" step="0.01" name="pakan_karung" id="pakan_karung" class="form-control form-control-modern" placeholder="0" min="0">
            </div>
          </div>
        </div>

        <!-- SECTION 4: Obat, Vitamin, & Vaksinasi (Opsional) -->
        <div class="mb-4 pb-3 border-bottom border-dark">
          <h5 class="text-white fw-bold mb-3 d-flex align-items-center gap-2">
            <i class="bi bi-4-circle-fill text-info"></i> Obat, Vitamin & Vaksin (Jika Ada)
          </h5>
          <div class="row g-3">
            <div class="col-md-4">
              <label for="obat_vitamin" class="form-label form-label-custom">Nama Obat / Vitamin / Vaksin</label>
              <input type="text" name="obat_vitamin" id="obat_vitamin" class="form-control form-control-modern" placeholder="Misal: Vita Stress / Vaksin ND B1">
            </div>

            <div class="col-md-4">
              <label for="dosis_obat" class="form-label form-label-custom">Dosis</label>
              <input type="text" name="dosis_obat" id="dosis_obat" class="form-control form-control-modern" placeholder="Misal: 1 gr / 2 Liter Air">
            </div>

            <div class="col-md-4">
              <label for="cara_pemberian" class="form-label form-label-custom">Cara Pemberian</label>
              <select name="cara_pemberian" id="cara_pemberian" class="form-select form-select-modern">
                <option value="Air Minum">Air Minum</option>
                <option value="Campur Pakan">Campur Pakan</option>
                <option value="Tetes Mata / Hidung">Tetes Mata / Hidung</option>
                <option value="Semprot / Spray">Semprot / Spray</option>
                <option value="Suntik / Injeksi">Suntik / Injeksi</option>
              </select>
            </div>
          </div>
        </div>

        <!-- SECTION 5: Kondisi Lingkungan & Bobot Sampel -->
        <div class="mb-4">
          <h5 class="text-white fw-bold mb-3 d-flex align-items-center gap-2">
            <i class="bi bi-5-circle-fill text-warning"></i> Kondisi Lingkungan & Sampel Bobot
          </h5>
          <div class="row g-3">
            <div class="col-md-4">
              <label for="suhu_kandang" class="form-label form-label-custom">Suhu Kandang (°C)</label>
              <input type="number" step="0.1" name="suhu_kandang" id="suhu_kandang" class="form-control form-control-modern" placeholder="Misal: 29.5">
            </div>

            <div class="col-md-4">
              <label for="kelembaban" class="form-label form-label-custom">Kelembaban (%)</label>
              <input type="number" step="0.1" name="kelembaban" id="kelembaban" class="form-control form-control-modern" placeholder="Misal: 68">
            </div>

            <div class="col-md-4">
              <label for="bobot_sampel_gram" class="form-label form-label-custom">Bobot Sampel (Gram / Ekor)</label>
              <input type="number" step="0.1" name="bobot_sampel_gram" id="bobot_sampel_gram" class="form-control form-control-modern" placeholder="Timbang mingguan">
            </div>

            <div class="col-md-12">
              <label for="catatan" class="form-label form-label-custom">Catatan Tambahan ABK</label>
              <input type="text" name="catatan" id="catatan" class="form-control form-control-modern" placeholder="Contoh: Pelebaran sekat, sirkulasi blower berjalan normal, sekam diganti baru">
            </div>
          </div>
        </div>

        <!-- Submit Buttons -->
        <div class="pt-3 border-top border-dark d-flex justify-content-end gap-2">
          <button type="reset" class="btn btn-outline-secondary">Reset Form</button>
          <button type="submit" class="btn-brand-accent">
            <i class="bi bi-save-fill me-1"></i> Simpan Laporan Harian
          </button>
        </div>
      </form>
    </div>
  </div>

  <!-- Sidebar Live Info Panel -->
  <div class="col-lg-4">
    <div class="card p-4" style="background-color: #0c1a14; border: 1px solid #1e3627; border-radius: 16px;">
      <h5 class="text-white fw-bold mb-3 d-flex align-items-center gap-2">
        <i class="bi bi-info-square-fill text-warning"></i> Informasi Batch Terpilih
      </h5>

      <div class="mb-3 p-3 rounded" style="background-color: rgba(255,255,255,0.02); border: 1px solid #16281e;">
        <div class="text-muted-green small mb-1">Unit Kandang:</div>
        <div class="text-white fw-bold" id="info_kandang"><?= htmlspecialchars($selected_periode ? $selected_periode->nama_kandang : '-') ?></div>
      </div>

      <div class="mb-3 p-3 rounded" style="background-color: rgba(255,255,255,0.02); border: 1px solid #16281e;">
        <div class="text-muted-green small mb-1">Strain DOC & Tanggal Masuk:</div>
        <div class="text-white fw-semibold" id="info_doc"><?= htmlspecialchars($selected_periode ? $selected_periode->strain . ' (' . date('d/m/Y', strtotime($selected_periode->tgl_masuk_doc)) . ')' : '-') ?></div>
      </div>

      <div class="mb-3 p-3 rounded" style="background-color: rgba(255,255,255,0.02); border: 1px solid #16281e;">
        <div class="d-flex justify-content-between mb-1">
          <span class="text-muted-green small">Populasi Awal DOC:</span>
          <span class="text-white fw-semibold" id="info_populasi_awal"><?= number_format($selected_periode ? $selected_periode->populasi_awal : 0, 0, ',', '.') ?> ekor</span>
        </div>
        <div class="d-flex justify-content-between mb-1">
          <span class="text-muted-green small">Total Kematian Sebelumnya:</span>
          <span class="text-danger fw-semibold" id="info_total_mati">-</span>
        </div>
        <div class="d-flex justify-content-between">
          <span class="text-muted-green small">Sisa Populasi Hidup:</span>
          <span class="text-success fw-bold" id="info_sisa_populasi">-</span>
        </div>
      </div>

      <!-- Quick Tips Box -->
      <div class="p-3 rounded" style="background: rgba(59, 130, 246, 0.08); border: 1px dashed rgba(59, 130, 246, 0.3);">
        <h6 class="text-primary fw-bold mb-1"><i class="bi bi-lightbulb me-1"></i> Standar Operasional ABK:</h6>
        <ul class="text-muted-green small mb-0 ps-3">
          <li>Input dilakukan setiap sore setelah pencatatan pakan dan culling selesai.</li>
          <li>Konversi karung otomatis: <strong>1 Karung = 50 Kg</strong>.</li>
          <li>Timbang sampel mingguan diambil acak minimal 50-100 ekor.</li>
        </ul>
      </div>
    </div>
  </div>
</div>

<!-- AJAX Dynamic Day Calculation & Unit Conversion Script -->
<script>
  document.addEventListener('DOMContentLoaded', function () {
    const periodeSelect = document.getElementById('periode_id');
    const tglInput = document.getElementById('tgl_input');
    const pakanKg = document.getElementById('pakan_kg');
    const pakanKarung = document.getElementById('pakan_karung');
    const displayUmur = document.getElementById('display_umur');
    const inputHariKe = document.getElementById('input_hari_ke');
    const badgeRekomendasi = document.getElementById('badge_rekomendasi');
    const jenisPakanSelect = document.getElementById('jenis_pakan');

    function updateAgeAndInfo() {
      const periodeId = periodeSelect.value;
      const tgl = tglInput.value;

      if (!periodeId) return;

      fetch('<?= base_url('harian/ajax_info') ?>?periode_id=' + periodeId + '&tgl_input=' + tgl)
        .then(res => res.json())
        .then(data => {
          if (data.success) {
            displayUmur.textContent = 'Hari ke-' + data.hari_ke;
            inputHariKe.value = data.hari_ke;
            badgeRekomendasi.textContent = 'Rekomendasi: ' + data.rekomendasi_pakan;
            jenisPakanSelect.value = data.rekomendasi_pakan;

            document.getElementById('info_kandang').textContent = data.kandang_nama;
            document.getElementById('info_doc').textContent = data.strain + ' (DOC: ' + data.tgl_masuk_doc + ')';
            document.getElementById('info_populasi_awal').textContent = data.populasi_awal + ' ekor';
            document.getElementById('info_total_mati').textContent = data.total_mati + ' ekor';
            document.getElementById('info_sisa_populasi').textContent = data.sisa_populasi + ' ekor';
          }
        })
        .catch(err => console.error('Error fetching batch info:', err));
    }

    // Auto convert Kg <-> Karung
    pakanKg.addEventListener('input', function () {
      const kg = parseFloat(pakanKg.value) || 0;
      pakanKarung.value = (kg / 50).toFixed(2);
    });

    pakanKarung.addEventListener('input', function () {
      const karung = parseFloat(pakanKarung.value) || 0;
      pakanKg.value = (karung * 50).toFixed(1);
    });

    periodeSelect.addEventListener('change', updateAgeAndInfo);
    tglInput.addEventListener('change', updateAgeAndInfo);

    // Initial load
    updateAgeAndInfo();
  });
</script>
