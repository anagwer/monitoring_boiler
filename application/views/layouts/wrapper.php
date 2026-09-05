<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$this->load->view('layouts/header');
$this->load->view('layouts/sidebar');
?>

<div class="main-wrapper">
  <?php $this->load->view('layouts/navbar'); ?>

  <!-- Flash Notification Alert Area -->
  <div class="container-fluid px-0">
    <?php if ($this->session->flashdata('success')): ?>
      <div class="alert alert-success alert-dismissible fade show border-0 d-flex align-items-center gap-2 mb-4" role="alert" style="background: rgba(16, 185, 129, 0.15); border-left: 4px solid #10b981 !important; color: #a7f3d0; border-radius: 8px;">
        <i class="bi bi-check-circle-fill text-success fs-5"></i>
        <div><?= $this->session->flashdata('success') ?></div>
        <button type="button" class="btn-close btn-close-white ms-auto" data-bs-dismiss="alert" aria-label="Close"></button>
      </div>
    <?php endif; ?>

    <?php if ($this->session->flashdata('error')): ?>
      <div class="alert alert-danger alert-dismissible fade show border-0 d-flex align-items-center gap-2 mb-4" role="alert" style="background: rgba(239, 68, 68, 0.15); border-left: 4px solid #ef4444 !important; color: #fca5a5; border-radius: 8px;">
        <i class="bi bi-exclamation-triangle-fill text-danger fs-5"></i>
        <div><?= $this->session->flashdata('error') ?></div>
        <button type="button" class="btn-close btn-close-white ms-auto" data-bs-dismiss="alert" aria-label="Close"></button>
      </div>
    <?php endif; ?>

    <?php if ($this->session->flashdata('warning')): ?>
      <div class="alert alert-warning alert-dismissible fade show border-0 d-flex align-items-center gap-2 mb-4" role="alert" style="background: rgba(245, 158, 11, 0.15); border-left: 4px solid #f59e0b !important; color: #fde68a; border-radius: 8px;">
        <i class="bi bi-exclamation-circle-fill text-warning fs-5"></i>
        <div><?= $this->session->flashdata('warning') ?></div>
        <button type="button" class="btn-close btn-close-white ms-auto" data-bs-dismiss="alert" aria-label="Close"></button>
      </div>
    <?php endif; ?>
  </div>

  <!-- Dynamic Content View -->
  <?php
  if (isset($content_view)) {
      $this->load->view($content_view);
  }
  ?>

<?php
$this->load->view('layouts/footer');
?>
