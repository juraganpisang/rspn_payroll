<?php

class RS_Controller extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->checkSession();
    }

    function onlyAjax()
    {
        if (!$this->input->is_ajax_request()) {
            show_error('No Direct Script Access Allowed');
        }
    }

    function checkSession()
    {
        if (!$this->session->has_userdata('logged_in')) {
            redirect(base_url('auth'));
        }
    }

    function sendHeader($menu_active = '', $menu_title = '', $sidebar = 'sidebar_minimize')
    {
        $arr = [
            'nav_id' => $menu_active,
            'title' => $menu_title,
            'sidebar_mode' => $sidebar,
            'u_id' => $this->session->userdata('user_id'),
            'u_name' => $this->session->userdata('user_name'),
            'u_fullname' => $this->session->userdata('user_fullname'),
            'u_level' => $this->session->userdata('user_level'),
            'u_priv' => $this->session->userdata('user_priv'),
            's_unit' => $this->session->userdata('unit'),
        ];
        // $arr['nav_menu'] = $this->nav_menu($arr['u_priv']);
        return $arr;
    }

    private function nav_menu($priv)
    {
        ob_start();
        switch ($priv) {
            case 'all':
?>
                <li class="nav-header text-navy text-center text-bold">Vclaim</li>
                <li class="nav-item">
                    <a id="nav-m-pasien-bpjs" href="<?= base_url('pasien/bpjs') ?>" class="nav-link">
                        <i class="fas fa-id-card nav-icon"></i>
                        <p>Peserta BPJS</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a id="nav-pendaftaran-kunjungan-bpjs" href="<?= base_url('pendaftaran/kunjungan/bpjs') ?>" class="nav-link">
                        <i class="fas fa-laptop-medical nav-icon"></i>
                        <p>Monitoring Pasien</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a id="nav-approval-sep" href="<?= base_url('pendaftaran/pengajuansep/approval') ?>" class="nav-link">
                        <i class="fas fa-hand-holding-medical nav-icon"></i>
                        <p>Persetujuan SEP</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="#" class="nav-link">
                        <i class="nav-icon fas fa-briefcase-medical"></i>
                        <p>Surat Kontrol <i class="fas fa-angle-left right"></i></p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a id="nav-pendaftaran-sk" href="<?= base_url('pendaftaran/skontrol') ?>" class="nav-link">
                                <i class="fas fa-file-medical-alt nav-icon text-indigo"></i>
                                <p>Kunjungan Kontrol</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a id="nav-pendaftaran-spri" href="<?= base_url('pendaftaran/skontrol/spri') ?>" class="nav-link">
                                <i class="fas fa-file-medical-alt nav-icon text-maroon"></i>
                                <p>Kunjungan Inap</p>
                            </a>
                        </li>
                    </ul>
                </li>
                <li class="nav-item">
                    <a href="#" class="nav-link">
                        <i class="nav-icon fas fa-ambulance"></i>
                        <p>Rujukan<i class="fas fa-angle-left right"></i></p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a id="nav-form-rujukan" href="<?= base_url('rujuk/rujukan') ?>" class="nav-link">
                                <i class="fas fa-file-prescription nav-icon text-success"></i>
                                <p>Form Rujukan</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a id="nav-khusus-rujukan" href="<?= base_url('rujuk/rujukan/khusus') ?>" class="nav-link">
                                <i class="fas fa-file-prescription nav-icon text-fuchsia"></i>
                                <p>Rujukan Khusus</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a id="nav-khusus-rujukan" href="<?= base_url('rujuk/rujukbalik') ?>" class="nav-link">
                                <i class="fas fa-file-prescription nav-icon text-olive"></i>
                                <p>PRB</p>
                            </a>
                        </li>
                    </ul>
                </li>
                <li class="nav-header text-navy text-center text-bold">Antrean online</li>
                <li class="nav-item">
                    <a id="nav-dashboard-bulan" href="<?= base_url('antrean/dashboard/per_bulan') ?>" class="nav-link">
                        <i class="fas fa-chart-bar nav-icon"></i>
                        <p>Dashboard</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a id="nav-dashboard-tanggal" href="<?= base_url('antrean/dashboard/per_tanggal') ?>" class="nav-link pl-4">
                        <small>
                            <i class="fas fa-chart-area nav-icon"></i>
                            <p>Dashboard per hari</p>
                        </small>
                    </a>
                </li>
                <li class="nav-item">
                    <a id="nav-a-simrs" href="<?= base_url('antrean/manage/appointment') ?>" class="nav-link">
                        <i class="fas fa-address-book nav-icon"></i>
                        <p>Appointment</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a id="nav-antrean" href="<?= base_url('antrean/task') ?>" class="nav-link">
                        <i class="fas fa-users nav-icon"></i>
                        <p>Antrean</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a id="nav-jadwal-dokter" href="<?= base_url('antrean/dokter') ?>" class="nav-link">
                        <i class="fas fa-user-md nav-icon"></i>
                        <p>Jadwal Dokter</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="#" class="nav-link">
                        <i class="nav-icon fas fa-random"></i>
                        <p>Waktu Pelayanan <i class="fas fa-angle-left right"></i></p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a id="nav-wp-farmasi" href="<?= base_url('antrean/task/admisi') ?>" class="nav-link">
                                <i class="fas fa-user-clock nav-icon"></i>
                                <p>Admisi</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a id="nav-wp-poli" href="<?= base_url('pendaftaran/ralan/poliklinik') ?>" class="nav-link">
                                <i class="fas fa-stethoscope nav-icon"></i>
                                <p>Poliklinik (Realtime)</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a id="nav-wp-poli-manual" href="<?= base_url('pendaftaran/ralan/poliklinik_manual') ?>" class="nav-link">
                                <i class="fas fa-stethoscope nav-icon"></i>
                                <p>Poliklinik (Manual)</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a id="nav-wp-farmasi" href="<?= base_url('antrean/Task/farmasi') ?>" class="nav-link">
                                <i class="fas fa-pills nav-icon"></i>
                                <p>Farmasi</p>
                            </a>
                        </li>
                    </ul>
                </li>
                <li class="nav-item">
                    <a id="nav-wp-poli-manual" href="<?= base_url('antrean/pending') ?>" class="nav-link">
                        <i class="fas fa-folder-open nav-icon"></i>
                        <p>Antrean Pending</p>
                    </a>
                </li>
                <li class="nav-header text-navy text-center text-bold">SIMRS</li>
                <li class="nav-item">
                    <a href="#" class="nav-link">
                        <i class="nav-icon fas fa-paste"></i>
                        <p>Pendaftaran Pasien <i class="fas fa-angle-left right"></i></p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a id="nav-pendaftaran-kunjungan" href="<?= base_url('pendaftaran/kunjungan') ?>" class="nav-link">
                                <i class="fas fa-notes-medical nav-icon"></i>
                                <p>Kunjungan</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a id="nav-pendaftaran-igd" href="<?= base_url('pendaftaran/igd') ?>" class="nav-link">
                                <i class="fas fa-file-medical nav-icon text-danger"></i>
                                <p>IGD</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a id="nav-pendaftaran-ralan" href="<?= base_url('pendaftaran/ralan') ?>" class="nav-link">
                                <i class="fas fa-file-alt nav-icon text-primary"></i>
                                <p>Rawat Jalan</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a id="nav-ralan-tracer" href="<?= base_url('pendaftaran/ralan/tracer') ?>" class="nav-link pl-4">
                                <small>
                                    <i class="fas fa-file-prescription nav-icon"></i>
                                    <p>Tracer</p>
                                </small>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a id="nav-pendaftaran-pm" href="<?= base_url('pendaftaran/penmed') ?>" class="nav-link">
                                <i class="fas fa-file-alt nav-icon text-primary"></i>
                                <p>Penunjang, ODC & IKO</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a id="nav-pendaftaran-ranap" href="<?= base_url('pendaftaran/ranap') ?>" class="nav-link">
                                <i class="fas fa-file-contract nav-icon text-warning"></i>
                                <p>Rawat Inap</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a id="nav-ranap-pulang" href="<?= base_url('pendaftaran/ranap/update_pulang') ?>" class="nav-link pl-4">
                                <small>
                                    <i class="fab fa-accusoft nav-icon"></i>
                                    <p>Update pulang</p>
                                </small>
                            </a>
                        </li>
                    </ul>
                </li>
                <li class="nav-item">
                    <a id="nav-antrean-sinkronisasi" href="<?= base_url('antrean/task/sinkronisasi') ?>" class="nav-link">
                        <i class="fas fa-upload nav-icon"></i>
                        <p>Sinkronisasi Antrean</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="#" class="nav-link">
                        <i class="nav-icon fas fa-database"></i>
                        <p>Master <i class="fas fa-angle-left right"></i></p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a id="nav-m-pasien" href="<?= base_url('pasien') ?>" class="nav-link">
                                <i class="fas fa-hospital-user nav-icon"></i>
                                <p>Pasien</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a id="nav-m-pasien" href="<?= base_url('user') ?>" class="nav-link">
                                <i class="fas fa-user-nurse nav-icon"></i>
                                <p>User</p>
                            </a>
                        </li>
                    </ul>
                </li>
                <li class="nav-item">
                    <a id="nav-config" href="<?= base_url('configuration') ?>" class="nav-link">
                        <i class="fas fa-cogs nav-icon"></i>
                        <p>Konfigurasi</p>
                    </a>
                </li>
            <?php
                break;
            case 'tpp':
            ?>
                <li class="nav-item">
                    <a href="#" class="nav-link">
                        <i class="nav-icon fas fa-paste"></i>
                        <p>Pendaftaran Pasien <i class="fas fa-angle-left right"></i></p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a id="nav-pendaftaran-kunjungan" href="<?= base_url('pendaftaran/kunjungan') ?>" class="nav-link">
                                <i class="fas fa-notes-medical nav-icon text-navy"></i>
                                <p>Kunjungan</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a id="nav-pendaftaran-igd" href="<?= base_url('pendaftaran/igd') ?>" class="nav-link">
                                <i class="fas fa-file-medical nav-icon text-danger"></i>
                                <p>IGD</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a id="nav-pendaftaran-ralan" href="<?= base_url('pendaftaran/ralan') ?>" class="nav-link">
                                <i class="fas fa-file-alt nav-icon text-primary"></i>
                                <p>Rawat Jalan</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a id="nav-pendaftaran-pm" href="<?= base_url('pendaftaran/penmed') ?>" class="nav-link">
                                <i class="fas fa-file-alt nav-icon text-primary"></i>
                                <p>Penunjang, ODC & IKO</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a id="nav-pendaftaran-ranap" href="<?= base_url('pendaftaran/ranap') ?>" class="nav-link">
                                <i class="fas fa-file-contract nav-icon text-warning"></i>
                                <p>Rawat Inap</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a id="nav-ranap-pulang" href="<?= base_url('pendaftaran/ranap/update_pulang') ?>" class="nav-link pl-4">
                                <small>
                                    <i class="fab fa-accusoft nav-icon"></i>
                                    <p>Update pulang</p>
                                </small>
                            </a>
                        </li>
                    </ul>
                </li>
                <li class="nav-item">
                    <a id="nav-pendaftaran-kunjungan-bpjs" href="<?= base_url('pendaftaran/kunjungan/bpjs') ?>" class="nav-link">
                        <i class="fas fa-laptop-medical nav-icon"></i>
                        <p>Monitoring Pasien</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a id="nav-approval-sep" href="<?= base_url('pendaftaran/pengajuansep/approval') ?>" class="nav-link">
                        <i class="fas fa-hand-holding-medical nav-icon"></i>
                        <p>Persetujuan SEP</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="#" class="nav-link">
                        <i class="nav-icon fas fa-briefcase-medical"></i>
                        <p>Surat Kontrol <i class="fas fa-angle-left right"></i></p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a id="nav-pendaftaran-sk" href="<?= base_url('pendaftaran/skontrol') ?>" class="nav-link">
                                <i class="fas fa-file-medical-alt nav-icon text-indigo"></i>
                                <p>Kunjungan Kontrol</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a id="nav-pendaftaran-spri" href="<?= base_url('pendaftaran/skontrol/spri') ?>" class="nav-link">
                                <i class="fas fa-file-medical-alt nav-icon text-maroon"></i>
                                <p>Kunjungan Inap</p>
                            </a>
                        </li>
                    </ul>
                </li>
                <li class="nav-item">
                    <a href="#" class="nav-link">
                        <i class="nav-icon fas fa-ambulance"></i>
                        <p>Rujukan<i class="fas fa-angle-left right"></i></p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a id="nav-form-rujukan" href="<?= base_url('rujuk/rujukan') ?>" class="nav-link">
                                <i class="fas fa-file-prescription nav-icon text-success"></i>
                                <p>Form Rujukan</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a id="nav-khusus-rujukan" href="<?= base_url('rujuk/rujukan/khusus') ?>" class="nav-link">
                                <i class="fas fa-file-prescription nav-icon text-fuchsia"></i>
                                <p>Rujukan Khusus</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a id="nav-khusus-rujukan" href="<?= base_url('rujuk/rujukbalik') ?>" class="nav-link">
                                <i class="fas fa-file-prescription nav-icon text-olive"></i>
                                <p>PRB</p>
                            </a>
                        </li>
                    </ul>
                </li>
                <li class="nav-item">
                    <a id="nav-m-pasien" href="<?= base_url('pasien') ?>" class="nav-link">
                        <i class="fas fa-hospital-user nav-icon"></i>
                        <p>Pasien</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a id="nav-m-pasien-bpjs" href="<?= base_url('pasien/bpjs') ?>" class="nav-link pl-4">
                        <small>
                            <i class="fas fa-id-card nav-icon"></i>
                            <p>Peserta BPJS</p>
                        </small>
                    </a>
                </li>
                <li class="nav-item">
                    <a id="nav-a-simrs" href="<?= base_url('antrean/manage/appointment') ?>" class="nav-link">
                        <i class="fas fa-address-book nav-icon"></i>
                        <p>Appointment</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a id="nav-wp-poli-manual" href="<?= base_url('antrean/pending') ?>" class="nav-link">
                        <i class="fas fa-folder-open nav-icon"></i>
                        <p>Antrean Pending</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a id="nav-config" href="<?= base_url('configuration') ?>" class="nav-link">
                        <i class="fas fa-cogs nav-icon"></i>
                        <p>Konfigurasi</p>
                    </a>
                </li>
            <?php
                break;
            case 'poli':
            ?>
                <li class="nav-item">
                    <a id="nav-wp-poli-manual" href="<?= base_url('pendaftaran/ralan/poliklinik_manual') ?>" class="nav-link">
                        <i class="fas fa-stethoscope nav-icon"></i>
                        <p>Poliklinik</p>
                    </a>
                </li>
<?php
                break;
            default:
                # code...
                break;
        }
        $nav = ob_get_contents();
        ob_clean();
        return $nav;
    }

    var $bulan = [
        1 => 'Januari',
        2 => 'Februari',
        3 => 'Maret',
        4 => 'April',
        5 => 'Mei',
        6 => 'Juni',
        7 => 'Juli',
        8 => 'Agustus',
        9 => 'September',
        10 => 'Oktober',
        11 => 'November',
        12 => 'Desember',
    ];

    function ftanggal($tanggal = '')
    {
        $t = strtotime($tanggal);
        return date('d', $t) . ' ' . $this->bulan[date('n', $t)] . ' ' . date('Y', $t);
    }

    public function cnull($string = '')
    {
        if ($string == '') {
            return null;
        }
        return $string;
    }

    public function date_to_millisecond($timestamp)
    {
        $date = strtotime($timestamp) * 1000;
        return $date;
    }

    public function tomillisecond($timestamp)
    {
        $date = new DateTime('@' . strtotime($timestamp), new DateTimeZone('Asia/Jakarta'));
        return strtotime($date->format('Y-m-d H:i:sP'));
    }

    public function waktu($waktu)
    {
        if (($waktu < 60)) {
            $lama = abs(number_format($waktu)) . " detik";
            return $lama;
        } else if (($waktu > 60) and ($waktu < 3600)) {
            $detik = fmod($waktu, 60);
            $menit = $waktu - $detik;
            $menit = $menit / 60;
            $lama = $menit . " Menit " . number_format($detik) . " detik";
            return $lama;
        } elseif ($waktu > 3600) {
            $detik = fmod($waktu, 60);
            $tempmenit = ($waktu - $detik) / 60;
            $menit = fmod($tempmenit, 60);
            $jam = ($tempmenit - $menit) / 60;
            $lama = $jam . " Jam " . $menit . " Menit " . number_format($detik) . " detik";
            return $lama;
        }
    }

    public function sel_combobox($table, $id, $name, $select = 0, $order = '')
    {
        if ($order == '') {
            $order = $id;
        }
        $data = $this->MCore->get_data($table, [], $order);
        $sel = '<div class="form-group p-0"><select class="form-control form-control-sm" name="' . $id . '[]"><option value="">--</option>';
        foreach ($data->result_array() as $value) {
            $selected = $select == $value[$id] ? 'selected=""' : '';
            $sel .= '<option ' . $selected . ' value="' . $value[$id] . '">' . $value[$name] . '</option>';
        }
        $sel .= '</select></div>';
        return $sel;
    }

    public function opt_combobox($table, $id, $name, $order = '', $select = '')
    {
        if ($order == '') {
            $order = $name;
        }
        $data = $this->MCore->get_data($table, [], $order);
        $sel = '<option value="">-Pilih-</option>';
        foreach ($data->result_array() as $value) {
            $selected = $select == $value[$id] ? 'selected=""' : '';
            $sel .= '<option ' . $selected . ' value="' . $value[$id] . '">' . $value[$name] . '</option>';
        }
        return $sel;
    }

    public function inp_checkbox($id, $check = 0, $label = 'Ya')
    {
        $checked = $check ? 'checked=""' : '';
        $cb = '<div class="form-check py-0"><label class="form-check-label">'
            . '<input class="form-check-input ' . $id . '" type="checkbox" value="1" ' . $checked . '>'
            . '<span class="form-check-sign">' . $label . '</span></label></div>';
        return $cb;
    }

    public function nf($string = '')
    {
        if (!is_numeric($string)) {
            return $string;
        }
        if (strpos($string, '.') !== false) {
            return number_format($string, 2, ',', '.');
        }
        return number_format($string, 0, ',', '.');
    }

    public function cnf($string = '')
    {
        if (is_numeric($string)) {
            return $string;
        }
        return str_replace(',', '', $string);
    }
}
