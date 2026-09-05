<!-- START: ABK Dashboard Header -->
<div class="page-header d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
  <div>
    <h1 class="page-title mb-1">Dashboard Operasional ABK</h1>
    <p class="page-subtitle mb-0">Selamat bertugas, <strong><?= htmlspecialchars($this->session->userdata('name')) ?></strong>. Pantau kondisi kandang, pakan harian, dan mortalitas.</p>
  </div>
  <a href="<?= base_url('harian/input') ?>" class="btn-brand-accent">
    <i class="bi bi-pencil-square me-1"></i> Form Input Harian
  </a>
</div>
<!-- END: ABK Dashboard Header -->

<!-- Banner Notifikasi Status Input Hari Ini -->
<?php if ($semua_sudah_input && !empty($status_kandang_hari_ini)): ?>
  <div class="card p-3 mb-4" style="background: rgba(16, 185, 129, 0.12) !important; border: 1.5px solid rgba(16, 185, 129, 0.3) !important;">
    <div class="d-flex align-items-center gap-3">
      <div class="p-2 bg-success text-white rounded-circle fs-4 d-flex align-items-center justify-content-center" style="width: 44px; height: 44px; flex-shrink: 0;">
        <i class="bi bi-check-lg"></i>
      </div>
      <div>
        <h6 class="text-success fw-bold mb-1">Laporan Hari Ini Telah Lengkap!</h6>
        <p class="text-muted small mb-0">Semua kandang aktif telah diisi data pakan dan kematian untuk hari ini (<strong><?= date('d F Y') ?></strong>).</p>
      </div>
    </div>
  </div>
<?php elseif (!empty($status_kandang_hari_ini)): ?>
  <div class="card p-3 mb-4" style="background: rgba(245, 158, 11, 0.12) !important; border: 1.5px solid rgba(245, 158, 11, 0.4) !important;">
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
      <div class="d-flex align-items-center gap-3">
        <div class="p-2 bg-warning text-dark rounded-circle fs-4 d-flex align-items-center justify-content-center" style="width: 44px; height: 44px; flex-shrink: 0;">
          <i class="bi bi-exclamation-circle-fill"></i>
        </div>
        <div>
          <h6 class="text-warning fw-bold mb-1">Perhatian: Laporan Hari Ini Belum Lengkap!</h6>
          <p class="text-muted small mb-0">Ada kandang aktif yang belum diinput data pakan dan kematian untuk hari ini (<?= date('d/m/Y') ?>).</p>
        </div>
      </div>
      <a href="<?= base_url('harian/input') ?>" class="btn-brand-accent btn-sm text-nowrap">
        <i class="bi bi-plus-circle me-1"></i> Input Laporan Sekarang
      </a>
    </div>
  </div>
<?php endif; ?>

<!-- Active Batches Grid Status -->
<h5 class="fw-bold mb-3 d-flex align-items-center gap-2" style="color: var(--app-text-main);">
  <i class="bi bi-house-door text-warning"></i> Status Kandang & Batch Aktif
</h5>

