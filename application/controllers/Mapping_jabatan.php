<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Mapping_jabatan extends RS_Controller
{

    public function index()
    {
        $header = $this->sendHeader('nav_jabatan', 'Mapping Jabatan');
        $body = [
            'opt_uk' => $this->opt_uk(),
            'opt_jabatan' => $this->opt_jabatan()
        ];
        $data = array_merge($header, $body);
        $this->template->view('psdm/VJabatan', $data);
    }

    private function opt_uk($select = '')
    {
        $data = $this->MCore->get_data('m_unit_kerja', [], 'uk_nama');
        $opt = '<option value="">-Pilih Unit Kerja-</option>';
        foreach ($data->result_array() as $value) {
            $selected = $value['uk_id'] == $select ? 'selected=""' : '';
            $opt .= '<option ' . $selected . ' value="' . $value['uk_id'] . '">' . $value['uk_nama'] . '</option>';
        }
        return $opt;
    }

    private function opt_jabatan($select = '')
    {
        $data = $this->MCore->get_data('m_jabatan', [], 'jb_nama');
        $opt = '<option value="">-Pilih Jabatan-</option>';
        foreach ($data->result_array() as $value) {
            $selected = $value['jb_id'] == $select ? 'selected=""' : '';
            $opt .= '<option ' . $selected . ' value="' . $value['jb_id'] . '">' . $value['jb_nama'] . '</option>';
        }
        return $opt;
    }

    public function list_($uk_id = 0)
    {
        $this->load->model('MPsdm');
        $data = $this->MPsdm->get_mapping_jabatan($uk_id);
        $no = 1;
        ob_start();
        foreach ($data->result_array() as $value) {
?>
            <tr>
                <td class="text-center"><?= $no ?></td>
                <td><?= $value['jb_nama'] ?></td>
                <td class="text-center">
                    <button id="btn-edit" type="button" class="btn btn-sm btn-icon btn-round btn-primary" data-id="<?= $value['jm_id'] ?>" data-toggle="tooltip" data-placement="left" data-original-title="Edit">
                        <i class="fa fa-edit"></i>
                    </button>
                    <button id="btn-delete" type="button" class="btn btn-sm btn-icon btn-round btn-danger" data-id="<?= $value['jm_id'] ?>" data-toggle="tooltip" data-placement="bottom" data-original-title="Hapus">
                        <i class="fa fa-times"></i>
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

    public function save()
    {
        $this->load->library('form_validation');
        $this->form_validation->set_rules('parent', 'Induk jabatan', 'required');
        $this->form_validation->set_rules('nama', 'Nama', 'trim|required');
        $this->form_validation->set_rules('kode', 'Kode', 'trim');
        $this->form_validation->set_rules('urut', 'Urut', 'trim');
        $this->form_validation->set_message('required', '{field} tidak boleh kosong.');
        if ($this->form_validation->run() == FALSE) {
            $errors = $this->form_validation->error_array();
            $arr['status'] = 0;
            $arr['message'] = reset($errors);
            echo json_encode($arr);
            exit();
        }
        $id = $this->input->post('id');
        $data = [
            'org_kode' => $this->input->post('kode'),
            'org_nama' => $this->input->post('nama'),
            'org_urut' => $this->input->post('urut'),
            'is_direksi' => $this->input->post('is_direksi') == 'on' ? 1 : 0,
            'org_parent' => $this->input->post('parent')
        ];

        if ($id == '') {
            $id = $this->MCore->get_newid('m_jabatan_mapping', 'jm_id');
            $data['jm_id'] = $id;
            $sql = $this->MCore->save_data('m_jabatan_mapping', $data);
        } else {
            $sql = $this->MCore->save_data('m_jabatan_mapping', $data, true, ['jm_id' => $id]);
        }
        if (!$sql) {
            $arr['status'] = 0;
            $arr['message'] = 'Terjadi kesalahan saat menyimpan data jabatan, silahkan refresh halaman.';
        } else {
            $arr['status'] = 1;
            $arr['message'] = 'Jabatan berhasil disimpan';
        }
        echo json_encode($arr);
    }

    public function edit($id = 0)
    {
        $data = $this->MCore->get_data('m_jabatan_mapping', ['jm_id' => $id])->row_array();
        echo json_encode($data);
    }

    public function delete($id = 0)
    {
        $sql = $this->MCore->delete_data('m_jabatan_mapping', ['jm_id' => $id]);
        if (!$sql) {
            $arr['status'] = 0;
            $arr['message'] = 'Terjadi kesalahan saat menghapus data, silahkan refresh halaman.';
        } else {
            $arr['status'] = 1;
            $arr['message'] = 'Data berhasil dihapus';
        }
        echo json_encode($arr);
    }

    public function script_mapping()
    {
        $peg = $this->db->select('us_uk_id, us_jabatan_id')
            ->group_by('us_uk_id, us_jabatan_id')
            ->get_where('z_user', ['us_level' => 2, 'us_status' => 1]);
        $arrjab = [];
        // SELECT `jm_id`, `jm_jb_id`, `jm_uk_id` FROM `m_jabatan_mapping` WHERE 1
        $id = $this->MCore->get_newid('m_jabatan_mapping', 'jm_id');
        foreach ($peg->result_array() as $key => $value) {
            $arrjab[] = [
                'jm_id' => $id,
                'jm_uk_id' => $value['us_uk_id'],
                'jm_jb_id' => $value['us_jabatan_id']
            ];
            $id++;
        }
        // $this->db->insert_batch('m_jabatan_mapping', $arrjab);
        echo '<pre>';
        print_r($arrjab);
    }
}
