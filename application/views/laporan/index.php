<div class="page-header d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
  <div>
    <h1 class="page-title mb-1">Laporan Produksi & Kinerja Panen</h1>
    <p class="page-subtitle mb-0">Lembar evaluasi produksi lengkap per periode untuk diserahkan ke manajemen PT BTS.</p>
  </div>

  <div class="d-flex gap-2">
    <?php if (!empty($laporan)): ?>
      <button type="button" class="btn btn-outline-warning fw-semibold" data-bs-toggle="modal" data-bs-target="#modalUpdateBobot">
        <i class="bi bi-pencil-square me-1"></i> Update Bobot Panen (FCR/IP)
      </button>
      <a href="<?= base_url('laporan/cetak/' . $selected_periode_id) ?>" target="_blank" class="btn-brand-accent">
        <i class="bi bi-printer-fill me-1"></i> Cetak Laporan (PDF)
      </a>
    <?php endif; ?>
  </div>
</div>

<!-- Periode Selector Bar -->
<div class="card p-3 mb-4">
  <form action="<?= base_url('laporan') ?>" method="GET" class="row g-3 align-items-center">
    <div class="col-md-5">
      <label for="periode_id" class="form-label form-label-custom small mb-1">Pilih Batch / Periode Panen:</label>
      <select name="periode_id" id="periode_id" class="form-select form-select-modern" onchange="this.form.submit()">
        <?php foreach ($all_periods as $p): ?>
          <option value="<?= $p->id ?>" <?= ($selected_periode_id == $p->id) ? 'selected' : '' ?>>
            <?= htmlspecialchars($p->kode_periode) ?> - <?= htmlspecialchars($p->nama_kandang) ?> (<?= ucfirst($p->status) ?>)
          </option>
        <?php endforeach; ?>
      </select>
    </div>
    <?php if (!empty($laporan)): ?>
      <div class="col-md-7 text-md-end pt-2 pt-md-0">
        <span class="text-muted small">Status Periode:</span>
        <?php if ($laporan['summary']->periode->status === 'aktif'): ?>
          <span class="badge badge-status-aktif ms-1">Sedang Berjalan (Hari ke-<?= $laporan['summary']->hari_terakhir ?>)</span>
        <?php else: ?>
          <span class="badge badge-status-selesai ms-1">Selesai Panen (<?= date('d/m/Y', strtotime($laporan['summary']->periode->tgl_selesai_panen)) ?>)</span>
        <?php endif; ?>
      </div>
    <?php endif; ?>
  </form>
</div>

<?php if (empty($laporan)): ?>
  <div class="card p-5 text-center my-4">
    <p class="text-muted mb-0">Silakan pilih periode panen di atas untuk menampilkan lembar laporan.</p>
  </div>