<div class="row g-4 mb-4">
  <?php if (empty($status_kandang_hari_ini)): ?>
    <div class="col-12">
      <div class="card p-4 text-center">
        <p class="text-muted mb-2">Saat ini tidak ada periode / batch kandang yang sedang aktif.</p>
        <div>
          <a href="<?= base_url('periode/tambah') ?>" class="btn-brand-accent btn-sm">Setup Periode Baru</a>
        </div>
      </div>
    </div>
  <?php else: ?>
    <?php foreach ($status_kandang_hari_ini as $sk): ?>
      <?php $p = $sk['periode']; ?>
      <div class="col-md-6 col-xl-4">
        <div class="card h-100 p-4 d-flex flex-column justify-content-between card-kpi">
          <div>
            <div class="d-flex justify-content-between align-items-start mb-3">
              <div>
                <span class="badge badge-closed-house mb-1"><?= htmlspecialchars($p->nama_kandang) ?></span>
                <h5 class="fw-bold mb-0" style="color: var(--app-text-main);"><?= htmlspecialchars($p->kode_periode) ?></h5>
              </div>
              <?php if ($sk['sudah_input']): ?>
                <span class="badge bg-success bg-opacity-25 text-success border border-success border-opacity-50">
                  <i class="bi bi-check-circle me-1"></i> Terinput
                </span>
              <?php else: ?>
                <span class="badge bg-warning bg-opacity-25 text-warning border border-warning border-opacity-50">
                  <i class="bi bi-clock-history me-1"></i> Belum Input
                </span>
              <?php endif; ?>
            </div>

            <!-- Progress Siklus Panen -->
            <?php 
              $target_hari = $p->target_panen_hari > 0 ? $p->target_panen_hari : 35;
              $progress_pct = min(100, round(($sk['umur_hari_ke'] / $target_hari) * 100));
            ?>
            <div class="mb-3">
              <div class="d-flex justify-content-between small text-muted mb-1">
                <span>Progress Siklus (Hari ke-<?= $sk['umur_hari_ke'] ?> / <?= $target_hari ?>)</span>
                <span class="fw-bold"><?= $progress_pct ?>%</span>
              </div>
              <div class="progress" style="height: 7px; background-color: rgba(0,0,0,0.1);">
                <div class="progress-bar bg-success" role="progressbar" style="width: <?= $progress_pct ?>%;" aria-valuenow="<?= $progress_pct ?>" aria-valuemin="0" aria-valuemax="100"></div>
              </div>
            </div>

            <div class="p-3 box-highlight rounded mb-3">
              <div class="d-flex justify-content-between mb-2">
                <span class="text-muted small">Strain DOC:</span>
                <span class="fw-semibold" style="color: var(--app-text-main);"><?= htmlspecialchars($p->strain) ?></span>
              </div>
              <div class="d-flex justify-content-between mb-2">
                <span class="text-muted small">Populasi Awal:</span>
                <span class="fw-semibold" style="color: var(--app-text-main);"><?= number_format($p->populasi_awal, 0, ',', '.') ?> ekor</span>
              </div>
              <div class="d-flex justify-content-between">
                <span class="text-muted small">Total Pakan Masuk:</span>
                <span class="text-warning fw-bold"><?= number_format($p->total_pakan_kg, 1, ',', '.') ?> kg</span>
              </div>
            </div>
          </div>

          <div class="d-flex gap-2">
            <?php if (!$sk['sudah_input']): ?>
              <a href="<?= base_url('harian/input?periode_id=' . $p->id) ?>" class="btn-brand-accent btn-sm w-100 text-center">
                <i class="bi bi-pencil-square me-1"></i> Input Hari ke-<?= $sk['umur_hari_ke'] ?>
              </a>
            <?php else: ?>
              <a href="<?= base_url('harian?periode_id=' . $p->id) ?>" class="btn-brand-outline btn-sm w-100 text-center">
                <i class="bi bi-eye me-1"></i> Lihat Log Hari Ini
              </a>
            <?php endif; ?>
          </div>
        </div>
      </div>
    <?php endforeach; ?>
  <?php endif; ?>
</div>

<!-- Visual Infographics Row for ABK -->
<?php if (!empty($abk_chart_data) && !empty($abk_chart_data['labels'])): ?>
<div class="row g-4 mb-4">
  <!-- Chart 1: Tren Pakan Harian -->
  <div class="col-lg-8">
    <div class="card p-4 h-100">
      <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
          <h5 class="card-title fw-bold mb-1" style="color: var(--app-text-main);">Grafik Tren Pemberian Pakan Harian</h5>
          <p class="text-muted small mb-0">Pemakaian pakan harian pada batch aktif saat ini</p>
        </div>
        <span class="badge bg-primary bg-opacity-25 text-primary border border-primary border-opacity-50">
          Total: <?= number_format($abk_chart_data['total_pakan_kg'], 1, ',', '.') ?> Kg
        </span>
      </div>
      <div id="chart-abk-pakan" style="min-height: 280px;"></div>
    </div>
  </div>

  <!-- Chart 2: Donut Distribusi Pakan Berdasarkan Jenis Stage -->
  <div class="col-lg-4">
    <div class="card p-4 h-100">
      <div class="mb-3">
        <h5 class="card-title fw-bold mb-1" style="color: var(--app-text-main);">Komposisi Pakan</h5>
        <p class="text-muted small mb-0">Starter vs Grower vs Finisher</p>
      </div>
      <div id="chart-abk-feed-donut" style="min-height: 240px;"></div>
      <div class="row g-2 mt-2 pt-2 border-top border-secondary border-opacity-25 text-center small">
        <div class="col-4">
          <span class="text-muted d-block">Starter</span>
          <strong class="text-success"><?= number_format($feed_stages['Starter'], 1, ',', '.') ?> kg</strong>
        </div>
        <div class="col-4">
          <span class="text-muted d-block">Grower</span>
          <strong class="text-warning"><?= number_format($feed_stages['Grower'], 1, ',', '.') ?> kg</strong>
        </div>
        <div class="col-4">
          <span class="text-muted d-block">Finisher</span>
          <strong class="text-primary"><?= number_format($feed_stages['Finisher'], 1, ',', '.') ?> kg</strong>
        </div>
      </div>
    </div>
  </div>
</div>
<?php endif; ?>

