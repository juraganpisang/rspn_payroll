<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Gaji_generate extends RS_Controller
{

    public function index()
    {
        $header = $this->sendHeader('nav_gaji', 'Gaji Pegawai');
        $header['css'] = array(
            'datepicker/css/tempusdominus-bootstrap-4.min.css',
            'select2/css/select2.min.css',
        );
        $header['js'] = array(
            'moment/moment.min.js',
            'datepicker/js/tempusdominus-bootstrap-4.min.js',
            'select2/js/select2.full.min.js',
            'maskmoney/jquery.maskMoney.min.js'
        );
        $additional = $this->MCore->get_data('payroll_additional_gaji', ['p_record_status' => 'A'], 'p_urut')->result_array();
        $body = [
            'opt_uk' => $this->opt_combobox('m_unit_kerja', 'uk_id', 'uk_nama'),
            'adt' => $additional
        ];
        $data = array_merge($header, $body);
        $this->template->view('VGaji_generate', $data);
    }

    public function list_($bulan = '', $tahun = '')
    {
        if ($tahun == '') {
            $tahun = date('Y');
        }
        if ($bulan == '') {
            $bulan = date('m');
        }
        $this->load->model('MPayroll');
        $data = $this->MPayroll->get_rekap_gaji_unit($tahun, $bulan);
        ob_start();
        $total = $total_n = $total_t = 0;
        $no = 1;
        foreach ($data as $value) {
?>
            <tr>
                <td class="text-center"><?= $no ?></td>
                <td><?= $value['uk_nama'] ?></td>
                <td class="text-center"><?= $value['tk'] ?></td>
                <td class="text-right"><?= $this->nf($value['tnetto']) ?></td>
                <td class="text-center"><?= $value['status'] ? '<button id="btn-lock" type="button" class="btn btn-sm btn-icon btn-round btn-danger" status="0" data-id="' . $value['uk_id'] . '" data-toggle="tooltip" data-placement="left" data-original-title="Update Status"><i class="fa fa-lock"></i></button>&nbsp;<span id="text">Terkunci</span>' : '<button id="btn-lock" type="button" class="btn btn-sm btn-icon btn-round btn-success" status="1" data-id="' . $value['uk_id'] . '" data-toggle="tooltip" data-placement="left" data-original-title="Update Status"><i class="fa fa-unlock"></i></button>&nbsp;<span id="text">Terbuka</span>' ?></td>
                <td class="text-center">
                    <button id="btn-additional-gaji" type="button" class="btn btn-sm btn-secondary" data-id="<?= $value['uk_id'] ?>" data-name="<?= $value['uk_nama'] ?>">
                        <i class="fas fa-balance-scale"></i> +/-
                    </button>
                </td>
                <td class="text-right <?= $value['tnetto'] != $value['diterima'] ? 'text-danger' : '' ?>"><?= $this->nf($value['diterima']) ?></td>
                <td class="text-center">
                    <button id="btn-refresh" type="button" class="btn btn-sm btn-icon btn-round btn-success" data-id="<?= $value['uk_id'] ?>" data-toggle="tooltip" data-placement="left" data-original-title="Refresh">
                        <i class="fas fa-redo-alt"></i>
                    </button>
                    <button id="btn-delete" type="button" class="btn btn-sm btn-icon btn-round btn-danger" data-id="<?= $value['uk_id'] ?>" data-toggle="tooltip" data-placement="bottom" data-original-title="Hapus">
                        <i class="fa fa-trash"></i>
                    </button>
                </td>
            </tr>
        <?php
            $total += $value['tk'];
            $total_n += $value['tnetto'];
            $total_t += $value['diterima'];
            $no++;
        }
        $arr['tbody'] = ob_get_contents();
        ob_clean();
        ?>
        <tr>
            <th colspan="2" class="text-center">Total Karyawan</th>
            <th class="text-center"><?= $total ?></th>
            <th class="text-right"><?= $this->nf($total_n) ?></th>
            <th colspan="2" class="text-center"></th>
            <th class="text-right"><?= $this->nf($total_t) ?></th>
            <th class="text-center"></th>
        </tr>
    <?php
        $arr['tfoot'] = ob_get_contents();
        ob_clean();
        echo json_encode($arr);
    }

    public function set_gaji($uk_id, $bulan, $tahun)
    {
        $this->load->model('MPayroll');
        if ($this->MPayroll->is_lock($tahun, $bulan, $uk_id)) {
            $arr['status'] = 2;
            $arr['message'] = 'Status terkunci, Proses set gaji tidak dapat dilakukan';
            echo json_encode($arr);
            exit();
        }
        $arr['status'] = 1;
        $sql = $this->MPayroll->set_rekap_gaji($uk_id, $bulan, $tahun);
        if (!$sql) {
            $arr['status'] = 0;
            $arr['message'] = 'Terjadi kesalahan saat menyimpan rekap gaji';
            echo json_encode($arr);
            exit();
        }
        $tr = $this->tr_content($uk_id, $bulan, $tahun);
        $arr = array_merge($arr, $tr);
        $this->MCore->set_history($this->session->userdata('user_fullname'), 'gaji_generate', 'Set Gaji periode ' . $tahun . '-' . $bulan . ' pada ' . json_encode(['m_unit_kerja' => 'uk_id;' . $uk_id . ';uk_nama']));
        echo json_encode($arr);
    }

    private function tr_content($uk_id, $bulan, $tahun)
    {
        $data = $this->MCore->get_data('payroll_fixed', ['unit_kerja' => $uk_id, 'bulan' => $bulan, 'tahun' => $tahun], 'g_id DESC, us_nama');
        $arr['tk'] = $data->num_rows();
        $total_n = $total_t = 0;
        foreach ($data->result_array() as $key => $value) {
            $total_n += $value['netto'];
            $total_t += is_null($value['terima_nominal']) ? $value['netto'] : $value['terima_nominal'];
        }
        $arr['tnetto'] = $this->nf($total_n);
        $arr['terima_nominal'] = $this->nf($total_t);
        return $arr;
    }

    public function hapus($uk_id, $bulan, $tahun)
    {
        $sql = $this->MCore->delete_data('payroll_fixed', ['unit_kerja' => $uk_id, 'bulan' => $bulan, 'tahun' => $tahun]);
        if ($sql) {
            $this->MCore->set_history($this->session->userdata('user_fullname'), 'gaji_generate', 'Hapus Gaji periode ' . $tahun . '-' . $bulan . ' pada ' . json_encode(['m_unit_kerja' => 'uk_id;' . $uk_id . ';uk_nama']));
            $arr['status'] = 1;
            $arr['message'] = 'Data berhasil dihapus';
        } else {
            $arr['status'] = 0;
            $arr['message'] = 'Data gagal dihapus';
        }
        echo json_encode($arr);
    }

    public function cari()
    {
        $word = $this->input->get('searchTerm');
        $uk = $this->input->get('uk');
        $tanggal = $this->input->get('b') . '/' . $this->input->get('t');
        $result = [];
        if ($word == '' || $tanggal == '') {
            echo json_encode($result);
            exit();
        }
        $tanggal = explode('/', $tanggal);
        $arrWhere = ['bulan' => $tanggal[0], 'tahun' => $tanggal[1]];
        if ($uk != '') {
            $arrWhere['unit_kerja'] = $uk;
        }
        $data = $this->MCore->search_data('payroll_fixed', 'us_nama', $word, 'us_nama', $arrWhere);
        foreach ($data->result_array() as $value) {
            $temp['id'] = $value['id'];
            $temp['text'] = $value['us_nama'];
            $temp['netto'] = $value['netto'];
            $temp['terima_json'] = is_null($value['terima_json_additional']) ? [] : json_decode($value['terima_json_additional'], true);
            $temp['terima_nominal'] = $value['terima_nominal'];
            $result[] = $temp;
        }
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($result);
    }

    public function modal_additional_gaji($uk_id = 0, $bulan = '', $tahun = '')
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
                    <form id="form-modal">
                        <div class="modal-header">
                            <h4 class="modal-title">Penambahan & Potongan Gaji yang diterima</h4>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">×</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <input type="hidden" id="uk" value="<?= $uk_id ?>">
                            <input type="hidden" name="tahun" id="tahun" value="<?= $tahun ?>">
                            <input type="hidden" name="bulan" id="bulan" value="<?= $bulan ?>">
                            <div class="form-group py-0 pl-3">
                                <div class="row">
                                    <div class="col-md-11 p-0">
                                        <select id="search"></select>
                                    </div>
                                    <div class="col-md-1 text-center pl-0 pr-0 pt-1">
                                        <i class="fa fa-2x fa-search"></i>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group px-0">
                                <input type="hidden" name="id" id="id" class="form-control" value="">
                                <input id="nama" name="nama" type="text" class="form-control" autocomplete="off" placeholder="Nama Pegawai" readonly>
                            </div>
                            <div class="form-group px-0">
                                <label><i class="fas fa-layer-group"></i>&nbsp;Gaji</label>
                                <div class="input-group col-md-6 pl-0">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">Rp</span>
                                    </div>
                                    <input type="text" id="netto" name="netto" value="" class="form-control currency" readonly>
                                </div>
                            </div>
                            <div class="row form-group px-0">
                                <?php
                                $adt = $this->MCore->get_data('payroll_additional_gaji', ['p_record_status' => 'A'], 'p_urut');
                                foreach ($adt->result_array() as $key => $value) {
                                    // $cls = ($value['p_jenis'] == '+') ? 'has-success' : 'has-error';
                                ?>
                                    <div class="col-md-6">
                                        <label><?= $value['p_nama'] ?></label>
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text">Rp</span>
                                            </div>
                                            <input type="hidden" id="jns-<?= $value['p_id'] ?>" name="jns[<?= $value['p_id'] ?>]" value="<?= $value['p_jenis'] ?>">
                                            <input type="text" id="p-<?= $value['p_id'] ?>" name="p[<?= $value['p_id'] ?>]" value="" class="form-control currency additional">
                                        </div>
                                    </div>
                                <?php
                                }
                                ?>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button data-dismiss="modal" class="btn btn-success float-left" type="button"><i class="fa fa-check"></i> Selesai</button>
                            <button id="btn-reset" class="btn btn-default" type="button">Batal</button>
                            <button type="submit" id="btn-save" class="btn btn-primary"><i class="fa fa-save"></i> Simpan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    <?php
        $arr['modal'] = ob_get_contents();
        ob_clean();
        $arr['status'] = 1;
        echo json_encode($arr);
    }

    public function reload_tr($uk, $bulan, $tahun)
    {
        $arr = $this->tr_content($uk, $bulan, $tahun);
        echo json_encode($arr);
    }

    public function save_additional_gaji()
    {
        $this->load->library('form_validation');
        $this->form_validation->set_rules('id', 'Nama Pegawai', 'trim|required');
        $this->form_validation->set_rules('nama', 'Nama Pegawai', 'trim|required');
        $this->form_validation->set_message('required', '{field} tidak boleh kosong.');
        if ($this->form_validation->run() == FALSE) {
            $errors = $this->form_validation->error_array();
            $arr['status'] = 0;
            $arr['message'] = reset($errors);
            echo json_encode($arr);
            exit();
        }
        $id = $this->input->post('id');
        $nama = $this->input->post('nama');
        $p = $this->input->post('p');
        $jns = $this->input->post('jns');
        $netto = $this->cnf($this->input->post('netto'));

        $additional = [];
        foreach ($p as $key => $val) {
            if ($val == '' || $val == 0) {
                continue;
            }
            $nominal = $this->cnf($val);
            $additional[$key] = $nominal;
            if ($jns[$key] == '-') {
                $nominal = -1 * $nominal;
            }
            $netto += $nominal;
        }
        if (count($additional) > 0) {
            $data = [
                'terima_json_additional' => json_encode($additional),
                'terima_nominal' => $netto,
            ];
        } else {
            $data = [
                'terima_json_additional' => NULL,
                'terima_nominal' => NULL,
            ];
        }
        $sql = $this->MCore->save_data('payroll_fixed', $data, true, ['id' => $id]);
        if (!$sql) {
            $arr['status'] = 0;
            $arr['message'] = 'Terjadi kesalahan saat menyimpan data, silahkan refresh halaman.';
        } else {
            $this->MCore->set_history($this->session->userdata('user_fullname'), 'gaji_generate', 'Update Penambahan & Potongan Gaji ' . $nama . ' periode ' . $this->input->post('tahun') . '-' . $this->input->post('bulan') . ' yg diterima menjadi Rp.' . $this->nf($netto));
            $arr['status'] = 1;
            $arr['message'] = 'Data berhasil diupdate';
        }
        echo json_encode($arr);
    }

    public function modal_additional_gaji_kolektif($p_id = 0, $bulan = '', $tahun = '')
    {
        $data = $this->MCore->get_data('payroll_additional_gaji', ['p_id' => $p_id])->row_array();
        ob_start();
    ?>
        <div id="modal" class="modal fade">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <form id="form-modal">
                        <div class="modal-header">
                            <h4 class="modal-title"><?= $data['p_nama'] ?></h4>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">×</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <input type="hidden" name="id" id="id" class="form-control" value="<?= $p_id ?>">
                            <input type="hidden" name="satuan" id="satuan" class="form-control" value="<?= $data['nilai_tipe'] ? 'Rp' : $data['nilai_satuan'] ?>">
                            <input type="hidden" name="tahun" id="tahun" value="<?= $tahun ?>">
                            <input type="hidden" name="bulan" id="bulan" value="<?= $bulan ?>">
                            <div class="row form-group">
                                <div class="input-group col-md-4 pl-0">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><?= $data['nilai_tipe'] ? 'Rp' : $data['nilai_satuan'] ?></span>
                                    </div>
                                    <input type="text" id="nilai" name="nilai" class="form-control currency" value="<?= number_format($data['nilai'], 0) ?>" <?= $data['nilai_tipe'] ? '' : 'readonly' ?>>
                                </div>
                                <div class="col-md-8 text-right">
                                    <button type="button" id="kolektif-kontrak" class="btn btn-primary btn-border btn-round">Peg. Kontrak</button>
                                </div>
                            </div>
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
                            <table id="table-manual" class="display table table-striped">
                                <thead>
                                    <tr>
                                        <th style="width: 3%;">No</th>
                                        <th style="width: 50%;">Nama Pegawai</th>
                                        <th class="text-right" style="width: 20%;">Bruto</th>
                                        <th class="text-right" style="width: 20%;">Potongan</th>
                                        <th style="width: 7%;">#</th>
                                    </tr>
                                </thead>
                                <tbody></tbody>
                            </table>
                        </div>
                        <div class="modal-footer">
                            <button data-dismiss="modal" class="btn btn-default" type="button">Batal</button>
                            <button type="submit" id="btn-save" class="btn btn-primary"><i class="fa fa-save"></i> Simpan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <?php
        $arr['modal'] = ob_get_contents();
        ob_clean();
        $arr['status'] = 1;
        echo json_encode($arr);
    }

    public function set_table($jenis = '')
    {
        $nilai = $this->cnf($this->input->post('nilai'));
        $satuan = $this->input->post('satuan');
        $this->load->model('MPayroll');
        $data = $this->MPayroll->get_table_additional($this->input->post('t'), $this->input->post('b'), $jenis);
        ob_start();
        foreach ($data->result_array() as $key => $value) {
            $id = str_replace(';', '-', $value['id']);
            $potongan = $nilai;
            if ($satuan == '%') {
                $potongan = $value['netto'] * $nilai / 100;
            }
        ?>
            <tr id="<?= $id ?>">
                <td class="text-center">#</td>
                <td><input type="hidden" name="idpeg[]" value="<?= $value['id'] ?>"><input type="hidden" id="netto" value="<?= $value['netto'] ?>"><?= $value['us_nama'] ?></td>
                <td class="text-right"><?= number_format($value['netto'], 0) ?></td>
                <td id="td-result" class="text-right"><?= number_format($potongan, 0) ?></td>
                <td class="text-center"><button id="btn-remove" type="button" class="btn btn-xs btn-danger"><i class="fa fa-trash"></i></button></td>
            </tr>
<?php
        }
        $arr['tbody'] = ob_get_contents();
        ob_clean();
        echo json_encode($arr);
    }

    public function save_additional_gaji_kolektif()
    {
        $this->load->library('form_validation');
        $this->form_validation->set_rules('id', 'Nama', 'trim|required');
        $this->form_validation->set_message('required', '{field} tidak boleh kosong.');
        if ($this->form_validation->run() == FALSE) {
            $errors = $this->form_validation->error_array();
            $arr['status'] = 0;
            $arr['message'] = reset($errors);
            echo json_encode($arr);
            exit();
        }
        $idpeg = $this->input->post('idpeg');

        $id = $this->input->post('id');
        $tahun = $this->input->post('tahun');
        $bulan = $this->input->post('bulan');
        $nilai = $this->cnf($this->input->post('nilai'));
        $satuan = $this->input->post('satuan');

        $adt = $this->MCore->get_data('payroll_additional_gaji', [], 'p_urut')->result_array();
        $arradt = [];
        foreach ($adt as $key => $value) {
            $arradt[$value['p_id']] = $value['p_jenis'];
        }

        $data = $this->MCore->get_data_in('payroll_fixed', 'id', $idpeg);
        foreach ($data->result_array() as $key => $value) {
            // cari data lama
            $additional = [];
            if (!is_null($value['terima_json_additional'])) {
                $additional = json_decode($value['terima_json_additional'], true);
            }
            // hitung nominal potongan dan update array
            $potongan = $nilai;
            if ($satuan == '%') {
                $potongan = round($value['netto'] * $nilai / 100);
            }
            $additional[$id] = $potongan;
            // hitung ulang potongan keseluruhan
            $netto = $value['netto'];
            foreach ($additional as $k => $va) {
                $nominal = $va;
                // cari berdasarkan jenis additional +/-
                if ($arradt[$k] == '-') {
                    $nominal = -1 * $nominal;
                }
                $netto += $nominal;
            }
            // update db
            $update = [
                'terima_json_additional' => json_encode($additional),
                'terima_nominal' => $netto,
            ];
            $sql = $this->MCore->save_data('payroll_fixed', $update, true, ['id' => $value['id']]);
            if (!$sql) {
                $arr['status'] = 0;
                $arr['message'] = 'Terjadi kesalahan saat menyimpan data, silahkan refresh halaman.';
                echo json_encode($arr);
                exit();
            }
        }
        $this->MCore->set_history($this->session->userdata('user_fullname'), 'gaji_generate', 'Update Penambahan & Potongan Gaji ' . count($idpeg) . ' Pegawai periode ' . $tahun . '-' . $bulan);
        $arr['status'] = 1;
        $arr['message'] = 'Data berhasil diupdate';
        echo json_encode($arr);
    }
}
