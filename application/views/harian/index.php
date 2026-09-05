<div class="page-header d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
  <div>
    <h1 class="page-title mb-1">Riwayat Input Harian</h1>
    <p class="page-subtitle mb-0">Catatan log harian operasional kandang: pakan, mortalitas, obat, vitamin, dan kondisi lingkungan.</p>
  </div>
  <?php if ($this->session->userdata('role') === 'abk'): ?>
    <a href="<?= base_url('harian/input?periode_id=' . $selected_periode_id) ?>" class="btn-brand-accent">
      <i class="bi bi-pencil-square me-1"></i> Input Laporan Harian
    </a>
  <?php endif; ?>
</div>

<!-- Filter Periode Selector Bar -->
<div class="card p-3 mb-4">
  <form action="<?= base_url('harian') ?>" method="GET" class="row g-3 align-items-center">
    <div class="col-md-5">
      <label for="periode_id" class="form-label form-label-custom small mb-1">Pilih Periode / Batch:</label>
      <select name="periode_id" id="periode_id" class="form-select form-select-modern" onchange="this.form.submit()">
        <?php foreach ($active_periods as $p): ?>
          <option value="<?= $p->id ?>" <?= ($selected_periode_id == $p->id) ? 'selected' : '' ?>>
            <?= htmlspecialchars($p->kode_periode) ?> - <?= htmlspecialchars($p->nama_kandang) ?> (<?= ucfirst($p->status) ?>)
          </option>
        <?php endforeach; ?>
      </select>
    </div>
    <?php if ($summary): ?>
      <div class="col-md-7 d-flex flex-wrap gap-3 justify-content-md-end pt-3 pt-md-0">
        <div class="text-end">
          <small class="text-muted d-block">Sisa Ayam Hidup</small>
          <span class="fw-bold fs-6" style="color: var(--app-text-main);"><?= number_format($summary->sisa_populasi, 0, ',', '.') ?> ekor</span>
        </div>
        <div class="text-end">
          <small class="text-muted d-block">Total Pakan Masuk</small>
          <span class="fw-bold text-warning fs-6"><?= number_format($summary->total_pakan_kg, 1, ',', '.') ?> kg</span>
        </div>
        <div class="text-end">
          <small class="text-muted d-block">Mortalitas</small>
          <span class="fw-bold <?= ($summary->mortalitas_persen > 4) ? 'text-danger' : 'text-success' ?> fs-6"><?= $summary->mortalitas_persen ?>%</span>
        </div>
      </div>
    <?php endif; ?>
  </form>
</div>

<!-- Table of Daily Logs -->
<div class="card p-4">
  <div class="table-responsive">
    <table class="table table-modern datatable mb-0 w-100">
      <thead>
        <tr>
          <th>Hari</th>
          <th>Tanggal</th>
          <th>Mati</th>
          <th>Penyebab</th>
          <th>Pakan (Kg)</th>
          <th>Jenis Pakan</th>
          <th>Bobot Sampel</th>
          <th>Obat / Vitamin</th>
          <th>Suhu / RH</th>
          <th>ABK</th>
          <th class="text-end">Aksi</th>
        </tr>
      </thead>
      <tbody>
        <?php if (!empty($harian_list)): ?>
          <?php foreach ($harian_list as $row): ?>
            <tr>
              <td><span class="badge bg-secondary px-2 py-1">Hari ke-<?= $row->hari_ke ?></span></td>
              <td><strong><?= date('d/m/Y', strtotime($row->tgl_input)) ?></strong></td>
              <td class="<?= ($row->jumlah_mati > 10) ? 'text-danger fw-bold' : '' ?>"><?= $row->jumlah_mati ?> ekor</td>
              <td><?= htmlspecialchars($row->penyebab_kematian) ? htmlspecialchars($row->penyebab_kematian) : '-' ?></td>
              <td class="text-warning fw-bold"><?= number_format($row->pakan_kg, 1, ',', '.') ?> kg</td>
              <td><span class="badge bg-dark border border-secondary"><?= $row->jenis_pakan ?></span></td>
              <td><?= $row->bobot_sampel_gram > 0 ? number_format($row->bobot_sampel_gram, 0, ',', '.') . ' gr' : '-' ?></td>
              <td>
                <?php if (!empty($row->obat_vitamin)): ?>
                  <div class="fw-semibold"><?= htmlspecialchars($row->obat_vitamin) ?></div>
                  <small class="text-muted"><?= htmlspecialchars($row->dosis_obat) ?> (<?= htmlspecialchars($row->cara_pemberian) ?>)</small>
                <?php else: ?>
                  <span class="text-muted">-</span>
                <?php endif; ?>
              </td>
              <td>
                <?php if ($row->suhu_kandang || $row->kelembaban): ?>
                  <?= $row->suhu_kandang ? $row->suhu_kandang . '°C' : '-' ?> / <?= $row->kelembaban ? $row->kelembaban . '%' : '-' ?>
                <?php else: ?>
                  <span class="text-muted">-</span>
                <?php endif; ?>
              </td>
              <td><?= htmlspecialchars($row->nama_abk) ? htmlspecialchars($row->nama_abk) : '-' ?></td>
              <td class="text-end">
                <div class="d-flex justify-content-end gap-1">
                  <a href="<?= base_url('harian/edit/' . $row->id) ?>" class="btn btn-sm btn-outline-warning" title="Edit Data">
                    <i class="bi bi-pencil"></i>
                  </a>
                  <a href="<?= base_url('harian/hapus/' . $row->id) ?>" class="btn btn-sm btn-outline-danger" title="Hapus Data" onclick="return confirm('Apakah Anda yakin ingin menghapus data laporan harian ini?');">
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
