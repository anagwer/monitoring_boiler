<!-- START: Dashboard Header Banner -->
<div class="page-header d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
  <div>
    <h1 class="page-title mb-1">Dashboard Monitoring Eksekutif</h1>
    <p class="page-subtitle mb-0">Analisis visual peternakan ayam broiler, evaluasi FCR, mortalitas, analisis penyebab kematian, dan suhu kandang PT BTS.</p>
  </div>

  <!-- Periode Selector -->
  <div class="d-flex align-items-center gap-2">
    <form action="<?= base_url('dashboard') ?>" method="GET" class="d-flex align-items-center gap-2">
      <label for="filter_periode" class="text-nowrap small fw-bold" style="color: var(--app-text-main);">Pilih Batch:</label>
      <select name="periode_id" id="filter_periode" class="form-select form-select-modern" onchange="this.form.submit()">
        <?php foreach ($active_periods as $ap): ?>
          <option value="<?= $ap->id ?>" <?= ($selected_periode_id == $ap->id) ? 'selected' : '' ?>>
            <?= htmlspecialchars($ap->kode_periode) ?> - <?= htmlspecialchars($ap->nama_kandang) ?> (<?= ucfirst($ap->status) ?>)
          </option>
        <?php endforeach; ?>
      </select>
    </form>
  </div>
</div>
<!-- END: Dashboard Header Banner -->

<?php if (empty($summary)): ?>
  <div class="card p-5 text-center my-4">
    <i class="bi bi-info-circle text-warning fs-1 mb-3"></i>
    <h4 class="fw-bold" style="color: var(--app-text-main);">Belum Ada Data Periode Aktif</h4>
    <p class="text-muted">Silakan buat periode panen (batch) baru terlebih dahulu untuk melihat grafik analitik dan monitoring.</p>
    <div class="mt-2">
      <a href="<?= base_url('periode/tambah') ?>" class="btn-brand-accent">
        <i class="bi bi-plus-lg me-1"></i> Setup Periode Baru
      </a>
    </div>
  </div>