<?php else: ?>
  <?php $s = $laporan['summary']; ?>

  <!-- Ringkasan Kinerja (KPI Summary Cards) -->
  <div class="row g-3 mb-4">
    <div class="col-6 col-lg-3">
      <div class="card p-3 h-100 card-kpi">
        <span class="text-muted small text-uppercase fw-semibold">Populasi Awal (DOC)</span>
        <div class="fs-4 fw-bold my-1" style="color: var(--app-text-main);"><?= number_format($s->populasi_awal, 0, ',', '.') ?> <small class="fs-6 fw-normal text-muted">ekor</small></div>
        <small class="text-muted">Strain: <strong><?= htmlspecialchars($s->periode->strain) ?></strong></small>
      </div>
    </div>

    <div class="col-6 col-lg-3">
      <div class="card p-3 h-100 card-kpi">
        <span class="text-muted small text-uppercase fw-semibold">Sisa Populasi Siap Panen</span>
        <div class="fs-4 fw-bold text-success my-1"><?= number_format($s->sisa_populasi, 0, ',', '.') ?> <small class="fs-6 fw-normal text-muted">ekor</small></div>
        <small class="text-muted">Daya Hidup (Livability): <strong class="text-success"><?= $s->livability_persen ?>%</strong></small>
      </div>
    </div>

    <div class="col-6 col-lg-3">
      <div class="card p-3 h-100 card-kpi">
        <span class="text-muted small text-uppercase fw-semibold">Total Kematian (Mortalitas)</span>
        <div class="fs-4 fw-bold text-danger my-1"><?= number_format($s->total_mati, 0, ',', '.') ?> <small class="fs-6 fw-normal text-muted">ekor</small></div>
        <small class="text-muted">Mortalitas: <strong class="<?= ($s->mortalitas_persen > 4) ? 'text-danger' : 'text-warning' ?>"><?= $s->mortalitas_persen ?>%</strong></small>
      </div>
    </div>

    <div class="col-6 col-lg-3">
      <div class="card p-3 h-100 card-kpi">
        <span class="text-muted small text-uppercase fw-semibold">Total Pakan Masuk</span>
        <div class="fs-4 fw-bold text-warning my-1"><?= number_format($s->total_pakan_kg, 1, ',', '.') ?> <small class="fs-6 fw-normal text-muted">kg</small></div>
        <small class="text-muted">Setara <strong><?= number_format($s->total_pakan_karung, 1, ',', '.') ?> Karung</strong></small>
      </div>
    </div>
  </div>

  <!-- Key Ratios: FCR, IP, Total Bobot Panen -->
  <div class="card p-4 mb-4" style="background: linear-gradient(135deg, rgba(7, 47, 31, 0.08) 0%, rgba(7, 47, 31, 0.02) 100%);">
    <div class="row g-4 text-center text-md-start">
      <div class="col-md-3 border-end border-secondary border-opacity-25">
        <span class="text-muted small text-uppercase fw-semibold">Total Bobot Panen</span>
        <h3 class="fw-bold mb-0 mt-1" style="color: var(--app-text-main);">
          <?= ((float)$s->periode->total_bobot_panen_kg > 0) ? number_format($s->periode->total_bobot_panen_kg, 1, ',', '.') . ' kg' : 'Belum Ditimbang' ?>
        </h3>
        <small class="text-muted">Rata-rata: <?= ((float)$s->periode->total_bobot_panen_kg > 0 && $s->sisa_populasi > 0) ? number_format(($s->periode->total_bobot_panen_kg / $s->sisa_populasi) * 1000, 0, ',', '.') . ' gr/ekor' : '-' ?></small>
      </div>

      <div class="col-md-3 border-end border-secondary border-opacity-25">
        <span class="text-muted small text-uppercase fw-semibold">FCR Akhir (Feed Conversion)</span>
        <h3 class="fw-bold mb-0 mt-1" style="color: var(--brand-lime);">
          <?= ($s->fcr_final > 0) ? $s->fcr_final : ($s->fcr_sementara > 0 ? $s->fcr_sementara . '*' : '-') ?>
        </h3>
        <small class="text-muted"><?= ($s->fcr_final > 0) ? 'Total Pakan / Total Bobot Panen' : '*Estimasi Berdasarkan Sampel' ?></small>
      </div>

      <div class="col-md-3 border-end border-secondary border-opacity-25">
        <span class="text-muted small text-uppercase fw-semibold">Indeks Prestasi (IP)</span>
        <h3 class="text-info fw-bold mb-0 mt-1">
          <?= ($s->ip_final > 0) ? $s->ip_final : '-' ?>
        </h3>
        <small class="text-muted">
          <?php
          if ($s->ip_final >= 400) echo '<span class="text-success fw-bold">Istimewa (&ge; 400)</span>';
          elseif ($s->ip_final >= 350) echo '<span class="text-success fw-bold">Sangat Baik (350 - 399)</span>';
          elseif ($s->ip_final >= 300) echo '<span class="text-info fw-bold">Baik (300 - 349)</span>';
          elseif ($s->ip_final > 0) echo '<span class="text-warning fw-bold">Cukup / Kurang (< 300)</span>';
          else echo 'Dihitung saat panen';
          ?>
        </small>
      </div>

      <div class="col-md-3">
        <span class="text-muted small text-uppercase fw-semibold">Estimasi Nilai Kontrak</span>
        <h3 class="text-warning fw-bold mb-0 mt-1">
          <?php
          $total_kg = (float)$s->periode->total_bobot_panen_kg;
          $harga_kg = (float)$s->periode->harga_kontrak_per_kg;
          if ($total_kg > 0) {
            echo 'Rp ' . number_format($total_kg * $harga_kg, 0, ',', '.');
          } else {
            echo 'Rp ' . number_format(($s->sisa_populasi * 2.0) * $harga_kg, 0, ',', '.') . '*';
          }
          ?>
        </h3>
        <small class="text-muted">Harga: Rp <?= number_format($harga_kg, 0, ',', '.') ?> / Kg</small>
      </div>
    </div>
  </div>

  <!-- Detail Tabel Lembar Serah Terima Produksi -->
  <div class="card p-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
      <h5 class="card-title fw-bold mb-0" style="color: var(--app-text-main);">Tabel Rekapitulasi Produksi Harian (Hari ke-1 s/d Hari ke-40)</h5>
      <span class="text-muted small">Unit: <strong><?= htmlspecialchars($s->periode->nama_kandang) ?></strong></span>
    </div>

    <div class="table-responsive">
      <table class="table table-sm table-modern datatable mb-0 w-100">
        <thead>
          <tr class="text-center">
            <th>Umur</th>
            <th>Tanggal</th>
            <th>Populasi Awal</th>
            <th>Mati Hari Ini</th>
            <th>Akumulasi Mati</th>
            <th>Sisa Hidup</th>
            <th>Pakan Hari Ini (Kg)</th>
            <th>Akumulasi Pakan (Kg)</th>
            <th>Bobot Sampel</th>
            <th>Obat / Vitamin</th>
            <th>Keterangan / Gejala</th>
          </tr>
        </thead>
        <tbody>
          <?php if (!empty($laporan['rows'])): ?>
            <?php foreach ($laporan['rows'] as $r): ?>
              <tr>
                <td class="text-center fw-bold">Hari <?= $r->hari_ke ?></td>
                <td class="text-center"><strong><?= date('d/m/Y', strtotime($r->tgl_input)) ?></strong></td>
                <td class="text-end"><?= number_format($r->populasi_awal_hari, 0, ',', '.') ?></td>
                <td class="text-center <?= ($r->jumlah_mati > 10) ? 'text-danger fw-bold' : '' ?>"><?= $r->jumlah_mati ?></td>
                <td class="text-center text-danger fw-bold"><?= number_format($r->akumulasi_mati, 0, ',', '.') ?></td>
                <td class="text-end fw-bold text-success"><?= number_format($r->sisa_hidup, 0, ',', '.') ?></td>
                <td class="text-end text-warning fw-semibold"><?= number_format($r->pakan_kg, 1, ',', '.') ?></td>
                <td class="text-end text-warning fw-bold"><?= number_format($r->akumulasi_pakan_kg, 1, ',', '.') ?></td>
                <td class="text-center"><?= $r->bobot_sampel_gram > 0 ? number_format($r->bobot_sampel_gram, 0, ',', '.') . ' gr' : '-' ?></td>
                <td><?= htmlspecialchars($r->obat_vitamin) ? htmlspecialchars($r->obat_vitamin) : '-' ?></td>
                <td><small class="text-muted"><?= htmlspecialchars($r->penyebab_kematian . ($r->catatan ? ' (' . $r->catatan . ')' : '')) ?></small></td>
              </tr>
            <?php endforeach; ?>
          <?php endif; ?>
        </tbody>
      </table>
    </div>
  </div>

  <!-- Modal Update Bobot Panen & Evaluasi -->
  <div class="modal fade" id="modalUpdateBobot" tabindex="-1" aria-labelledby="modalUpdateBobotLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content" style="background-color: var(--app-card-bg); border: 1px solid var(--app-card-border); color: var(--app-text-main);">
        <div class="modal-header border-bottom border-dark">
          <h5 class="modal-title fw-bold" id="modalUpdateBobotLabel" style="color: var(--app-text-main);">
            <i class="bi bi-calculator text-warning me-2"></i> Input Total Bobot Panen (FCR / IP)
          </h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <form action="<?= base_url('laporan/update_bobot_panen') ?>" method="POST">
          <input type="hidden" name="periode_id" value="<?= $s->periode->id ?>">
          <div class="modal-body">
            <div class="mb-3">
              <label for="total_bobot_panen_kg" class="form-label form-label-custom">Total Bobot Seluruh Ayam Saat Panen (Kg) <span class="text-danger">*</span></label>
              <input type="number" step="0.01" name="total_bobot_panen_kg" id="total_bobot_panen_kg" class="form-control form-control-modern" value="<?= $s->periode->total_bobot_panen_kg ?>" required>
              <small class="text-muted">Hasil timbang total seluruh ayam yang dijual ke tengkulak/pabrik.</small>
            </div>

            <div class="mb-3">
              <label for="tgl_selesai_panen" class="form-label form-label-custom">Tanggal Panen</label>
              <input type="date" name="tgl_selesai_panen" id="tgl_selesai_panen" class="form-control form-control-modern" value="<?= $s->periode->tgl_selesai_panen ? $s->periode->tgl_selesai_panen : date('Y-m-d') ?>">
            </div>

            <div class="mb-3">
              <label for="harga_kontrak_per_kg" class="form-label form-label-custom">Harga Jual / Kontrak (Rp/Kg)</label>
              <input type="number" step="100" name="harga_kontrak_per_kg" id="harga_kontrak_per_kg" class="form-control form-control-modern" value="<?= $s->periode->harga_kontrak_per_kg ?>">
            </div>

            <div class="mb-3">
              <label for="status" class="form-label form-label-custom">Status Periode</label>
              <select name="status" id="status" class="form-select form-select-modern">
                <option value="selesai" <?= ($s->periode->status === 'selesai') ? 'selected' : '' ?>>Selesai Panen</option>
                <option value="aktif" <?= ($s->periode->status === 'aktif') ? 'selected' : '' ?>>Masih Aktif</option>
              </select>
            </div>

            <div class="mb-3">
              <label for="catatan" class="form-label form-label-custom">Catatan Tambahan</label>
              <textarea name="catatan" id="catatan" rows="2" class="form-control form-control-modern"><?= htmlspecialchars($s->periode->catatan) ?></textarea>
            </div>
          </div>
          <div class="modal-footer border-top border-dark">
            <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Batal</button>
            <button type="submit" class="btn-brand-accent">
              <i class="bi bi-save me-1"></i> Simpan & Hitung FCR
            </button>
          </div>
        </form>
      </div>
    </div>
  </div>

<?php endif; ?>
