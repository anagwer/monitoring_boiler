<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dashboard extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        if (!$this->session->userdata('logged_in')) {
            $this->session->set_flashdata('error', 'Silakan login terlebih dahulu untuk mengakses sistem.');
            redirect('auth');
        }

        $this->load->model('User_model');
        $this->load->model('Kandang_model');
        $this->load->model('Periode_model');
        $this->load->model('Harian_model');
        $this->load->model('Laporan_model');
    }

    public function index()
    {
        $role = $this->session->userdata('role');
        $active_periods = $this->Periode_model->get_active();
        $pengaturan = $this->Laporan_model->get_pengaturan();

        if ($role === 'pemilik') {
            // Dashboard Pemilik / Manajemen
            $selected_periode_id = $this->input->get('periode_id');
            if (empty($selected_periode_id) && !empty($active_periods)) {
                $selected_periode_id = $active_periods[0]->id;
            } elseif (empty($selected_periode_id)) {
                $all_periods = $this->Periode_model->get_all();
                $selected_periode_id = !empty($all_periods) ? $all_periods[0]->id : 0;
            }

            $summary = null;
            $chart_data = null;
            $alerts = array();

            if ($selected_periode_id > 0) {
                $summary = $this->Periode_model->get_summary($selected_periode_id);
                $chart_data = $this->Harian_model->get_chart_data($selected_periode_id);
                
                // Deteksi Alert Mortalitas Tidak Wajar (> 1% dari populasi awal pada satu hari)
                $daily_logs = $this->Harian_model->get_by_periode($selected_periode_id);
                $threshold = max(10, round(($summary->populasi_awal * 0.01))); // 1% atau minimal 10 ekor

                foreach ($daily_logs as $log) {
                    if ((int)$log->jumlah_mati >= $threshold) {
                        $pct = round(((int)$log->jumlah_mati / $summary->populasi_awal) * 100, 2);
                        $alerts[] = array(
                            'hari_ke' => $log->hari_ke,
                            'tgl_input' => $log->tgl_input,
                            'jumlah_mati' => $log->jumlah_mati,
                            'persen' => $pct,
                            'penyebab' => $log->penyebab_kematian,
                            'catatan' => $log->catatan
                        );
                    }
                }
            }

            $data = array(
                'title' => 'Dashboard Monitoring Eksekutif',
                'active_menu' => 'dashboard',
                'active_periods' => $active_periods,
                'selected_periode_id' => $selected_periode_id,
                'summary' => $summary,
                'chart_data' => $chart_data,
                'alerts' => $alerts,
                'pengaturan' => $pengaturan,
                'total_kandang' => $this->Kandang_model->count_all(),
                'total_users' => $this->User_model->count_users(),
                'content_view' => 'dashboard/index_pemilik'
            );

            $this->load->view('layouts/wrapper', $data);

        } else {
            // Dashboard Admin / ABK (Anak Buah Kandang)
            $today = date('Y-m-d');
            $status_kandang_hari_ini = array();
            $semua_sudah_input = true;

            foreach ($active_periods as $p) {
                $check_input = $this->Harian_model->get_today_entry($p->id, $today);
                $calculated_age = $this->Harian_model->calculate_day_number($p->id, $today);

                $status_kandang_hari_ini[] = array(
                    'periode' => $p,
                    'sudah_input' => !empty($check_input),
                    'data_input' => $check_input,
                    'umur_hari_ke' => $calculated_age
                );

                if (empty($check_input)) {
                    $semua_sudah_input = false;
                }
            }

            // Input 5 riwayat terbaru
            $recent_inputs = $this->db->select('input_harian.*, periode.kode_periode, kandang.nama_kandang')
                                      ->from('input_harian')
                                      ->join('periode', 'periode.id = input_harian.periode_id', 'left')
                                      ->join('kandang', 'kandang.id = input_harian.kandang_id', 'left')
                                      ->order_by('input_harian.id', 'DESC')
                                      ->limit(5)
                                      ->get()->result();

            // Aggregate data for ABK charts
            $first_active_id = !empty($active_periods) ? $active_periods[0]->id : 0;
            $abk_chart_data = ($first_active_id > 0) ? $this->Harian_model->get_chart_data($first_active_id) : null;
            $abk_summary = ($first_active_id > 0) ? $this->Periode_model->get_summary($first_active_id) : null;

            // Feed distribution by stage
            $feed_stages = array('Starter' => 0, 'Grower' => 0, 'Finisher' => 0);
            if ($first_active_id > 0) {
                $stage_query = $this->db->select('jenis_pakan, COALESCE(SUM(pakan_kg), 0) as total_kg')
                                        ->where('periode_id', $first_active_id)
                                        ->group_by('jenis_pakan')
                                        ->get('input_harian')->result();
                foreach ($stage_query as $sq) {
                    if (isset($feed_stages[$sq->jenis_pakan])) {
                        $feed_stages[$sq->jenis_pakan] = (float)$sq->total_kg;
                    }
                }
            }

            $data = array(
                'title' => 'Dashboard Operasional ABK',
                'active_menu' => 'dashboard',
                'active_periods' => $active_periods,
                'status_kandang_hari_ini' => $status_kandang_hari_ini,
                'semua_sudah_input' => $semua_sudah_input,
                'recent_inputs' => $recent_inputs,
                'abk_chart_data' => $abk_chart_data,
                'abk_summary' => $abk_summary,
                'feed_stages' => $feed_stages,
                'total_kandang' => $this->Kandang_model->count_all(),
                'content_view' => 'dashboard/index_abk'
            );

            $this->load->view('layouts/wrapper', $data);
        }
    }

    public function chart_data($periode_id)
    {
        $chart_data = $this->Harian_model->get_chart_data($periode_id);
        $summary = $this->Periode_model->get_summary($periode_id);

        header('Content-Type: application/json');
        echo json_encode(array(
            'success' => true,
            'summary' => $summary,
            'chart' => $chart_data
        ));
    }
}
