<?php
$s = $laporan['summary'];
$pt = $laporan['pengaturan'];
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Cetak Laporan Produksi - <?= htmlspecialchars($s->periode->kode_periode) ?></title>
  <link rel="stylesheet" href="<?= base_url('assets/libs/bootstrap/css/bootstrap.min.css') ?>">
  <link rel="stylesheet" href="<?= base_url('assets/libs/bootstrap-icons/bootstrap-icons.css') ?>">
  
  <style>
    body {
      background-color: #f8fafc;
      color: #0f172a;
      font-family: 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;
      font-size: 11pt;
    }
    .print-page {
      max-width: 960px;
      margin: 20px auto;
      background: #ffffff;
      padding: 35px 45px;
      box-shadow: 0 4px 12px rgba(0,0,0,0.08);
      border-radius: 8px;
    }
    .kop-surat {
      border-bottom: 3px double #0f172a;
      padding-bottom: 12px;
      margin-bottom: 20px;
    }
    .table-print {
      width: 100%;
      border-collapse: collapse;
      font-size: 9.5pt;
    }
    .table-print th, .table-print td {
      border: 1px solid #334155;
      padding: 5px 8px;
    }
    .table-print th {
      background-color: #f1f5f9;
      font-weight: 700;
      text-align: center;
    }
    .kpi-box {
      border: 1px solid #cbd5e1;
      background-color: #f8fafc;
      border-radius: 6px;
      padding: 10px 14px;
    }
    .signature-area {
      margin-top: 40px;
      page-break-inside: avoid;
    }
    .signature-box {
      text-align: center;
      width: 250px;
    }
    .signature-space {
      height: 70px;
    }
    @media print {
      body {
        background: #ffffff;
        margin: 0;
        padding: 0;
      }
      .print-page {
        max-width: 100%;
        margin: 0;
        padding: 15px;
        box-shadow: none;
        border-radius: 0;
      }
      .btn-print-action {
        display: none !important;
      }
    }
  </style>
