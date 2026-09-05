<div class="page-header d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
  <div>
    <div class="d-flex align-items-center gap-2 mb-1">
      <h1 class="page-title mb-0">Batch: <?= htmlspecialchars($summary->periode->kode_periode) ?></h1>
      <?php if ($summary->periode->status === 'aktif'): ?>
        <span class="badge badge-status-aktif">Sedang Aktif</span>
      <?php else: ?>
        <span class="badge badge-status-selesai">Selesai Panen</span>
      <?php endif; ?>
    </div>
    <p class="page-subtitle mb-0"><?= htmlspecialchars($summary->periode->nama_kandang) ?> • Strain: <?= htmlspecialchars($summary->periode->strain) ?> • DOC Masuk: <?= date('d F Y', strtotime($summary->periode->tgl_masuk_doc)) ?></p>
  </div>

  <div class="d-flex gap-2">
    <a href="<?= base_url('laporan?periode_id=' . $summary->periode->id) ?>" class="btn-brand-outline">
      <i class="bi bi-file-earmark-bar-graph me-1"></i> Lembar Laporan
    </a>
    <?php if ($summary->periode->status === 'aktif'): ?>
      <button type="button" class="btn btn-warning fw-bold text-dark" data-bs-toggle="modal" data-bs-target="#modalTutupPanen">
        <i class="bi bi-check-circle-fill me-1"></i> Tutup & Selesaikan Panen
      </button>
    <?php endif; ?>
  </div>
</div>

<!-- KPI Summary Cards -->
<div class="row g-3 mb-4">
  <div class="col-md-3">
    <div class="card p-3 h-100 card-kpi">
      <span class="text-muted small text-uppercase fw-semibold">Sisa Populasi Hidup</span>
      <div class="metric-value-highlight my-1"><?= number_format($summary->sisa_populasi, 0, ',', '.') ?> <span class="fs-6 fw-normal text-muted">ekor</span></div>
      <small class="text-muted">Daya Hidup: <strong class="text-success"><?= $summary->livability_persen ?>%</strong></small>
    </div>
  </div>

  <div class="col-md-3">
    <div class="card p-3 h-100 card-kpi">
      <span class="text-muted small text-uppercase fw-semibold">Total Kematian</span>
      <div class="metric-value-highlight text-danger my-1"><?= number_format($summary->total_mati, 0, ',', '.') ?> <span class="fs-6 fw-normal text-muted">ekor</span></div>
      <small class="text-muted">Mortalitas: <strong class="<?= ($summary->mortalitas_persen > 4) ? 'text-danger' : 'text-warning' ?>"><?= $summary->mortalitas_persen ?>%</strong></small>
    </div>
  </div>

  <div class="col-md-3">
    <div class="card p-3 h-100 card-kpi">
      <span class="text-muted small text-uppercase fw-semibold">Konsumsi Pakan Masuk</span>
      <div class="metric-value-highlight text-warning my-1"><?= number_format($summary->total_pakan_kg, 1, ',', '.') ?> <span class="fs-6 fw-normal text-muted">kg</span></div>
      <small class="text-muted">Total <?= number_format($summary->total_pakan_karung, 1, ',', '.') ?> Karung</small>
    </div>
  </div>

  <div class="col-md-3">
    <div class="card p-3 h-100 card-kpi">
      <span class="text-muted small text-uppercase fw-semibold"><?= ($summary->periode->status === 'selesai') ? 'FCR Final & IP' : 'FCR Sementara' ?></span>
      <div class="metric-value-highlight mb-1" style="color: var(--brand-lime);">
        <?= ($summary->periode->status === 'selesai') ? $summary->fcr_final : ($summary->fcr_sementara > 0 ? $summary->fcr_sementara : '-') ?>
      </div>
      <small class="text-muted">
        <?php if ($summary->periode->status === 'selesai'): ?>
          Indeks Prestasi (IP): <strong style="color: var(--app-text-main);"><?= $summary->ip_final ?></strong>
        <?php else: ?>
          Bobot sampel: <strong><?= $summary->bobot_terakhir_gram > 0 ? number_format($summary->bobot_terakhir_gram, 0, ',', '.') . ' gr' : '-' ?></strong>
        <?php endif; ?>
      </small>
    </div>
  </div>
</div>

