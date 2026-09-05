<div class="page-header d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
  <div>
    <h1 class="page-title mb-1">Master Data Kandang</h1>
    <p class="page-subtitle mb-0">Kelola unit kandang peternakan broiler PT BTS, tipe kandang, dan kapasitas terpasang.</p>
  </div>
  <a href="<?= base_url('kandang/tambah') ?>" class="btn-brand-accent">
    <i class="bi bi-plus-lg me-1"></i> Tambah Kandang Baru
  </a>
</div>

<div class="card p-4">
  <div class="table-responsive">
    <table class="table table-modern datatable mb-0 w-100">
      <thead>
        <tr>
          <th>Kode</th>
          <th>Nama Kandang</th>
          <th>Kapasitas</th>
          <th>Tipe Kandang</th>
          <th>Lokasi / Blok</th>
          <th>Status</th>
          <th class="text-end">Aksi</th>
        </tr>
      </thead>
      <tbody>
        <?php if (!empty($kandang_list)): ?>
          <?php foreach ($kandang_list as $k): ?>
            <tr>
              <td class="fw-bold" style="color: var(--app-text-main);"><?= htmlspecialchars($k->kode_kandang) ?></td>
              <td>
                <div class="fw-bold" style="color: var(--app-text-main);"><?= htmlspecialchars($k->nama_kandang) ?></div>
                <?php if (!empty($k->active_periode_kode)): ?>
                  <small class="text-warning"><i class="bi bi-circle-fill" style="font-size: 6px;"></i> Batch Aktif: <?= htmlspecialchars($k->active_periode_kode) ?></small>
                <?php endif; ?>
              </td>
              <td><span class="fw-bold"><?= number_format($k->kapasitas, 0, ',', '.') ?></span> <small class="text-muted">ekor</small></td>
              <td>
                <?php if ($k->tipe_kandang === 'Closed House'): ?>
                  <span class="badge badge-closed-house"><i class="bi bi-building-check me-1"></i> Closed House</span>
                <?php else: ?>
                  <span class="badge badge-open-house"><i class="bi bi-sun me-1"></i> Open House</span>
                <?php endif; ?>
              </td>
              <td><?= htmlspecialchars($k->lokasi) ? htmlspecialchars($k->lokasi) : '-' ?></td>
              <td>
                <?php if ($k->status === 'terisi'): ?>
                  <span class="badge badge-status-terisi"><i class="bi bi-activity me-1"></i> Terisi Ayam</span>
                <?php elseif ($k->status === 'tersedia'): ?>
                  <span class="badge badge-status-tersedia"><i class="bi bi-check-circle me-1"></i> Tersedia (Kosong)</span>
                <?php else: ?>
                  <span class="badge bg-secondary">Maintenance</span>
                <?php endif; ?>
              </td>
              <td class="text-end">
                <div class="d-flex justify-content-end gap-1">
                  <a href="<?= base_url('kandang/edit/' . $k->id) ?>" class="btn btn-sm btn-outline-warning" title="Edit">
                    <i class="bi bi-pencil"></i>
                  </a>
                  <a href="<?= base_url('kandang/hapus/' . $k->id) ?>" class="btn btn-sm btn-outline-danger" title="Hapus" onclick="return confirm('Apakah Anda yakin ingin menghapus kandang ini?');">
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