</head>
<body>

  <!-- Floating Action Buttons for Screen View -->
  <div class="container text-center my-3 btn-print-action">
    <button onclick="window.print()" class="btn btn-primary px-4 py-2 me-2">
      <i class="bi bi-printer-fill me-1"></i> Cetak Dokumen / Simpan PDF
    </button>
    <a href="<?= base_url('laporan?periode_id=' . $s->periode->id) ?>" class="btn btn-outline-secondary px-4 py-2">
      <i class="bi bi-arrow-left me-1"></i> Kembali ke Sistem
    </a>
  </div>

  <div class="print-page">
    <!-- KOP SURAT RESMI PT BTS -->
    <div class="kop-surat d-flex align-items-center justify-content-between">
      <div class="d-flex align-items-center gap-3">
        <div class="border border-dark p-2 rounded text-center" style="width: 65px; height: 65px;">
          <i class="bi bi-egg-fried fs-1 text-dark"></i>
        </div>
        <div>
          <h3 class="fw-bold mb-0 text-uppercase" style="letter-spacing: 0.05em;"><?= htmlspecialchars($pt->nama_pt) ?></h3>
          <p class="mb-0 small text-muted"><strong>SISTEM MONITORING & MANAJEMEN PETERNAKAN AYAM BROILER</strong></p>
          <p class="mb-0 small text-muted"><?= htmlspecialchars($pt->alamat) ?> | Telp: <?= htmlspecialchars($pt->no_telp) ?></p>
        </div>
      </div>
      <div class="text-end small">
        <span class="badge bg-dark text-white px-2 py-1">LEMBAR RESMI</span>
      </div>
    </div>

    <!-- DOCUMENT TITLE -->
    <div class="text-center mb-4">
      <h5 class="fw-bold text-uppercase mb-1 text-decoration-underline">LAPORAN REKAPITULASI PRODUKSI & PERFORMA KANDANG</h5>
      <span class="small text-muted">Nomor Registrasi Batch: <strong><?= htmlspecialchars($s->periode->kode_periode) ?></strong></span>
    </div>

    <!-- METADATA BATCH -->
    <div class="row g-2 mb-3 small">
      <div class="col-6">
        <table class="table table-sm table-borderless mb-0">
          <tr>
            <td style="width: 140px;"><strong>Unit Kandang</strong></td>
            <td>: <?= htmlspecialchars($s->periode->nama_kandang) ?> (<?= htmlspecialchars($s->periode->kode_kandang) ?> - <?= $s->periode->tipe_kandang ?>)</td>
          </tr>
          <tr>
            <td><strong>Jenis Strain Bibit</strong></td>
            <td>: <?= htmlspecialchars($s->periode->strain) ?></td>
          </tr>
          <tr>
            <td><strong>Tanggal Masuk DOC</strong></td>
            <td>: <?= date('d F Y', strtotime($s->periode->tgl_masuk_doc)) ?> (Hari ke-0)</td>
          </tr>
        </table>
      </div>
      <div class="col-6">
        <table class="table table-sm table-borderless mb-0">
          <tr>
            <td style="width: 140px;"><strong>Populasi Awal DOC</strong></td>
            <td>: <strong><?= number_format($s->populasi_awal, 0, ',', '.') ?> Ekor</strong></td>
          </tr>
          <tr>
            <td><strong>Status Siklus</strong></td>
            <td>: <?= ($s->periode->status === 'selesai') ? 'Selesai Panen (Tgl: ' . date('d/m/Y', strtotime($s->periode->tgl_selesai_panen)) . ')' : 'Masih Aktif (Hari ke-' . $s->hari_terakhir . ')' ?></td>
          </tr>
          <tr>
            <td><strong>Target Pemeliharaan</strong></td>
            <td>: <?= $s->periode->target_panen_hari ?> Hari (Target Bobot: <?= number_format($s->periode->target_bobot_panen_gram, 0, ',', '.') ?> gr)</td>
          </tr>
        </table>
      </div>
    </div>

    <!-- TABEL LOG PRODUKSI HARIAN LENGKAP -->
    <table class="table-print mb-3">
      <thead>
        <tr>
          <th rowspan="2" style="width: 50px;">Umur</th>
          <th rowspan="2" style="width: 80px;">Tanggal</th>
          <th rowspan="2">Populasi Awal</th>
          <th colspan="2">Kematian (Ekor)</th>
          <th rowspan="2">Sisa Hidup</th>
          <th colspan="2">Pakan (Kg)</th>
          <th rowspan="2">Bobot Sampel</th>
          <th rowspan="2">Obat / Vitamin</th>
          <th rowspan="2">Keterangan / Gejala</th>
        </tr>
        <tr>
          <th>Harian</th>
          <th>Akumulasi</th>
          <th>Harian</th>
          <th>Akumulasi</th>
        </tr>
      </thead>
      <tbody>
        <?php if (empty($laporan['rows'])): ?>
          <tr>
            <td colspan="11" class="text-center py-3">Belum ada rekaman input harian.</td>
          </tr>
        <?php else: ?>
          <?php foreach ($laporan['rows'] as $r): ?>
            <tr>
              <td class="text-center">H-<?= $r->hari_ke ?></td>
              <td class="text-center"><?= date('d/m/Y', strtotime($r->tgl_input)) ?></td>
              <td class="text-end"><?= number_format($r->populasi_awal_hari, 0, ',', '.') ?></td>
              <td class="text-center"><?= $r->jumlah_mati ?></td>
              <td class="text-center"><?= number_format($r->akumulasi_mati, 0, ',', '.') ?></td>
              <td class="text-end fw-bold"><?= number_format($r->sisa_hidup, 0, ',', '.') ?></td>
              <td class="text-end"><?= number_format($r->pakan_kg, 1, ',', '.') ?></td>
              <td class="text-end"><?= number_format($r->akumulasi_pakan_kg, 1, ',', '.') ?></td>
              <td class="text-center"><?= $r->bobot_sampel_gram > 0 ? number_format($r->bobot_sampel_gram, 0, ',', '.') . ' gr' : '-' ?></td>
              <td><?= htmlspecialchars($r->obat_vitamin) ? htmlspecialchars($r->obat_vitamin) : '-' ?></td>
              <td><small><?= htmlspecialchars($r->penyebab_kematian . ($r->catatan ? ' - ' . $r->catatan : '')) ?></small></td>
            </tr>
          <?php endforeach; ?>
        <?php endif; ?>
      </tbody>
      <tfoot>
        <tr class="fw-bold bg-light">
          <td colspan="3" class="text-center">TOTAL KESELURUHAN</td>
          <td class="text-center"><?= number_format($s->total_mati, 0, ',', '.') ?></td>
          <td class="text-center"><?= number_format($s->total_mati, 0, ',', '.') ?></td>
          <td class="text-end"><?= number_format($s->sisa_populasi, 0, ',', '.') ?> Ekor</td>
          <td class="text-end"><?= number_format($s->total_pakan_kg, 1, ',', '.') ?></td>
          <td class="text-end"><?= number_format($s->total_pakan_kg, 1, ',', '.') ?> Kg</td>
          <td class="text-center"><?= $s->bobot_terakhir_gram > 0 ? number_format($s->bobot_terakhir_gram, 0, ',', '.') . ' gr' : '-' ?></td>
          <td colspan="2" class="text-center">Total Karung: <?= number_format($s->total_pakan_karung, 1, ',', '.') ?> Karung</td>
        </tr>
      </tfoot>
    </table>

    <!-- RINGKASAN REKAPITULASI AKHIR (KPI BOXES) -->
    <div class="row g-2 mb-4 small">
      <div class="col-4">
        <div class="kpi-box">
          <div class="text-muted">Total Kematian (Mortalitas):</div>
          <div class="fw-bold fs-6"><?= number_format($s->total_mati, 0, ',', '.') ?> Ekor (<?= $s->mortalitas_persen ?>%)</div>
          <small>Daya Hidup (Livability): <strong><?= $s->livability_persen ?>%</strong></small>
        </div>
      </div>

      <div class="col-4">
        <div class="kpi-box">
          <div class="text-muted">Total Pakan Masuk:</div>
          <div class="fw-bold fs-6"><?= number_format($s->total_pakan_kg, 1, ',', '.') ?> Kg</div>
          <small>Setara <?= number_format($s->total_pakan_karung, 1, ',', '.') ?> Karung (@ 50 Kg)</small>
        </div>
      </div>

      <div class="col-4">
        <div class="kpi-box">
          <div class="text-muted">Total Bobot Panen:</div>
          <div class="fw-bold fs-6">
            <?= ((float)$s->periode->total_bobot_panen_kg > 0) ? number_format($s->periode->total_bobot_panen_kg, 1, ',', '.') . ' Kg' : 'Belum Ditimbang' ?>
          </div>
          <small>Populasi Panen: <strong><?= number_format($s->sisa_populasi, 0, ',', '.') ?> Ekor</strong></small>
        </div>
      </div>

      <div class="col-6">
        <div class="kpi-box">
          <div class="text-muted">Feed Conversion Ratio (FCR Final):</div>
          <div class="fw-bold fs-5 text-primary"><?= ($s->fcr_final > 0) ? $s->fcr_final : ($s->fcr_sementara > 0 ? $s->fcr_sementara . ' (Sementara)' : '-') ?></div>
          <small>Standar Industri: &le; 1.55 (Semakin rendah semakin efisien)</small>
        </div>
      </div>

      <div class="col-6">
        <div class="kpi-box">
          <div class="text-muted">Indeks Prestasi (IP):</div>
          <div class="fw-bold fs-5 text-success"><?= ($s->ip_final > 0) ? $s->ip_final : '-' ?></div>
          <small>Klasifikasi: &ge; 350 (Sangat Baik / Istimewa)</small>
        </div>
      </div>
    </div>

    <!-- TANDA TANGAN SERAH TERIMA -->
    <div class="signature-area d-flex justify-content-between">
      <div class="signature-box">
        <div class="small text-muted mb-1">Dibuat Oleh,</div>
        <div class="fw-bold">Petugas / ABK Kandang</div>
        <div class="signature-space"></div>
        <div class="border-bottom border-dark pb-1 fw-bold">( ............................................ )</div>
        <small class="text-muted">Anak Buah Kandang PT BTS</small>
      </div>

      <div class="signature-box">
        <div class="small text-muted mb-1">Subang, <?= date('d F Y') ?></div>
        <div class="fw-bold">Diperiksa & Disetujui,</div>
        <div class="signature-space"></div>
        <div class="border-bottom border-dark pb-1 fw-bold">( H. Bambang Tri Santoso )</div>
        <small class="text-muted">Pemilik / Pimpinan PT BTS</small>
      </div>
    </div>
  </div>

</body>
</html>