<!-- Riwayat Input Harian Table -->
<div class="card p-4">
  <div class="d-flex justify-content-between align-items-center mb-3">
    <div>
      <h5 class="card-title fw-bold mb-1" style="color: var(--app-text-main);">Daftar Input Harian Batch Ini</h5>
      <p class="text-muted small mb-0">Total <?= count($daily_logs) ?> hari rekaman data operasional kandang</p>
    </div>
    <?php if ($summary->periode->status === 'aktif'): ?>
      <a href="<?= base_url('harian/input?periode_id=' . $summary->periode->id) ?>" class="btn-brand-accent btn-sm">
        <i class="bi bi-plus-lg me-1"></i> Tambah Input Hari Ke-X
      </a>
    <?php endif; ?>
  </div>

  <div class="table-responsive">
    <table class="table table-modern datatable mb-0 w-100">
      <thead>
        <tr>
          <th>Hari Ke</th>
          <th>Tanggal</th>
          <th>Mati (Ekor)</th>
          <th>Penyebab Kematian</th>
          <th>Pakan (Kg)</th>
          <th>Jenis Pakan</th>
          <th>Bobot Sampel</th>
          <th>Obat / Vitamin</th>
          <th>Suhu/RH</th>
          <th>Petugas ABK</th>
          <th class="text-end">Aksi</th>
        </tr>
      </thead>
      <tbody>
        <?php if (!empty($daily_logs)): ?>
          <?php foreach ($daily_logs as $log): ?>
            <tr>
              <td><span class="badge bg-secondary">Hari ke-<?= $log->hari_ke ?></span></td>
              <td><strong><?= date('d/m/Y', strtotime($log->tgl_input)) ?></strong></td>
              <td class="<?= ($log->jumlah_mati > 10) ? 'text-danger fw-bold' : '' ?>"><?= $log->jumlah_mati ?> ekor</td>
              <td><?= htmlspecialchars($log->penyebab_kematian) ? htmlspecialchars($log->penyebab_kematian) : '-' ?></td>
              <td class="text-warning fw-bold"><?= number_format($log->pakan_kg, 1, ',', '.') ?> kg</td>
              <td><span class="badge bg-dark border border-secondary"><?= $log->jenis_pakan ?></span></td>
              <td><?= $log->bobot_sampel_gram > 0 ? number_format($log->bobot_sampel_gram, 0, ',', '.') . ' gr' : '-' ?></td>
              <td><?= htmlspecialchars($log->obat_vitamin) ? htmlspecialchars($log->obat_vitamin) : '-' ?></td>
              <td><?= $log->suhu_kandang ? $log->suhu_kandang . '°C' : '-' ?> / <?= $log->kelembaban ? $log->kelembaban . '%' : '-' ?></td>
              <td><?= htmlspecialchars($log->nama_abk) ? htmlspecialchars($log->nama_abk) : '-' ?></td>
              <td class="text-end">
                <div class="d-flex justify-content-end gap-1">
                  <a href="<?= base_url('harian/edit/' . $log->id) ?>" class="btn btn-sm btn-outline-warning" title="Edit">
                    <i class="bi bi-pencil"></i>
                  </a>
                  <a href="<?= base_url('harian/hapus/' . $log->id) ?>" class="btn btn-sm btn-outline-danger" title="Hapus" onclick="return confirm('Apakah Anda yakin ingin menghapus data harian ini?');">
                    <i class="bi bi-trash"></i>
                  </a>
                </div>
              </td>
            </tr>
          <?php endforeach; ?>
        <?php endif; ?>
      </tbody>
    </table>
  </div>
</div>

<!-- Modal Tutup & Selesaikan Panen -->
<div class="modal fade" id="modalTutupPanen" tabindex="-1" aria-labelledby="modalTutupPanenLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content" style="background-color: var(--app-card-bg); border: 1px solid var(--app-card-border); color: var(--app-text-main);">
      <div class="modal-header border-bottom border-dark">
        <h5 class="modal-title fw-bold" id="modalTutupPanenLabel" style="color: var(--app-text-main);">
          <i class="bi bi-clipboard-check text-warning me-2"></i> Tutup & Selesaikan Panen
        </h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form action="<?= base_url('periode/tutup_panen/' . $summary->periode->id) ?>" method="POST">
        <div class="modal-body">
          <p class="text-muted small">
            Menutup batch ini akan menandai status periode menjadi <strong>Selesai Panen</strong> dan mengembalikan unit kandang menjadi <strong>Tersedia</strong> untuk DOC baru.
          </p>

          <div class="mb-3">
            <label for="tgl_selesai_panen" class="form-label form-label-custom">Tanggal Selesai Panen <span class="text-danger">*</span></label>
            <input type="date" name="tgl_selesai_panen" id="tgl_selesai_panen" class="form-control form-control-modern" value="<?= date('Y-m-d') ?>" required>
          </div>

          <div class="mb-3">
            <label for="total_bobot_panen_kg" class="form-label form-label-custom">Total Bobot Timbang Panen (Kg) <span class="text-danger">*</span></label>
            <input type="number" step="0.01" name="total_bobot_panen_kg" id="total_bobot_panen_kg" class="form-control form-control-modern" placeholder="Contoh: 19850.5" required>
            <small class="text-muted">Digunakan untuk menghitung FCR Final dan Indeks Prestasi (IP).</small>
          </div>

          <div class="mb-3">
            <label for="harga_kontrak_per_kg" class="form-label form-label-custom">Harga Jual / Kontrak (Rp/Kg)</label>
            <input type="number" step="100" name="harga_kontrak_per_kg" id="harga_kontrak_per_kg" class="form-control form-control-modern" value="<?= $summary->periode->harga_kontrak_per_kg ?>">
          </div>

          <div class="mb-3">
            <label for="catatan" class="form-label form-label-custom">Catatan Evaluasi Panen</label>
            <textarea name="catatan" id="catatan" rows="3" class="form-control form-control-modern" placeholder="Kondisi panen, penerimaan tengkulak/pabrik, dll."></textarea>
          </div>
        </div>
        <div class="modal-footer border-top border-dark">
          <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Batal</button>
          <button type="submit" class="btn btn-warning fw-bold text-dark">
            <i class="bi bi-check2-circle me-1"></i> Simpan & Selesaikan
          </button>
        </div>
      </form>
    </div>
  </div>
</div>
