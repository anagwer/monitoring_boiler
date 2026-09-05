<div class="page-header d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
  <div>
    <h1 class="page-title mb-1">Data Periode Panen (Batch)</h1>
    <p class="page-subtitle mb-0">Manajemen siklus pemeliharaan ayam broiler dari DOC Masuk (Hari ke-0) hingga Panen.</p>
  </div>
  <a href="<?= base_url('periode/tambah') ?>" class="btn-brand-accent">
    <i class="bi bi-plus-lg me-1"></i> Setup Batch Baru
  </a>
</div>

<!-- Filter Status Tab -->
<div class="d-flex gap-2 mb-4">
  <a href="<?= base_url('periode') ?>" class="btn btn-sm <?= empty($status_filter) ? 'btn-brand-accent' : 'btn-outline-secondary' ?>">
    Semua Batch
  </a>
  <a href="<?= base_url('periode?status=aktif') ?>" class="btn btn-sm <?= ($status_filter === 'aktif') ? 'btn-brand-accent' : 'btn-outline-secondary' ?>">
    <i class="bi bi-play-circle me-1"></i> Sedang Aktif
  </a>
  <a href="<?= base_url('periode?status=selesai') ?>" class="btn btn-sm <?= ($status_filter === 'selesai') ? 'btn-brand-accent' : 'btn-outline-secondary' ?>">
    <i class="bi bi-check2-circle me-1"></i> Selesai Panen
  </a>
</div>

<div class="card p-4">
  <div class="table-responsive">
    <table class="table table-modern datatable mb-0 w-100">
      <thead>
        <tr>
          <th>Kode Batch</th>
          <th>Kandang</th>
          <th>Strain</th>
          <th>Tgl DOC Masuk</th>
          <th>Populasi Awal</th>
          <th>Total Mati</th>
          <th>Pakan (Kg)</th>
          <th>Status</th>
          <th class="text-end">Aksi</th>
        </tr>
      </thead>
      <tbody>
        <?php if (!empty($periode_list)): ?>
          <?php foreach ($periode_list as $p): ?>
            <tr>
              <td class="fw-bold">
                <a href="<?= base_url('periode/detail/' . $p->id) ?>" class="text-decoration-none fw-bold" style="color: var(--app-text-main);">
                  <?= htmlspecialchars($p->kode_periode) ?>
                </a>
              </td>
              <td>
                <div class="fw-semibold" style="color: var(--app-text-main);"><?= htmlspecialchars($p->nama_kandang) ?></div>
                <small class="text-muted"><?= htmlspecialchars($p->kode_kandang) ?></small>
              </td>
              <td><span class="badge bg-dark border border-secondary"><?= htmlspecialchars($p->strain) ?></span></td>
              <td><?= date('d/m/Y', strtotime($p->tgl_masuk_doc)) ?></td>
              <td class="fw-semibold"><?= number_format($p->populasi_awal, 0, ',', '.') ?> <small class="text-muted">ekor</small></td>
              <td class="text-danger fw-bold"><?= number_format($p->total_mati, 0, ',', '.') ?> ekor</td>
              <td class="text-warning fw-bold"><?= number_format($p->total_pakan_kg, 1, ',', '.') ?> kg</td>
              <td>
                <?php if ($p->status === 'aktif'): ?>
                  <span class="badge badge-status-aktif"><i class="bi bi-activity me-1"></i> Aktif</span>
                <?php else: ?>
                  <span class="badge badge-status-selesai"><i class="bi bi-check-all me-1"></i> Selesai Panen</span>
                <?php endif; ?>
              </td>
              <td class="text-end">
                <div class="d-flex justify-content-end gap-1">
                  <a href="<?= base_url('periode/detail/' . $p->id) ?>" class="btn btn-sm btn-outline-info" title="Detail & Overview">
                    <i class="bi bi-eye"></i>
                  </a>
                  <a href="<?= base_url('laporan?periode_id=' . $p->id) ?>" class="btn btn-sm btn-outline-success" title="Lembar Laporan">
                    <i class="bi bi-file-earmark-text"></i>
                  </a>
                  <a href="<?= base_url('periode/edit/' . $p->id) ?>" class="btn btn-sm btn-outline-warning" title="Edit">
                    <i class="bi bi-pencil"></i>
                  </a>
                  <a href="<?= base_url('periode/hapus/' . $p->id) ?>" class="btn btn-sm btn-outline-danger" title="Hapus" onclick="return confirm('Apakah Anda yakin ingin menghapus periode ini beserta seluruh data input hariannya?');">
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
