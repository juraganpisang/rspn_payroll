<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Organisasi extends RS_Controller
{

    public function index()
    {
        $header = $this->sendHeader('nav_organisasi', 'Struktur Organisasi');
        $body = [
            'opt_parent' => $this->opt_parent(),
            'tbody' => $this->list_(),
        ];
        $data = array_merge($header, $body);
        $this->template->view('psdm/VOrganisasi', $data);
    }

    private function opt_parent($selected = '')
    {
        $opt = '<option value="0">-Tidak ada induk-</option>';

        $data = $this->MCore->get_data('m_struktur_organisasi', [], 'org_id');
        foreach ($data->result_array() as $value) {
            $sel = ($selected == $value['org_id']) ? 'selected=""' : '';
            $opt .= '<option ' . $sel . ' value="' . $value['org_id'] . '" data-kode="' . $value['org_kode'] . '">' . $value['org_nama'] . '</option>';
        }
        return $opt;
    }

    public function list_()
    {
        $this->load->model('MPsdm');
        $data = $this->MPsdm->get_struktur_organisasi();
        $no = 1;
        ob_start();
        foreach ($data as $value) {
?>
            <tr>
                <td class="text-center"><?= $no ?></td>
                <td><?= $value['parent_name'] ?></td>
                <td><?= $value['org_kode'] ?></td>
                <td><?= $value['org_nama'] ?></td>
                <td class="text-center"><?= ($value['is_direksi']) ? 'Ya' : 'Tidak' ?></td>
                <td class="text-center">
                    <button id="btn-edit" type="button" class="btn btn-sm btn-icon btn-round btn-primary" data-id="<?= $value['org_id'] ?>" data-toggle="tooltip" data-placement="bottom" data-original-title="Edit">
                        <i class="fa fa-edit"></i>
                    </button>
                    <button id="btn-delete" type="button" class="btn btn-sm btn-icon btn-round btn-danger" data-id="<?= $value['org_id'] ?>" data-toggle="tooltip" data-placement="bottom" data-original-title="Hapus">
                        <i class="fa fa-times"></i>
                    </button>
                </td>
            </tr>
<?php
            $no++;
        }
        $tr = ob_get_contents();
        ob_clean();
        return $tr;
    }

    public function save()
    {
        $this->load->library('form_validation');
        $this->form_validation->set_rules('parent', 'Induk Organisasi', 'required');
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
            $id = $this->MCore->get_newid('m_struktur_organisasi', 'org_id');
            $data['org_id'] = $id;
            $sql = $this->MCore->save_data('m_struktur_organisasi', $data);
        } else {
            $sql = $this->MCore->save_data('m_struktur_organisasi', $data, true, ['org_id' => $id]);
        }
        if (!$sql) {
            $arr['status'] = 0;
            $arr['message'] = 'Terjadi kesalahan saat menyimpan data struktur organisasi, silahkan refresh halaman.';
        } else {
            $arr['status'] = 1;
            $arr['message'] = 'Struktur Organisasi berhasil disimpan';
            $arr['tbody'] = $this->list_();
        }
        echo json_encode($arr);
    }

    public function edit($id = 0)
    {
        $data = $this->MCore->get_data('m_struktur_organisasi', ['org_id' => $id])->row_array();
        echo json_encode($data);
    }

    public function delete($id = 0)
    {
        $cek = $this->MCore->get_data('m_struktur_organisasi', ['org_parent' => $id]);
        if ($cek->num_rows() > 0) {
            $arr['status'] = 0;
            $arr['message'] = 'Struktur Organisasi tersebut tidak dapat dihapus, karena memiliki bawahan!';
            echo json_encode($arr);
            exit();
        }
        // $sql = $this->MCore->delete_data('m_struktur_organisasi', ['org_id' => $id]);
        $sql = $this->MCore->save_data('m_struktur_organisasi', ['org_record_status' => 'D'], true, ['org_id' => $id]);
        if (!$sql) {
            $arr['status'] = 0;
            $arr['message'] = 'Terjadi kesalahan saat menghapus data struktur organisasi, silahkan refresh halaman.';
        } else {
            $arr['status'] = 1;
            $arr['message'] = 'Struktur Organisasi berhasil dihapus';
            $arr['tbody'] = $this->list_();
        }
        echo json_encode($arr);
    }
}
