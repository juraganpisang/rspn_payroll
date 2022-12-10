<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Punishment extends RS_Controller
{

    public function index()
    {
        $header = $this->sendHeader('nav_punishment', 'Punishment');
        $header['css'] = array(
            'datepicker/css/tempusdominus-bootstrap-4.min.css',
            'select2/css/select2.min.css',
        );
        $header['js'] = array(
            'moment/moment.min.js',
            'datepicker/js/tempusdominus-bootstrap-4.min.js',
            'select2/js/select2.full.min.js',
        );
        $body = [
            'tbody' => $this->list_()
        ];
        $data = array_merge($header, $body);
        $this->template->view('VPunishment', $data);
    }

    public function cari()
    {
        $word = $this->input->get('searchTerm');
        $result = [];
        if ($word == '') {
            echo json_encode($result);
            exit();
        }
        $data = $this->MCore->search_data('z_user', 'us_nama', $word, 'us_nama');
        foreach ($data->result_array() as $value) {
            $result[] = [
                'id' => $value['us_id'],
                'text' => $value['us_nama'],
                'tanggal' => date('m', strtotime($value['us_mulai_kerja'])) . '/' . date('Y'),
                'mulai_kerja' => $value['us_mulai_kerja'],
            ];
        }
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($result);
    }

    public function edit($id = 0)
    {
        $this->load->model('MPayroll');
        $arr = $this->MPayroll->get_punishment(['pn_id' => $id])->row_array();
        $arr['tanggal'] = date('m/Y', strtotime($arr['tanggal_mulai']));
        echo json_encode($arr);
    }

    private function list_()
    {
        $this->load->model('MPayroll');
        $data = $this->MPayroll->get_punishment();
        $no = 1;
        ob_start();
        foreach ($data->result_array() as $value) {
            $is_temp = ($value['tipe']) ? '' : 'text-primary';
?>
            <tr class="mb-2 <?= $is_temp ?>">
                <td><?= $no ?></td>
                <td><?= $value['us_nama'] ?></td>
                <td><?= $value['durasi'] . ' Bulan<br>' . date('m/Y', strtotime($value['tanggal_mulai'])) . '-' . date('m/Y', strtotime($value['tanggal_akhir'])) ?></td>
                <td><?= $value['keterangan'] ?></td>
                <td>
                    <button id="btn-edit" type="button" class="btn btn-sm btn-icon btn-round btn-primary <?= ($value['tipe']) ? '' : 'd-none' ?>" data-id="<?= $value['pn_id'] ?>" data-toggle="tooltip" data-placement="left" data-original-title="Edit">
                        <i class="fa fa-edit"></i>
                    </button>
                    <button id="btn-delete" type="button" class="btn btn-sm btn-icon btn-round btn-danger" data-id="<?= $value['pn_id'] ?>" data-toggle="tooltip" data-placement="bottom" data-original-title="Hapus">
                        <i class="fa fa-trash"></i>
                    </button>
                </td>
            </tr>
<?php
            $no++;
        }
        $tbody = ob_get_contents();
        ob_clean();
        return $tbody;
    }

    public function save()
    {
        // die();
        $this->load->library('form_validation');
        $this->form_validation->set_rules('id_peg', 'Pegawai', 'trim|required');
        $this->form_validation->set_rules('tanggal', 'Tanggal Mulai', 'trim|required');
        $this->form_validation->set_rules('durasi', 'Durasi Hukuman', 'trim|required');
        $this->form_validation->set_rules('keterangan', 'Keterangan', 'trim');

        $this->form_validation->set_message('required', '{field} tidak boleh kosong.');
        if ($this->form_validation->run() == FALSE) {
            $errors = $this->form_validation->error_array();
            $arr['status'] = 0;
            $arr['message'] = reset($errors);
            echo json_encode($arr);
            exit();
        }
        $nama = $this->input->post('nama');
        $tanggal = strtotime(str_replace('/', '-', '01/' . $this->input->post('tanggal')));
        $data = [
            'id_user' => $this->input->post('id_peg'),
            'tanggal_mulai' => date('Y-m-d', $tanggal),
            'tanggal_akhir' => date('Y-m-d', strtotime(date('Y-m-d', $tanggal) . ' +' . $this->input->post('durasi') . ' months')),
            'durasi' => $this->input->post('durasi'),
            'keterangan' => $this->input->post('keterangan')
        ];
        $action = 'Tambah';
        if ($this->input->post('id') == '') {
            $data['pn_id'] = $this->MCore->get_newid('payroll_punishment', 'pn_id');
            $sql = $this->MCore->save_data('payroll_punishment', $data);
        } else {
            $sql = $this->MCore->save_data('payroll_punishment', $data, true, ['pn_id' => $this->input->post('id')]);
            $action = 'Edit';
        }
        if ($sql) {
            $this->MCore->set_history($this->session->userdata('user_fullname'), 'punishment', $action . ' ' . $nama . ' tanggal ' . $data['tanggal_mulai'] . ' s/d ' . $data['tanggal_akhir'] . ' (' . $data['keterangan'] . ')');
            $arr['status'] = 1;
            $arr['message'] = 'Data berhasil disimpan';
            $arr['tbody'] = $this->list_();
        } else {
            $arr['status'] = 0;
            $arr['message'] = 'Data gagal disimpan';
        }
        echo json_encode($arr);
    }

    public function hapus($id = 0)
    {
        $data = $this->MCore->get_data('payroll_punishment', ['pn_id' => $id])->row_array();
        $sql = $this->MCore->delete_data('payroll_punishment', ['pn_id' => $id]);
        if ($sql) {
            $this->MCore->set_history($this->session->userdata('user_fullname'), 'punishment', 'Hapus data ID:' . $id . ' ' . json_encode(['z_user' => 'us_id;' . $data['id_user'] . ';us_nama']));
            $arr['status'] = 1;
            $arr['message'] = 'Data berhasil dihapus';
            $arr['tbody'] = $this->list_();
        } else {
            $arr['status'] = 0;
            $arr['message'] = 'Data gagal dihapus';
        }
        echo json_encode($arr);
    }
}
