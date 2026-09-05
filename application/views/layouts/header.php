<!DOCTYPE html>
<html lang="id" data-bs-theme="light">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?= isset($title) ? htmlspecialchars($title) . " | PT BTS Broiler Farm" : "PT BTS - Sistem Monitoring Peternakan Ayam Broiler" ?></title>

  <!-- Favicon -->
  <link rel="icon" type="image/png" href="<?= base_url('assets/images/favicon.ico') ?>">

  <!-- Local Third-Party Libraries (100% Offline Compatible) -->
  <link rel="stylesheet" href="<?= base_url('assets/libs/bootstrap/css/bootstrap.min.css') ?>">
  <link rel="stylesheet" href="<?= base_url('assets/libs/bootstrap-icons/bootstrap-icons.css') ?>">
  <link rel="stylesheet" href="<?= base_url('assets/libs/apexcharts/apexcharts.css') ?>">
  <link rel="stylesheet" href="<?= base_url('assets/libs/flatpickr/flatpickr.min.css') ?>">

  <!-- DataTables CSS -->
  <link rel="stylesheet" href="<?= base_url('assets/libs/datatables-bs4/css/dataTables.bootstrap4.min.css') ?>">
  <link rel="stylesheet" href="<?= base_url('assets/libs/datatables-responsive/css/responsive.bootstrap4.min.css') ?>">

  <!-- Main Design System & Custom Stylesheet -->
  <link rel="stylesheet" href="<?= base_url('assets/css/main.css') ?>">

  <style>
    :root {
      --app-bg: #F4F6F5;
      --app-card-bg: #FFFFFF;
      --app-card-border: #E2E8F0;
      --app-text-main: #0F172A;
      --app-text-muted: #64748B;
      --app-table-th-bg: #072F1F;
      --app-table-th-text: #FFFFFF;
      --app-table-td-text: #1E293B;
      --app-table-td-border: #E2E8F0;
      --app-table-hover: #F8FAFC;
      --app-input-bg: #FFFFFF;
      --app-input-border: #CBD5E1;
      --app-input-text: #0F172A;
      --app-header-title: #072F1F;
      --app-box-highlight: #F8FAF9;
      --app-box-highlight-border: #E2E8F0;
      --brand-lime: #84cc16;
      --brand-lime-dark: #4d7c0f;
    }

    [data-bs-theme="dark"], body.dark-mode {
      --app-bg: #06130D;
      --app-card-bg: #0C1A14;
      --app-card-border: #1E3627;
      --app-text-main: #F8FAFC;
      --app-text-muted: #94A3B8;
      --app-table-th-bg: #04100A;
      --app-table-th-text: #B4F105;
      --app-table-td-text: #E2E8F0;
      --app-table-td-border: #16281E;
      --app-table-hover: rgba(180, 241, 5, 0.05);
      --app-input-bg: #0C1A14;
      --app-input-border: #1E3627;
      --app-input-text: #F8FAFC;
      --app-header-title: #FFFFFF;
      --app-box-highlight: rgba(255, 255, 255, 0.02);
      --app-box-highlight-border: #16281E;
      --brand-lime: #B4F105;
      --brand-lime-dark: #B4F105;
    }

    body {
      background-color: var(--app-bg) !important;
      color: var(--app-text-main) !important;
      transition: background-color 0.25s ease, color 0.25s ease;
    }

    .main-wrapper {
      background-color: var(--app-bg) !important;
    }

    .page-title {
      color: var(--app-header-title) !important;
      font-weight: 800;
      letter-spacing: -0.02em;
    }

    .page-subtitle {
      color: var(--app-text-muted) !important;
    }

    /* Cards */
    .card {
      background-color: var(--app-card-bg) !important;
      border: 1px solid var(--app-card-border) !important;
      border-radius: 16px !important;
      box-shadow: 0 4px 20px rgba(0,0,0,0.03);
      transition: background-color 0.25s ease, border-color 0.25s ease, transform 0.2s ease;
    }

    .card-kpi:hover {
      transform: translateY(-3px);
    }

    .box-highlight {
      background-color: var(--app-box-highlight) !important;
      border: 1px solid var(--app-box-highlight-border) !important;
      border-radius: 12px;
    }

    /* Table Styles */
    .table-modern {
      vertical-align: middle;
      color: var(--app-table-td-text) !important;
    }

    .table-modern thead th {
      background-color: var(--app-table-th-bg) !important;
      color: var(--app-table-th-text) !important;
      font-weight: 700;
      font-size: 0.8rem;
      text-transform: uppercase;
      letter-spacing: 0.05em;
      padding: 12px 14px;
      border: none !important;
    }

    .table-modern tbody td {
      padding: 12px 14px;
      color: var(--app-table-td-text) !important;
      border-bottom: 1px solid var(--app-table-td-border) !important;
      background-color: transparent !important;
    }

    .table-modern tbody tr:hover td {
      background-color: var(--app-table-hover) !important;
    }

    .table-responsive {
      border-radius: 12px;
      overflow-x: auto;
    }

    /* Form Controls */
    .form-control-modern, .form-select-modern {
      background-color: var(--app-input-bg) !important;
      border: 1px solid var(--app-input-border) !important;
      color: var(--app-input-text) !important;
      border-radius: 8px;
      padding: 10px 14px;
    }

    .form-control-modern:focus, .form-select-modern:focus {
      border-color: var(--brand-lime) !important;
      box-shadow: 0 0 0 0.2rem rgba(132, 204, 22, 0.25) !important;
    }

    .form-label-custom {
      font-weight: 600;
      font-size: 0.875rem;
      color: var(--app-text-main);
      margin-bottom: 6px;
    }

    /* Metric & Badges */
    .metric-value-highlight {
      font-size: 1.85rem;
      font-weight: 800;
      letter-spacing: -0.02em;
      color: var(--app-text-main);
    }

    .metric-subtext {
      font-size: 0.825rem;
      color: var(--app-text-muted);
    }

    .badge-closed-house {
      background: linear-gradient(135deg, #10b981 0%, #059669 100%);
      color: #ffffff;
      font-weight: 600;
      border-radius: 6px;
      padding: 5px 10px;
    }

    .badge-open-house {
      background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%);
      color: #ffffff;
      font-weight: 600;
      border-radius: 6px;
      padding: 5px 10px;
    }

    .badge-status-aktif {
      background: rgba(132, 204, 22, 0.15);
      color: #4d7c0f;
      border: 1px solid rgba(132, 204, 22, 0.4);
      font-weight: 700;
      padding: 5px 10px;
      border-radius: 6px;
    }

    [data-bs-theme="dark"] .badge-status-aktif {
      color: #B4F105;
      background: rgba(180, 241, 5, 0.15);
      border-color: rgba(180, 241, 5, 0.4);
    }

    .badge-status-selesai {
      background: rgba(148, 163, 184, 0.15);
      color: #64748b;
      border: 1px solid rgba(148, 163, 184, 0.3);
      font-weight: 600;
      padding: 5px 10px;
      border-radius: 6px;
    }

    .badge-status-terisi {
      background: rgba(245, 158, 11, 0.15);
      color: #d97706;
      border: 1px solid rgba(245, 158, 11, 0.4);
      font-weight: 700;
      padding: 5px 10px;
      border-radius: 6px;
    }

    .badge-status-tersedia {
      background: rgba(16, 185, 129, 0.15);
      color: #059669;
      border: 1px solid rgba(16, 185, 129, 0.4);
      font-weight: 700;
      padding: 5px 10px;
      border-radius: 6px;
    }

    .btn-brand-accent {
      background-color: #B4F105;
      color: #06130d;
      font-weight: 700;
      border-radius: 8px;
      padding: 10px 20px;
      border: none;
      transition: all 0.2s ease;
    }

    .btn-brand-accent:hover {
      background-color: #a0d804;
      color: #000000;
      transform: translateY(-1px);
    }

    .btn-brand-outline {
      background: transparent;
      border: 1.5px solid #072F1F;
      color: #072F1F;
      font-weight: 600;
      border-radius: 8px;
      padding: 8px 16px;
      transition: all 0.2s ease;
    }

    [data-bs-theme="dark"] .btn-brand-outline {
      border-color: #B4F105;
      color: #B4F105;
    }

    .btn-brand-outline:hover {
      background-color: rgba(7, 47, 31, 0.08);
    }

    [data-bs-theme="dark"] .btn-brand-outline:hover {
      background-color: rgba(180, 241, 5, 0.1);
    }

    /* DataTables Custom Polish */
    .dataTables_wrapper .dataTables_length,
    .dataTables_wrapper .dataTables_filter,
    .dataTables_wrapper .dataTables_info,
    .dataTables_wrapper .dataTables_paginate {
      color: var(--app-text-muted) !important;
      font-size: 0.85rem;
      margin-bottom: 12px;
      margin-top: 8px;
    }

    .dataTables_wrapper .dataTables_filter input {
      background-color: var(--app-input-bg) !important;
      border: 1px solid var(--app-input-border) !important;
      color: var(--app-input-text) !important;
      border-radius: 6px;
      padding: 6px 12px;
      margin-left: 8px;
    }

    .dataTables_wrapper .dataTables_length select {
      background-color: var(--app-input-bg) !important;
      border: 1px solid var(--app-input-border) !important;
      color: var(--app-input-text) !important;
      border-radius: 6px;
      padding: 4px 8px;
      margin: 0 6px;
    }

    .page-item.active .page-link {
      background-color: #072F1F !important;
      border-color: #072F1F !important;
      color: #ffffff !important;
    }

    [data-bs-theme="dark"] .page-item.active .page-link {
      background-color: #B4F105 !important;
      border-color: #B4F105 !important;
      color: #06130D !important;
      font-weight: 700;
    }

    .page-link {
      background-color: var(--app-card-bg) !important;
      border-color: var(--app-card-border) !important;
      color: var(--app-text-main) !important;
    }

    /* Theme Switcher Button */
    .theme-toggle-btn {
      width: 40px;
      height: 40px;
      border-radius: 50%;
      border: 1px solid var(--app-card-border);
      background-color: var(--app-card-bg);
      color: var(--app-text-main);
      display: flex;
      align-items: center;
      justify-content: center;
      cursor: pointer;
      transition: all 0.2s ease;
    }

    .theme-toggle-btn:hover {
      transform: scale(1.05);
      border-color: var(--brand-lime);
    }

    @media print {
      .sidebar-wrapper, .navbar-custom, .footer-custom, .btn-no-print, .btn-date-picker, .dropdown, .dataTables_filter, .dataTables_length, .dataTables_paginate, .dataTables_info, .theme-toggle-btn {
        display: none !important;
      }
      .main-wrapper {
        margin-left: 0 !important;
        padding: 0 !important;
        background: #ffffff !important;
        color: #000000 !important;
      }
      body {
        background: #ffffff !important;
        color: #000000 !important;
      }
    }
  </style>

  <!-- Prevent theme flicker script -->
  <script>
    (function () {
      const savedTheme = localStorage.getItem('bts_theme') || 'light';
      document.documentElement.setAttribute('data-bs-theme', savedTheme);
      if (savedTheme === 'dark') {
        document.documentElement.classList.add('dark-mode');
      }
    })();
  </script>
</head>
<body>
