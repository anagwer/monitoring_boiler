<div class="page-header d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
  <div>
    <h1 class="page-title mb-1">Manajemen Pengguna</h1>
    <p class="page-subtitle mb-0">Kelola akun Anak Buah Kandang (ABK), Admin Kandang, dan Pemilik / Manajemen PT BTS.</p>
  </div>
  <a href="<?= base_url('pengguna/tambah') ?>" class="btn-brand-accent">
    <i class="bi bi-person-plus-fill me-1"></i> Tambah Pengguna Baru
  </a>
</div>

<div class="card p-4">
  <div class="table-responsive">
    <table class="table table-modern datatable mb-0 w-100">
      <thead>
        <tr>
          <th>Nama Lengkap</th>
          <th>Username</th>
          <th>Hak Akses (Role)</th>
          <th>No. WhatsApp / HP</th>
          <th>Status</th>
          <th>Tanggal Dibuat</th>
          <th class="text-end">Aksi</th>
        </tr>
      </thead>
      <tbody>
        <?php if (!empty($users)): ?>
          <?php foreach ($users as $u): ?>
            <tr>
              <td>
                <div class="d-flex align-items-center gap-2">
                  <div class="d-flex align-items-center justify-content-center bg-success text-white rounded-circle" style="width: 34px; height: 34px; font-size: 0.8rem; font-weight: 700;">
                    <?= strtoupper(substr($u->name, 0, 1)) ?>
                  </div>
                  <div class="fw-bold" style="color: var(--app-text-main);"><?= htmlspecialchars($u->name) ?></div>
                </div>
              </td>
              <td><code><?= htmlspecialchars($u->username) ?></code></td>
              <td>
                <?php if ($u->role === 'pemilik'): ?>
                  <span class="badge bg-warning text-dark"><i class="bi bi-shield-lock-fill me-1"></i> Pemilik / Owner</span>
                <?php else: ?>
                  <span class="badge bg-success"><i class="bi bi-person-badge-fill me-1"></i> Admin / ABK</span>
                <?php endif; ?>
              </td>
              <td><?= htmlspecialchars($u->phone) ? htmlspecialchars($u->phone) : '-' ?></td>
              <td>
                <?php if ($u->status === 'aktif'): ?>
                  <span class="badge badge-status-aktif">Aktif</span>
                <?php else: ?>
                  <span class="badge bg-secondary">Non-Aktif</span>
                <?php endif; ?>
              </td>
              <td class="small text-muted"><?= date('d/m/Y H:i', strtotime($u->created_at)) ?></td>
              <td class="text-end">
                <div class="d-flex justify-content-end gap-1">
                  <?php if ($u->role === 'pemilik' && $this->session->userdata('role') !== 'pemilik'): ?>
                    <button type="button" class="btn btn-sm btn-outline-secondary disabled" title="Akun Pemilik dilindungi (Hanya dapat diubah oleh Pemilik)" style="opacity: 0.55; cursor: not-allowed;">
                      <i class="bi bi-pencil"></i>
                    </button>
                  <?php else: ?>
                    <a href="<?= base_url('pengguna/edit/' . $u->id) ?>" class="btn btn-sm btn-outline-warning" title="Edit Pengguna">
                      <i class="bi bi-pencil"></i>
                    </a>
                  <?php endif; ?>
                  <?php if ($this->session->userdata('user_id') != $u->id): ?>
                    <?php if ($u->role === 'pemilik' && $this->session->userdata('role') !== 'pemilik'): ?>
                      <button type="button" class="btn btn-sm btn-outline-secondary disabled" title="Akun Pemilik dilindungi (Tidak dapat dihapus oleh ABK/Admin)" style="opacity: 0.55; cursor: not-allowed;">
                        <i class="bi bi-shield-lock-fill text-warning"></i>
                      </button>
                    <?php else: ?>
                      <a href="<?= base_url('pengguna/hapus/' . $u->id) ?>" class="btn btn-sm btn-outline-danger" title="Hapus Pengguna" onclick="return confirm('Apakah Anda yakin ingin menghapus akun ini?');">
                        <i class="bi bi-trash"></i>
                      </a>
                    <?php endif; ?>
                  <?php endif; ?>
                </div>
              </td>
            </tr>
          <?php endforeach; ?>
        <?php endif; ?>
      </tbody>
    </table>
  </div>
</div>
