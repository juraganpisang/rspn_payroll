<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Gaji extends RS_Controller
{

    public function index()
    {
        $header = $this->sendHeader('nav_gaji', 'Gaji Karyawan', 'overlay-sidebar');
        $header['css'] = array(
            'jquery.stickytable/jquery.stickytable.css',
            'datepicker/css/tempusdominus-bootstrap-4.min.css',
            'select2/css/select2.min.css',
        );
        $header['js'] = array(
            'jquery.stickytable/jquery.stickytable.js',
            'moment/moment.min.js',
            'datepicker/js/tempusdominus-bootstrap-4.min.js',
            'select2/js/select2.full.min.js',
        );
        $setting = $this->MCore->get_setting();
        $body = [
            's' => $setting,
            'opt_uk' => $this->opt_combobox('m_unit_kerja', 'uk_id', 'uk_nama'),
        ];
        $data = array_merge($header, $body);
        $this->template->view('VGaji', $data);
    }

    public function list_($uk_id = 0, $bulan = '', $tahun = '')
    {
        if ($tahun == '') {
            $tahun = date('Y');
        }
        if ($bulan == '') {
            $bulan = date('m');
        }
        $this->load->model('MPayroll');
        $is_lock = $this->MPayroll->is_lock($tahun, $bulan, $uk_id);
        if ($is_lock) {
            $data = $this->MCore->get_data('payroll_fixed', ['unit_kerja' => $uk_id, 'bulan' => $bulan, 'tahun' => $tahun], 'g_id DESC, us_nama')->result_array();
        } else {
            $this->MPayroll->set_rekap_gaji($uk_id, $bulan, $tahun);
            $data = $this->MCore->get_data('payroll_fixed', ['unit_kerja' => $uk_id, 'bulan' => $bulan, 'tahun' => $tahun], 'g_id DESC, us_nama')->result_array();
        }
        ob_start();
        if (count($data) > 0) {
            $no = 1;
            foreach ($data as $value) {
                $punisment_desc = ($value['punishment'] > 0) ? '<br><span class="text-danger"><i class="fa fa-gavel"></i> -' . $value['punishment'] . ' Bulan</span>' : '';
?>
                <tr>
                    <td><?= $no ?></td>
                    <td class="sticky-cell">
                        <?= $value['us_nama'] . '<br><i class="fas fa-id-card-alt"></i> ' . $value['us_reg']  ?>
                    </td>
                    <td><?= date('d/m/Y', strtotime($value['us_mulai_kerja'])) . '<br>Tahun ke-' . $value['tahun_kerja'] . '<br><i class="fa fa-clock"></i> ' . $value['lama_kerja_asli'] . $punisment_desc ?></td>
                    <td><?= $value['g_nama'] ?></td>
                    <td><?= $value['kp_nama'] ?></td>
                    <td><?= rupiah($value['gaji_nominal']) ?></td>
                    <td><?= rupiah($value['tj_nominal']) ?></td>
                    <td><?= rupiah($value['tf_nominal']) ?></td>
                    <td><?= rupiah($value['t_nominal']) ?></td>
                    <td><?= rupiah($value['rs_bpjs_jkk']) ?></td>
                    <td><?= rupiah($value['rs_bpjs_pensiun']) ?></td>
                    <td><?= rupiah($value['rs_bpjs_kesehatan']) ?></td>
                    <td><?= rupiah($value['ti_nominal']) ?></td>
                    <td><?= rupiah($value['bruto']) ?></td>
                    <td><?= rupiah($value['bpjs_jkk']) ?></td>
                    <td><?= rupiah($value['rs_bpjs_jkk']) ?></td>
                    <td><?= rupiah($value['bpjs_pensiun']) ?></td>
                    <td><?= rupiah($value['rs_bpjs_pensiun']) ?></td>
                    <td><?= rupiah($value['pajak_rp']) ?></td>
                    <td><?= rupiah($value['bpjs_kesehatan']) ?></td>
                    <td><?= rupiah($value['rs_bpjs_kesehatan']) ?></td>
                    <td><?= rupiah($value['potongan']) ?></td>
                    <td><?= rupiah($value['netto']) ?></td>
                </tr>
            <?php
                $no++;
            }
        } else {
            ?>

        <?php
        }
        $arr['tbody'] = ob_get_contents();
        ob_clean();
        $arr['icon'] = $is_lock ? 'fa fa-lock' : 'fa fa-unlock';
        $arr['bg'] = $is_lock ? 'btn btn-danger' : 'btn btn-success';
        $arr['status'] = $is_lock ? 0 : 1;
        $arr['text'] = $is_lock ? 'Terkunci' : 'Terbuka';
        echo json_encode($arr);
    }

    public function modal_additional($uk_id = 0, $bulan = '', $tahun = '')
    {
        $this->load->model('MPayroll');
        $is_lock = $this->MPayroll->is_lock($tahun, $bulan, $uk_id);
        if (!$is_lock) {
            $arr['status'] = 0;
            $arr['message'] = 'Gaji pada unit tersebut belum terkunci!!';
            echo json_encode($arr);
            exit();
        }
        ob_start();
        ?>
        <div id="modal" class="modal fade">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">+/- Gaji</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form id="form-modal">
                            <input type="hidden" name="uk" id="uk" value="<?= $uk_id ?>">
                            <input type="hidden" name="tahun" id="tahun" value="<?= $tahun ?>">
                            <input type="hidden" name="bulan" id="bulan" value="<?= $bulan ?>">
                            <div class="form-group">
                                <div class="row">
                                    <div class="col-md-11 p-0">
                                        <select id="search"></select>
                                    </div>
                                    <div class="col-md-1 text-center pl-0 pr-0 pt-1">
                                        <i class="fa fa-2x fa-search"></i>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group col-md-12 px-0">
                                <label>Nama Pegawai</label>
                                <input type="hidden" name="id" id="id" class="form-control" value="">
                                <input id="nama" name="nama" type="text" class="form-control" autocomplete="off" readonly>
                            </div>
                            <div class="row">
                                <?php
                                $adt = $this->MCore->get_data('payroll_additional', ['p_record_status' => 'A'], 'p_urut');
                                foreach ($adt->result_array() as $key => $value) {
                                    // $cls = ($value['p_jenis'] == '+') ? 'has-success' : 'has-error';
                                ?>
                                    <div class="form-group col-md-4">
                                        <label style="color: #000 !important;"><?= $value['p_nama'] ?></label>
                                        <div class="input-group input-group-sm">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text">Rp.</span>
                                            </div>
                                            <input type="text" id="p-<?= $value['p_id'] ?>" name="p[<?= $value['p_id'] ?>]" value="" class="form-control">
                                        </div>
                                    </div>
                                <?php
                                }
                                ?>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button data-dismiss="modal" class="btn btn-default btn-sm" type="button">Tutup</button>
                    </div>
                </div>
            </div>
        </div>
<?php
        $arr['modal'] = ob_get_contents();
        ob_clean();
        $arr['status'] = 1;
        echo json_encode($arr);
    }

    public function cari()
    {
        $word = $this->input->get('searchTerm');
        $uk = $this->input->get('uk');
        $bulan = $this->input->get('b');
        $tahun = $this->input->get('t');
        $result = [];
        if ($word == '') {
            echo json_encode($result);
            exit();
        }
        $this->load->model('MPayroll');
        $data = $this->MPayroll->search_gaji($uk, $tahun, $bulan, $word);
        foreach ($data->result_array() as $value) {
            $result[] = [
                'id' => $value['id'],
                'text' => $value['us_nama'],
            ];
        }
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($result);
    }
}
