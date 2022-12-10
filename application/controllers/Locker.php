<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Locker extends RS_Controller
{

    public function index()
    {
        $header = $this->sendHeader('nav_locker', 'Locker');
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
        $body = [
            'opt_uk' => $this->opt_uk(),
        ];
        $data = array_merge($header, $body);
        $this->template->view('VLocker', $data);
    }

    private function opt_uk($select = [])
    {
        $data = $this->MCore->get_data('m_unit_kerja', [], 'uk_nama');
        $opt = '';
        foreach ($data->result_array() as $value) {
            $selected = in_array($value['uk_id'], $select) ? 'selected=""' : '';
            $opt .= '<option ' . $selected . ' value="' . $value['uk_id'] . '">' . $value['uk_nama'] . '</option>';
        }
        return $opt;
    }

    public function list_($tahun = '')
    {
        $this->load->model('MPayroll');
        $data = $this->MPayroll->get_locker($tahun);
        $no = 1;
        ob_start();
        foreach ($data as $value) {
?>
            <tr class="mb-2">
                <td><?= $no ?></td>
                <td><?= $value['bulan'] ?></td>
                <td><?= wordwrap($value['unit_kerja'], 40, '<br>') ?></td>
                <td>
                    <button id="btn-edit" type="button" class="btn btn-sm btn-icon btn-round btn-primary" data-id="<?= $value['lc_id'] ?>" data-toggle="tooltip" data-placement="left" data-original-title="Edit">
                        <i class="fa fa-edit"></i>
                    </button>
                    <button id="btn-delete" type="button" class="btn btn-sm btn-icon btn-round btn-danger" data-id="<?= $value['lc_id'] ?>" data-toggle="tooltip" data-placement="bottom" data-original-title="Hapus">
                        <i class="fa fa-trash"></i>
                    </button>
                </td>
            </tr>
<?php
            $no++;
        }
        $arr['tbody'] = ob_get_contents();
        ob_clean();
        echo json_encode($arr);
    }

    public function edit($id = 0)
    {
        $arr = $this->MCore->get_data('payroll_locker', ['lc_id' => $id])->row_array();
        $arr['tanggal'] = date('m/Y', strtotime($arr['tahun'] . '-' . $arr['bulan'] . '-01'));
        $arr_uk = [];
        if (strpos($arr['uk_id'], ';') !== false) {
            $arr_uk = explode(';', $arr['uk_id']);
        } else {
            $arr_uk[] = $arr['uk_id'];
        }
        $arr['opt_uk'] = $this->opt_uk($arr_uk);
        echo json_encode($arr);
    }

    public function save()
    {
        $this->load->library('form_validation');
        $this->form_validation->set_rules('id', 'Id', 'trim');
        $this->form_validation->set_rules('tanggal', 'Bulan', 'trim|required');
        // $this->form_validation->set_rules('uk_id', 'Unit Kerja', 'required');

        $this->form_validation->set_message('required', '{field} tidak boleh kosong.');
        if ($this->form_validation->run() == FALSE) {
            $errors = $this->form_validation->error_array();
            $arr['status'] = 0;
            $arr['message'] = reset($errors);
            echo json_encode($arr);
            exit();
        }
        $uk_id = $this->input->post('uk_id');
        if (count($uk_id) == 0) {
            $arr['status'] = 0;
            $arr['message'] = 'Unit kerja tidak boleh kosong.';
            echo json_encode($arr);
            exit();
        }
        $id = $this->input->post('id');
        $tanggal = explode('/', $this->input->post('tanggal'));
        $data = [
            'bulan' => $tanggal[0],
            'tahun' => $tanggal[1],
            'uk_id' => implode(';', $uk_id)
        ];
        if ($id == '') {
            $data['lc_id'] = $this->MCore->get_newid('payroll_locker', 'lc_id');
            $sql = $this->MCore->save_data('payroll_locker', $data);
        } else {
            $sql = $this->MCore->save_data('payroll_locker', $data, true, ['lc_id' => $id]);
        }
        if ($sql) {
            $arr['status'] = 1;
            $arr['message'] = 'Data berhasil disimpan';
        } else {
            $arr['status'] = 0;
            $arr['message'] = 'Data gagal disimpan';
        }
        echo json_encode($arr);
    }

    public function hapus($id = 0)
    {
        $data = $this->MCore->get_data('payroll_locker', ['lc_id' => $id])->row_array();
        $sql = $this->MCore->delete_data('payroll_locker', ['lc_id' => $id]);
        if ($sql) {
            $this->MCore->set_history($this->session->userdata('user_fullname'), 'locker', 'Hapus locker seluruh unit kerja periode ' . $data['tahun'] . '-' . $data['bulan']);
            $arr['status'] = 1;
            $arr['message'] = 'Data berhasil dihapus';
        } else {
            $arr['status'] = 0;
            $arr['message'] = 'Data gagal dihapus';
        }
        echo json_encode($arr);
    }

    public function update_status()
    {
        $this->load->library('form_validation');
        $this->form_validation->set_rules('uk_id', 'Unit', 'required');
        $this->form_validation->set_rules('tanggal', 'Bulan', 'trim|required');
        $this->form_validation->set_rules('status', 'Status', 'required');

        $this->form_validation->set_message('required', '{field} tidak boleh kosong.');
        if ($this->form_validation->run() == FALSE) {
            $errors = $this->form_validation->error_array();
            $arr['status'] = 0;
            $arr['message'] = reset($errors);
            echo json_encode($arr);
            exit();
        }
        $uk_id = $this->input->post('uk_id');
        $tanggal = explode('/', $this->input->post('tanggal'));
        $bulan = $tanggal[0];
        $tahun = $tanggal[1];
        $status = $this->input->post('status');
        $id = '';

        $this->load->model('MPayroll');
        $arr_uk = [];
        $c = $this->MPayroll->cek_locker($tahun, $bulan);
        if ($c['status']) {
            $arr_uk = $c['uk'];
            $id = $c['id'];
        }

        switch ($status) {
            case 1:
                $arr_uk[] = $uk_id;
                // $arr_uk = array_filter($arr_uk);
                $arr_uk = array_unique($arr_uk);
                $action = 'Mengunci';
                break;
            default:
                if (($key = array_search($uk_id, $arr_uk)) !== false) {
                    unset($arr_uk[$key]);
                }
                $action = 'Membuka kunci';
                break;
        }
        $data = [
            'bulan' => $bulan,
            'tahun' => $tahun,
            'uk_id' => implode(';', $arr_uk)
        ];
        if ($id == '') {
            $data['lc_id'] = $this->MCore->get_newid('payroll_locker', 'lc_id');
            $sql = $this->MCore->save_data('payroll_locker', $data);
        } else {
            $sql = $this->MCore->save_data('payroll_locker', $data, true, ['lc_id' => $id]);
        }
        if ($sql) {
            $this->MCore->set_history($this->session->userdata('user_fullname'), 'locker', $action . ' Gaji periode ' . $tahun . '-' . $bulan . ' pada ' . json_encode(['m_unit_kerja' => 'uk_id;' . $uk_id . ';uk_nama']));
            $arr['status'] = 1;
            $arr['message'] = $status ? 'Data berhasil dikunci' : 'Kunci data berhasil dibuka';
        } else {
            $arr['status'] = 0;
            $arr['message'] = $status ? 'Data gagal dikunci' : 'Kunci data gagal dibuka';
        }
        echo json_encode($arr);
    }

    public function lock_all($bulan = '', $tahun = '')
    {
        $id = '';
        $this->load->model('MPayroll');
        $c = $this->MPayroll->cek_locker($tahun, $bulan);
        if ($c['status']) {
            $id = $c['id'];
        }
        $uk = $this->MCore->get_data('m_unit_kerja', [], 'uk_nama');
        $arr_uk = [];
        foreach ($uk->result_array() as $key => $value) {
            $arr_uk[] = $value['uk_id'];
        }
        $data = [
            'bulan' => $bulan,
            'tahun' => $tahun,
            'uk_id' => implode(';', $arr_uk)
        ];
        if ($id == '') {
            $data['lc_id'] = $this->MCore->get_newid('payroll_locker', 'lc_id');
            $sql = $this->MCore->save_data('payroll_locker', $data);
        } else {
            $sql = $this->MCore->save_data('payroll_locker', $data, true, ['lc_id' => $id]);
        }
        if ($sql) {
            $this->MCore->set_history($this->session->userdata('user_fullname'), 'locker', 'Mengunci seluruh unit kerja periode ' . $tahun . '-' . $bulan);
            $arr['status'] = 1;
        } else {
            $arr['status'] = 0;
            $arr['message'] = 'Locker gagal diupdate';
        }
        echo json_encode($arr);
    }
}
