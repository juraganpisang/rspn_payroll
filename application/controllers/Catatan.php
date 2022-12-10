<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Catatan extends RS_Controller
{

    public function index()
    {
        $header = $this->sendHeader('nav_catatan', 'Catatan');
        $header['css'] = array(
            'select2/css/select2.min.css',
            'summernote/summernote-bs4.min.css'
        );
        $header['js'] = array(
            'select2/js/select2.full.min.js',
            'summernote/summernote-bs4.min.js'
        );
        $body = [
            'tbody' => $this->list_()
        ];
        $data = array_merge($header, $body);
        $this->template->view('VCatatan', $data);
    }

    public function cari()
    {
        $word = $this->input->get('searchTerm');
        $result = [];
        if ($word == '') {
            echo json_encode($result);
            exit();
        }
        $this->load->model('MPayroll');
        $data = $this->MPayroll->get_mapping_pegawai(['us_nama LIKE' => "%$word%"]);
        foreach ($data->result_array() as $value) {
            $result[] = [
                'id' => $value['id_user'],
                'text' => $value['us_nama'],
            ];
        }
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($result);
    }

    public function edit($id = 0)
    {
        $this->load->model('MPayroll');
        $arr = $this->MPayroll->get_mapping_pegawai(['id_user' => $id])->row_array();
        echo json_encode($arr);
    }

    private function list_()
    {
        $this->load->model('MPayroll');
        $data = $this->MPayroll->get_mapping_pegawai(['catatan <>' => '']);
        $no = 1;
        ob_start();
        foreach ($data->result_array() as $value) {
?>
            <tr class="mb-2">
                <td><?= $no ?></td>
                <td><?= $value['us_nama'] ?></td>
                <td><?= $value['catatan'] ?></td>
                <td>
                    <button id="btn-edit" type="button" class="btn btn-sm btn-icon btn-round btn-primary" data-id="<?= $value['id_user'] ?>" data-toggle="tooltip" data-placement="left" data-original-title="Edit">
                        <i class="fa fa-edit"></i>
                    </button>
                    <button id="btn-delete" type="button" class="btn btn-sm btn-icon btn-round btn-danger" data-id="<?= $value['id_user'] ?>" data-toggle="tooltip" data-placement="bottom" data-original-title="Hapus">
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
        $this->form_validation->set_rules('id_peg', 'Nama Pegawai', 'trim|required');
        $this->form_validation->set_rules('keterangan', 'Keterangan', 'trim');

        $this->form_validation->set_message('required', '{field} tidak boleh kosong.');
        if ($this->form_validation->run() == FALSE) {
            $errors = $this->form_validation->error_array();
            $arr['status'] = 0;
            $arr['message'] = reset($errors);
            echo json_encode($arr);
            exit();
        }
        $id = $this->input->post('id_peg');
        $nama = $this->input->post('nama');
        $data = [
            'catatan' => $this->input->post('keterangan')
        ];
        $sql = $this->MCore->save_data('payroll_user_mapping', $data, true, ['id_user' => $id]);
        if ($sql) {
            $this->MCore->set_history($this->session->userdata('user_fullname'), 'catatan', 'Tambah catatan untuk ' . $nama);
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
        $sql = $this->MCore->save_data('payroll_user_mapping', ['catatan' => NULL], true, ['id_user' => $id]);
        if ($sql) {
            $this->MCore->set_history($this->session->userdata('user_fullname'), 'catatan', 'Hapus catatan untuk ' . json_encode(['z_user' => 'us_id;' . $id . ';us_nama']));
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