<?php else: ?>

  <!-- Alert Banner: Peringatan Kematian Melebihi Batas Wajar (>1%) -->
  <?php if (!empty($alerts)): ?>
    <div class="card p-3 mb-4" style="background: rgba(239, 68, 68, 0.1) !important; border: 1.5px solid rgba(239, 68, 68, 0.4) !important;">
      <div class="d-flex flex-column flex-md-row align-items-start align-items-md-center justify-content-between gap-3">
        <div class="d-flex align-items-center gap-3">
          <div class="p-2 bg-danger text-white rounded-circle fs-4 d-flex align-items-center justify-content-center" style="width: 44px; height: 44px; flex-shrink: 0;">
            <i class="bi bi-exclamation-triangle-fill"></i>
          </div>
          <div>
            <h6 class="text-danger fw-bold mb-1">Peringatan Lonjakan Mortalitas Terdeteksi!</h6>
            <p class="mb-0 text-muted small">
              Terdapat <?= count($alerts) ?> hari dengan tingkat kematian harian &gt; 1% dari populasi awal pada <strong><?= htmlspecialchars($summary->periode->kode_periode) ?></strong>.
              Catatan terakhir: <em>"<?= htmlspecialchars($alerts[count($alerts)-1]['penyebab']) ?>"</em> (<?= $alerts[count($alerts)-1]['jumlah_mati'] ?> ekor pada Hari ke-<?= $alerts[count($alerts)-1]['hari_ke'] ?>).
            </p>
          </div>
        </div>
        <a href="<?= base_url('harian?periode_id=' . $selected_periode_id) ?>" class="btn btn-sm btn-outline-danger text-nowrap">
          <i class="bi bi-search me-1"></i> Periksa Log Harian
        </a>
      </div>
    </div>
  <?php endif; ?>

  <!-- KPI Metric Cards Row -->
  <div class="row g-3 mb-4">
    <!-- Card 1: Populasi Hidup -->
    <div class="col-xl-3 col-md-6">
      <div class="card card-kpi h-100 p-3">
        <div class="d-flex justify-content-between align-items-start mb-2">
          <span class="text-muted small fw-bold text-uppercase">Sisa Populasi Hidup</span>
          <span class="badge bg-success bg-opacity-25 text-success border border-success border-opacity-50">Livability <?= $summary->livability_persen ?>%</span>
        </div>
        <div class="metric-value-highlight mb-1">
          <?= number_format($summary->sisa_populasi, 0, ',', '.') ?> <span class="fs-6 fw-normal text-muted">ekor</span>
        </div>
        <div class="metric-subtext">
          Dari total awal <?= number_format($summary->populasi_awal, 0, ',', '.') ?> DOC (Mati: <?= number_format($summary->total_mati, 0, ',', '.') ?>)
        </div>
      </div>
    </div>

    <!-- Card 2: Tingkat Mortalitas -->
    <div class="col-xl-3 col-md-6">
      <div class="card card-kpi h-100 p-3">
        <div class="d-flex justify-content-between align-items-start mb-2">
          <span class="text-muted small fw-bold text-uppercase">Tingkat Mortalitas</span>
          <span class="badge <?= ($summary->mortalitas_persen > 4.0) ? 'bg-danger text-white' : 'bg-warning text-dark' ?>">
            Target &le; 4.0%
          </span>
        </div>
        <div class="metric-value-highlight <?= ($summary->mortalitas_persen > 4.0) ? 'text-danger' : 'text-warning' ?> mb-1">
          <?= $summary->mortalitas_persen ?>%
        </div>
        <div class="metric-subtext">
          Total <?= number_format($summary->total_mati, 0, ',', '.') ?> ekor ayam mati kumulatif
        </div>
      </div>
    </div>

    <!-- Card 3: Total Pakan -->
    <div class="col-xl-3 col-md-6">
      <div class="card card-kpi h-100 p-3">
        <div class="d-flex justify-content-between align-items-start mb-2">
          <span class="text-muted small fw-bold text-uppercase">Konsumsi Pakan Masuk</span>
          <span class="badge bg-primary bg-opacity-25 text-primary border border-primary border-opacity-50"><?= number_format($summary->total_pakan_karung, 1, ',', '.') ?> Karung</span>
        </div>
        <div class="metric-value-highlight mb-1">
          <?= number_format($summary->total_pakan_kg, 1, ',', '.') ?> <span class="fs-6 fw-normal text-muted">kg</span>
        </div>
        <div class="metric-subtext">
          Hari berjalan ke-<?= $summary->hari_terakhir ?> dari target <?= $summary->periode->target_panen_hari ?> hari
        </div>
      </div>
    </div>

    <!-- Card 4: FCR Sementara & Nilai IP -->
    <div class="col-xl-3 col-md-6">
      <div class="card card-kpi h-100 p-3">
        <div class="d-flex justify-content-between align-items-start mb-2">
          <span class="text-muted small fw-bold text-uppercase"><?= ($summary->periode->status === 'selesai') ? 'FCR Final & IP' : 'FCR Sementara' ?></span>
          <span class="badge" style="background: rgba(132, 204, 22, 0.2); color: var(--brand-lime-dark); border: 1px solid var(--brand-lime);">
            Standar &le; 1.55
          </span>
        </div>
        <div class="metric-value-highlight mb-1" style="color: var(--brand-lime-dark);">
          <?= ($summary->periode->status === 'selesai') ? $summary->fcr_final : ($summary->fcr_sementara > 0 ? $summary->fcr_sementara : '1.45*') ?>
        </div>
        <div class="metric-subtext">
          <?php if ($summary->periode->status === 'selesai'): ?>
            Indeks Prestasi (IP): <strong><?= $summary->ip_final ?></strong> (Sangat Baik)
          <?php else: ?>
            Bobot sampel terakhir: <strong><?= $summary->bobot_terakhir_gram > 0 ? number_format($summary->bobot_terakhir_gram, 0, ',', '.') . ' gr' : 'Belum timbang' ?></strong>
          <?php endif; ?>
        </div>
      </div>
    </div>
  </div>

  <!-- Infographics & Charts Row 1: Kurva Pertumbuhan & Penyebab Kematian -->
  <div class="row g-4 mb-4">
    <!-- Chart 1: Kurva Pertumbuhan Bobot vs Standar -->
    <div class="col-xl-8">
      <div class="card p-4 h-100">
        <div class="d-flex flex-column flex-sm-row justify-content-between align-items-sm-center gap-2 mb-3">
          <div>
            <h5 class="card-title fw-bold mb-1" style="color: var(--app-text-main);">1. Kurva Pertumbuhan Bobot Ayam Broiler (Gram)</h5>
            <p class="text-muted small mb-0">Perbandingan bobot riil sampel timbang vs standar performa strain <?= htmlspecialchars($summary->periode->strain) ?></p>
          </div>
          <span class="badge bg-success bg-opacity-25 text-success border border-success border-opacity-50 px-3 py-2">
            <i class="bi bi-activity me-1"></i> Standar Strain Cobb 500
          </span>
        </div>
        <div id="chart-pertumbuhan" style="min-height: 320px;"></div>
      </div>
    </div>

    <!-- Chart 2: Analisis Penyebab Kematian Terbanyak (Donut Chart) -->
    <div class="col-xl-4">
      <div class="card p-4 h-100">
        <div class="mb-3">
          <h5 class="card-title fw-bold mb-1" style="color: var(--app-text-main);">2. Analisis Penyebab Kematian</h5>
          <p class="text-muted small mb-0">Distribusi faktor mortalitas ayam selama pemeliharaan</p>
        </div>
        <div id="chart-penyebab-kematian" style="min-height: 280px;"></div>
        <div class="mt-2 text-center text-muted small">
          Total Ayam Mati: <strong class="text-danger"><?= number_format($summary->total_mati, 0, ',', '.') ?> Ekor</strong>
        </div>
      </div>
    </div>
  </div>

  <!-- Infographics & Charts Row 2: Mortalitas & Lingkungan Kandang (Suhu & RH) -->
  <div class="row g-4 mb-4">
    <!-- Chart 3: Mortalitas Kumulatif & Harian -->
    <div class="col-xl-6">
      <div class="card p-4 h-100">
        <div class="mb-3">
          <h5 class="card-title fw-bold mb-1" style="color: var(--app-text-main);">3. Tren Mortalitas (Harian vs Akumulasi)</h5>
          <p class="text-muted small mb-0">Pemantauan lonjakan kematian harian dan akumulasi keseluruhan</p>
        </div>
        <div id="chart-mortalitas" style="min-height: 300px;"></div>
      </div>
    </div>

    <!-- Chart 4: Fluktuasi Suhu (°C) & Kelembaban (%) Harian -->
    <div class="col-xl-6">
      <div class="card p-4 h-100">
        <div class="d-flex justify-content-between align-items-center mb-3">
          <div>
            <h5 class="card-title fw-bold mb-1" style="color: var(--app-text-main);">4. Fluktuasi Suhu (°C) & Kelembaban (%) Kandang</h5>
            <p class="text-muted small mb-0">Monitoring kondisi mikroklimat lingkungan kandang harian</p>
          </div>
          <span class="badge bg-warning bg-opacity-25 text-warning border border-warning border-opacity-50">
            Kondisi Lingkungan
          </span>
        </div>
        <div id="chart-suhu-rh" style="min-height: 300px;"></div>
      </div>
    </div>
  </div>

  <!-- Infographics & Charts Row 3: Efisiensi Pakan & Proyeksi Finansial -->
  <div class="row g-4 mb-4">
    <!-- Chart 5: Intake Pakan per Ekor per Hari (Gram/Ekor/Hari) -->
    <div class="col-xl-6">
      <div class="card p-4 h-100">
        <div class="d-flex justify-content-between align-items-center mb-3">
          <div>
            <h5 class="card-title fw-bold mb-1" style="color: var(--app-text-main);">5. Konsumsi Pakan Harian per Ekor (Gram / Ekor / Hari)</h5>
            <p class="text-muted small mb-0">Evaluasi nafsu makan dan asupan nutrisi harian per ayam</p>
          </div>
          <span class="badge bg-primary bg-opacity-25 text-primary border border-primary border-opacity-50">
            Feed Intake Rate
          </span>
        </div>
        <div id="chart-feed-intake" style="min-height: 280px;"></div>
      </div>
    </div>

    <!-- Infographic Financial & Batch Summary -->
    <div class="col-xl-6">
      <div class="card p-4 h-100 d-flex flex-column justify-content-between">
        <div>
          <div class="d-flex justify-content-between align-items-center mb-3">
            <h5 class="card-title fw-bold mb-0" style="color: var(--app-text-main);">6. Estimasi Proyeksi Finansial & Status Batch</h5>
            <a href="<?= base_url('laporan?periode_id=' . $selected_periode_id) ?>" class="btn-brand-outline btn-sm">
              <i class="bi bi-file-earmark-text me-1"></i> Buka Laporan Lengkap
            </a>
          </div>

          <div class="row g-3 mb-3">
            <div class="col-6">
              <div class="p-3 box-highlight rounded">
                <span class="text-muted small d-block">Estimasi Nilai Panen</span>
                <?php
                  $bobot_panen = ((float)$summary->periode->total_bobot_panen_kg > 0) ? (float)$summary->periode->total_bobot_panen_kg : ($summary->sisa_populasi * 2.0);
                  $nilai_panen = $bobot_panen * (float)$summary->periode->harga_kontrak_per_kg;
                ?>
                <h4 class="fw-bold text-success mb-0 mt-1">Rp <?= number_format($nilai_panen, 0, ',', '.') ?></h4>
                <small class="text-muted">@ Rp <?= number_format($summary->periode->harga_kontrak_per_kg, 0, ',', '.') ?> / Kg</small>
              </div>
            </div>

            <div class="col-6">
              <div class="p-3 box-highlight rounded">
                <span class="text-muted small d-block">Estimasi Biaya Pakan</span>
                <?php
                  $biaya_pakan = $summary->total_pakan_kg * 8800; // Standar pakan Rp 8.800/kg
                ?>
                <h4 class="fw-bold text-warning mb-0 mt-1">Rp <?= number_format($biaya_pakan, 0, ',', '.') ?></h4>
                <small class="text-muted">Asumsi Rp 8.800 / Kg</small>
              </div>
            </div>
          </div>

          <div class="table-responsive">
            <table class="table table-sm table-modern mb-0">
              <tbody>
                <tr>
                  <td class="text-muted">Kode Periode</td>
                  <td class="text-end fw-bold" style="color: var(--app-text-main);"><?= htmlspecialchars($summary->periode->kode_periode) ?></td>
                </tr>
                <tr>
                  <td class="text-muted">Kandang & Tipe</td>
                  <td class="text-end fw-semibold" style="color: var(--app-text-main);"><?= htmlspecialchars($summary->periode->nama_kandang) ?> <span class="badge badge-closed-house ms-1"><?= htmlspecialchars($summary->periode->tipe_kandang) ?></span></td>
                </tr>
                <tr>
                  <td class="text-muted">Tanggal Masuk DOC</td>
                  <td class="text-end" style="color: var(--app-text-main);"><?= date('d F Y', strtotime($summary->periode->tgl_masuk_doc)) ?></td>
                </tr>
                <tr>
                  <td class="text-muted">Target Panen</td>
                  <td class="text-end" style="color: var(--app-text-main);"><?= $summary->periode->target_panen_hari ?> Hari (Target Bobot: <?= number_format($summary->periode->target_bobot_panen_gram, 0, ',', '.') ?> gr)</td>
                </tr>
                <tr>
                  <td class="text-muted">Status Batch</td>
                  <td class="text-end">
                    <?php if ($summary->periode->status === 'aktif'): ?>
                      <span class="badge badge-status-aktif">Sedang Berjalan (Aktif)</span>
                    <?php else: ?>
                      <span class="badge badge-status-selesai">Selesai Panen</span>
                    <?php endif; ?>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>

        <div class="pt-3 border-top border-secondary border-opacity-25 d-flex gap-2 justify-content-end">
          <a href="<?= base_url('laporan/cetak/' . $selected_periode_id) ?>" target="_blank" class="btn btn-sm btn-outline-warning">
            <i class="bi bi-printer me-1"></i> Cetak Lembar Produksi
          </a>
        </div>
      </div>
    </div>
  </div>

  <!-- ApexCharts Script Controller for Pemilik -->
  <script>
    document.addEventListener('DOMContentLoaded', function () {
      const chartData = <?= json_encode($chart_data) ?>;

      if (!chartData || !chartData.labels || chartData.labels.length === 0) {
        return;
      }

      // Chart 1: Pertumbuhan Bobot
      const optionsPertumbuhan = {
        series: [
          {
            name: 'Bobot Sampel Aktual (gr)',
            data: chartData.bobot_aktual
          },
          {
            name: 'Standar Performa Cobb/Ross (gr)',
            data: chartData.bobot_standar
          }
        ],
        chart: {
          type: 'area',
          height: 320,
          toolbar: { show: false },
          background: 'transparent'
        },
        colors: ['#84cc16', '#3b82f6'],
        fill: {
          type: 'gradient',
          gradient: {
            shadeIntensity: 1,
            opacityFrom: 0.45,
            opacityTo: 0.05,
            stops: [20, 100]
          }
        },
        dataLabels: { enabled: false },
        stroke: {
          curve: 'smooth',
          width: [3, 2],
          dashArray: [0, 4]
        },
        xaxis: {
          categories: chartData.labels,
          labels: { style: { colors: '#94a3b8' } }
        },
        yaxis: {
          labels: {
            style: { colors: '#94a3b8' },
            formatter: function (val) { return val + ' g'; }
          }
        },
        tooltip: {
          y: { formatter: function (val) { return val + ' gram'; } }
        },
        grid: {
          borderColor: 'rgba(148, 163, 184, 0.15)',
          strokeDashArray: 3
        },
        legend: { position: 'top' }
      };
      new ApexCharts(document.querySelector("#chart-pertumbuhan"), optionsPertumbuhan).render();

      // Chart 2: Analisis Penyebab Kematian (Donut)
      const causeLabels = (chartData.cause_labels && chartData.cause_labels.length > 0) ? chartData.cause_labels : ['Normal'];
      const causeValues = (chartData.cause_values && chartData.cause_values.length > 0) ? chartData.cause_values : [chartData.total_mati];
      
      const optionsCauses = {
        series: causeValues,
        chart: {
          type: 'donut',
          height: 280,
          background: 'transparent'
        },
        labels: causeLabels,
        colors: ['#ef4444', '#f59e0b', '#3b82f6', '#8b5cf6', '#ec4899', '#10b981'],
        legend: { position: 'bottom', fontSize: '11px' },
        dataLabels: { enabled: true },
        tooltip: {
          y: { formatter: function (val) { return val + ' ekor'; } }
        }
      };
      new ApexCharts(document.querySelector("#chart-penyebab-kematian"), optionsCauses).render();

      // Chart 3: Mortalitas
      const optionsMortalitas = {
        series: [
          {
            name: 'Mati Harian (ekor)',
            type: 'column',
            data: chartData.mortalitas_harian
          },
          {
            name: 'Mati Kumulatif (ekor)',
            type: 'line',
            data: chartData.mortalitas_kumulatif
          }
        ],
        chart: {
          height: 300,
          toolbar: { show: false },
          background: 'transparent'
        },
        colors: ['#f59e0b', '#ef4444'],
        stroke: { width: [0, 3] },
        dataLabels: { enabled: false },
        xaxis: {
          categories: chartData.labels,
          labels: { style: { colors: '#94a3b8' } }
        },
        yaxis: [
          {
            title: { text: 'Mati Harian', style: { color: '#f59e0b' } },
            labels: { style: { colors: '#94a3b8' } }
          },
          {
            opposite: true,
            title: { text: 'Total Akumulasi', style: { color: '#ef4444' } },
            labels: { style: { colors: '#94a3b8' } }
          }
        ],
        grid: { borderColor: 'rgba(148, 163, 184, 0.15)' },
        legend: { position: 'top' }
      };
      new ApexCharts(document.querySelector("#chart-mortalitas"), optionsMortalitas).render();

      // Chart 4: Suhu (°C) & Kelembaban (%) Kandang
      const optionsSuhuRH = {
        series: [
          {
            name: 'Suhu Kandang (°C)',
            type: 'line',
            data: chartData.suhu_harian
          },
          {
            name: 'Kelembaban (%)',
            type: 'area',
            data: chartData.kelembaban_harian
          }
        ],
        chart: {
          height: 300,
          toolbar: { show: false },
          background: 'transparent'
        },
        colors: ['#f97316', '#06b6d4'],
        stroke: { width: [3, 2], curve: 'smooth' },
        fill: {
          type: ['solid', 'gradient'],
          gradient: { opacityFrom: 0.35, opacityTo: 0.05 }
        },
        dataLabels: { enabled: false },
        xaxis: {
          categories: chartData.labels,
          labels: { style: { colors: '#94a3b8' } }
        },
        yaxis: [
          {
            title: { text: 'Suhu (°C)', style: { color: '#f97316' } },
            min: 24,
            max: 38,
            labels: { style: { colors: '#94a3b8' } }
          },
          {
            opposite: true,
            title: { text: 'Kelembaban (%)', style: { color: '#06b6d4' } },
            min: 40,
            max: 90,
            labels: { style: { colors: '#94a3b8' } }
          }
        ],
        grid: { borderColor: 'rgba(148, 163, 184, 0.15)' },
        legend: { position: 'top' }
      };
      new ApexCharts(document.querySelector("#chart-suhu-rh"), optionsSuhuRH).render();

      // Chart 5: Daily Feed Intake (Gram / Ekor / Hari)
      const optionsFeedIntake = {
        series: [{
          name: 'Feed Intake (gr/ekor/hari)',
          data: chartData.feed_intake_per_bird
        }],
        chart: {
          type: 'bar',
          height: 280,
          toolbar: { show: false },
          background: 'transparent'
        },
        colors: ['#10b981'],
        plotOptions: {
          bar: { borderRadius: 4, columnWidth: '60%' }
        },
        dataLabels: { enabled: false },
        xaxis: {
          categories: chartData.labels,
          labels: { style: { colors: '#94a3b8' } }
        },
        yaxis: {
          labels: {
            style: { colors: '#94a3b8' },
            formatter: function (val) { return val + ' g'; }
          }
        },
        tooltip: {
          y: { formatter: function (val) { return val + ' gram / ekor'; } }
        },
        grid: { borderColor: 'rgba(148, 163, 184, 0.15)' }
      };
      new ApexCharts(document.querySelector("#chart-feed-intake"), optionsFeedIntake).render();
    });
  </script>

<?php endif; ?>
