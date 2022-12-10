<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Mapping_slip extends RS_Controller
{

    public function index()
    {
        $header = $this->sendHeader('nav_mapping_slip', 'Mapping Slip Gaji');
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
            'opt_uk' => $this->opt_combobox('m_unit_kerja', 'uk_id', 'uk_nama'),
            'opt_golongan' => $this->opt_combobox('payroll_golongan', 'g_id', 'g_nama')
        ];
        $data = array_merge($header, $body);
        $this->template->view('VMapping_slip', $data);
    }

    public function cari()
    {
        $word = $this->input->get('searchTerm');
        $result = [];
        if ($word == '') {
            echo json_encode($result);
            exit();
        }
        $this->load->model('MPsdm');
        $data = $this->MPsdm->search_mapping_jabatan($word);
        foreach ($data->result_array() as $value) {
            $result[] = [
                'id' => $value['jm_id'],
                'text' => $value['jb_nama'] . ' - ' . $value['uk_nama'],
            ];
        }
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($result);
    }

    public function list_($tahun = 0)
    {
        $this->load->model('MPayroll');
        $data = $this->MPayroll->list_slip_gaji($tahun);
        $no = 1;
        ob_start();
        foreach ($data as $value) {
?>
            <tr>
                <td><?= $no ?></td>
                <td><?= $value['sl_nama'] ?></td>
                <td><?= $value['content'] ?></td>
                <td>
                    <button id="btn-edit" type="button" class="btn btn-sm btn-icon btn-round btn-primary" data-id="<?= $value['sl_id'] ?>" data-toggle="tooltip" data-placement="left" data-original-title="Edit">
                        <i class="fa fa-edit"></i>
                    </button>
                    <button id="btn-delete" type="button" class="btn btn-sm btn-icon btn-round btn-danger" data-id="<?= $value['sl_id'] ?>" data-toggle="tooltip" data-placement="bottom" data-original-title="Hapus">
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
        $this->load->model('MPayroll');
        $data = $this->MPayroll->edit_slip_gaji($id);
        echo json_encode($data);
    }

    public function save()
    {
        // die();
        $this->load->library('form_validation');
        $this->form_validation->set_rules('tanggal', 'Tahun', 'trim|required');
        $this->form_validation->set_rules('nama', 'Nama', 'trim');
        $this->form_validation->set_rules('jenis', 'Jenis', 'required');
        $this->form_validation->set_message('required', '{field} tidak boleh kosong.');
        if ($this->form_validation->run() == FALSE) {
            $errors = $this->form_validation->error_array();
            $arr['status'] = 0;
            $arr['message'] = reset($errors);
            echo json_encode($arr);
            exit();
        }
        // SELECT 'sl_id', 'sl_tahun', 'sl_nama', 'sl_uk_id', 'sl_us_id', 'sl_jenis' 
        // FROM 'payroll_lap_slip_gaji' WHERE 1
        $id = $this->input->post('id');
        $jenis = $this->input->post('jenis');
        $nama = $this->input->post('nama');

        $data = [
            'sl_tahun' => $this->input->post('tanggal'),
            'sl_nama' => $nama,
            'sl_uk_id' => $this->input->post('uk'),
            'sl_g_id' => NULL,
            'sl_jm_id' => NULL,
            'sl_us_id' => NULL,
            'sl_jenis' => $jenis,
            'sl_urut' => $this->input->post('urut')
        ];
        switch ($jenis) {
            case 0:
                $data['sl_jm_id'] = json_encode($this->input->post('idjm'));
                $data['sl_uk_id'] = 0;
                break;
            case 2:
                $data['sl_us_id'] = json_encode($this->input->post('idpeg'));
                $data['sl_uk_id'] = 0;
                break;
            case 3:
                $data['sl_g_id'] = $this->input->post('golongan');
                $data['sl_uk_id'] = 0;
                break;
            default:
                if ($nama == '') {
                    $uk = $this->MCore->get_data('m_unit_kerja', ['uk_id' => $this->input->post('uk')])->row_array();
                    $data['sl_nama'] = $uk['uk_nama'];
                }
                break;
        }
        if ($id == '') {
            $data['sl_id'] = $this->MCore->get_newid('payroll_lap_slip_gaji', 'sl_id');
            $sql = $this->MCore->save_data('payroll_lap_slip_gaji', $data);
        } else {
            $sql = $this->MCore->save_data('payroll_lap_slip_gaji', $data, true, ['sl_id' => $id]);
        }
        if (!$sql) {
            $arr['status'] = 0;
            $arr['message'] = 'Terjadi kesalahan saat menyimpan data, silahkan refresh halaman.';
        } else {
            $arr['status'] = 1;
            $arr['message'] = 'Data berhasil disimpan';
        }
        echo json_encode($arr);
    }

    public function hapus($id = 0)
    {
        $sql = $this->MCore->delete_data('payroll_lap_slip_gaji', ['sl_id' => $id]);
        if ($sql) {
            $arr['status'] = 1;
            $arr['message'] = 'Data berhasil dihapus';
        } else {
            $arr['status'] = 0;
            $arr['message'] = 'Data gagal dihapus';
        }
        echo json_encode($arr);
    }
}