<!-- 5 Riwayat Input Terakhir (DataTable) -->
<div class="card p-4">
  <div class="d-flex justify-content-between align-items-center mb-3">
    <div>
      <h5 class="card-title fw-bold mb-1" style="color: var(--app-text-main);">Riwayat Input Operasional Terakhir</h5>
      <p class="text-muted small mb-0">Daftar input laporan harian yang baru saja dimasukkan ke database</p>
    </div>
    <a href="<?= base_url('harian') ?>" class="btn-brand-outline btn-sm">Lihat Semua</a>
  </div>

  <div class="table-responsive">
    <table class="table table-modern datatable mb-0 w-100">
      <thead>
        <tr>
          <th>Tanggal</th>
          <th>Periode / Kandang</th>
          <th>Hari Ke</th>
          <th>Mati (Ekor)</th>
          <th>Pakan (Kg)</th>
          <th>Jenis Pakan</th>
          <th>Suhu / RH</th>
          <th>Aksi</th>
        </tr>
      </thead>
      <tbody>
        <?php if (!empty($recent_inputs)): ?>
          <?php foreach ($recent_inputs as $r): ?>
            <tr>
              <td><strong><?= date('d/m/Y', strtotime($r->tgl_input)) ?></strong></td>
              <td>
                <div class="fw-bold" style="color: var(--app-text-main);"><?= htmlspecialchars($r->kode_periode) ?></div>
                <small class="text-muted"><?= htmlspecialchars($r->nama_kandang) ?></small>
              </td>
              <td><span class="badge bg-secondary">Hari ke-<?= $r->hari_ke ?></span></td>
              <td class="text-danger fw-bold"><?= $r->jumlah_mati ?> ekor</td>
              <td class="text-warning fw-bold"><?= number_format($r->pakan_kg, 1, ',', '.') ?> kg</td>
              <td><span class="badge bg-dark border border-secondary"><?= $r->jenis_pakan ?></span></td>
              <td><?= $r->suhu_kandang ? $r->suhu_kandang . '°C' : '-' ?> / <?= $r->kelembaban ? $r->kelembaban . '%' : '-' ?></td>
              <td>
                <a href="<?= base_url('harian/edit/' . $r->id) ?>" class="btn btn-sm btn-outline-warning" title="Edit">
                  <i class="bi bi-pencil"></i>
                </a>
              </td>
            </tr>
          <?php endforeach; ?>
        <?php endif; ?>
      </tbody>
    </table>
  </div>
</div>

<?php if (!empty($abk_chart_data) && !empty($abk_chart_data['labels'])): ?>
<script>
  document.addEventListener('DOMContentLoaded', function () {
    const chartData = <?= json_encode($abk_chart_data) ?>;
    const feedStages = <?= json_encode($feed_stages) ?>;

    // Pakan Tren Chart
    const optionsPakan = {
      series: [{
        name: 'Pakan Harian (Kg)',
        data: chartData.pakan_harian
      }],
      chart: {
        type: 'area',
        height: 280,
        toolbar: { show: false },
        background: 'transparent'
      },
      colors: ['#84cc16'],
      fill: {
        type: 'gradient',
        gradient: {
          shadeIntensity: 1,
          opacityFrom: 0.4,
          opacityTo: 0.05,
          stops: [20, 100]
        }
      },
      stroke: { curve: 'smooth', width: 3 },
      dataLabels: { enabled: false },
      xaxis: {
        categories: chartData.labels,
        labels: { style: { colors: '#94a3b8' } }
      },
      yaxis: {
        labels: {
          style: { colors: '#94a3b8' },
          formatter: function (val) { return val + ' kg'; }
        }
      },
      tooltip: {
        y: { formatter: function (val) { return val + ' Kg'; } }
      },
      grid: { borderColor: 'rgba(148, 163, 184, 0.15)' }
    };
    new ApexCharts(document.querySelector("#chart-abk-pakan"), optionsPakan).render();

    // Feed Stage Donut Chart
    const stageValues = [feedStages['Starter'] || 0, feedStages['Grower'] || 0, feedStages['Finisher'] || 0];
    const optionsDonut = {
      series: stageValues,
      chart: {
        type: 'donut',
        height: 240,
        background: 'transparent'
      },
      labels: ['Starter', 'Grower', 'Finisher'],
      colors: ['#10b981', '#f59e0b', '#3b82f6'],
      legend: { position: 'bottom' },
      dataLabels: { enabled: true },
      tooltip: {
        y: { formatter: function (val) { return val + ' Kg'; } }
      }
    };
    new ApexCharts(document.querySelector("#chart-abk-feed-donut"), optionsDonut).render();
  });
</script>
<?php endif; ?>
