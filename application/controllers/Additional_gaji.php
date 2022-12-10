<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Additional_gaji extends RS_Controller
{

    public function index()
    {
        $header = $this->sendHeader('nav_additional_gaji', 'Penambahan & Potongan Gaji yang diterima');
        $header['js'] = ['maskmoney/jquery.maskMoney.min.js'];
        $body = [
            'tbody' => $this->list_()
        ];
        $data = array_merge($header, $body);
        $this->template->view('VAdditional_gaji', $data);
    }

    public function list_()
    {
        $u_level = $this->session->userdata('user_level');
        $data = $this->MCore->get_data('payroll_additional_gaji', ['p_record_status' => 'A'], 'p_urut');
        $no = 1;
        ob_start();
        foreach ($data->result_array() as $value) {
            $kode = $value['p_kode'] == '' ? '' : '<b>[' . $value['p_kode'] . ']</b> ';
            $detail = $value['nilai_tipe'] == 0 ? ' ' . $value['nilai'] . ' (' . $value['nilai_satuan'] . ')' : '';
?>
            <tr>
                <td class="text-center"><?= $no ?></td>
                <td><?= $value['p_jenis'] == '-' ? 'Potongan' : 'Tambahan' ?></td>
                <td><?= $kode . $value['p_nama'] ?></td>
                <td><?= $detail ?></td>
                <td class="text-center">
                    <button id="btn-edit" type="button" class="btn btn-sm btn-icon btn-round btn-primary" data-id="<?= $value['p_id'] ?>" data-toggle="tooltip" data-placement="bottom" data-original-title="Edit">
                        <i class="fa fa-edit"></i>
                    </button>
                    <button id="btn-disable" type="button" class="btn btn-sm btn-icon btn-round btn-warning" data-id="<?= $value['p_id'] ?>" data-toggle="tooltip" data-placement="bottom" data-original-title="Nonaktifkan">
                        <i class="fa fa-times"></i>
                    </button>
                    <?php if ($u_level == 1) { ?>
                        <button id="btn-delete" type="button" class="btn btn-sm btn-icon btn-round btn-danger" data-id="<?= $value['p_id'] ?>" data-toggle="tooltip" data-placement="bottom" data-original-title="Hapus">
                            <i class="fa fa-trash"></i>
                        </button>
                    <?php } ?>
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
        $this->form_validation->set_rules('nama', 'Nama', 'trim|required');
        $this->form_validation->set_rules('jenis', 'Jenis', 'required');
        $this->form_validation->set_rules('urut', 'Urut', 'numeric|required');
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
            'p_kode' => $this->input->post('kode') == '' ? NULL : $this->input->post('kode'),
            'p_nama' => $this->input->post('nama'),
            'p_jenis' => $this->input->post('jenis'),
            'nilai_tipe' => $this->input->post('tipe'),
            'nilai' => $this->input->post('tipe') == 1 ? null : $this->cnf($this->input->post('nilai')),
            'nilai_satuan' => $this->input->post('tipe') == 1 ? null : $this->input->post('satuan'),
            'p_urut' => $this->input->post('urut')
        ];

        if ($id == '') {
            $id = $this->MCore->get_newid('payroll_additional_gaji', 'p_id');
            $data['p_id'] = $id;
            $sql = $this->MCore->save_data('payroll_additional_gaji', $data);
        } else {
            $sql = $this->MCore->save_data('payroll_additional_gaji', $data, true, ['p_id' => $id]);
        }
        if (!$sql) {
            $arr['status'] = 0;
            $arr['message'] = 'Terjadi kesalahan saat menyimpan data, silahkan refresh halaman.';
        } else {
            $arr['status'] = 1;
            $arr['message'] = 'Data berhasil disimpan';
            $arr['tbody'] = $this->list_();
        }
        echo json_encode($arr);
    }

    public function edit($id = 0)
    {
        $data = $this->MCore->get_data('payroll_additional_gaji', ['p_id' => $id])->row_array();
        echo json_encode($data);
    }

    public function disable($id = 0)
    {
        $sql = $this->MCore->save_data('payroll_additional_gaji', ['p_record_status' => 'D'], true, ['p_id' => $id]);
        if (!$sql) {
            $arr['status'] = 0;
            $arr['message'] = 'Terjadi kesalahan saat menghapus data, silahkan refresh halaman.';
        } else {
            $arr['status'] = 1;
            $arr['message'] = 'Data berhasil dihapus';
            $arr['tbody'] = $this->list_();
        }
        echo json_encode($arr);
    }

    public function delete($id = 0)
    {
        $sql = $this->MCore->delete_data('payroll_additional_gaji', ['p_id' => $id]);
        if (!$sql) {
            $arr['status'] = 0;
            $arr['message'] = 'Terjadi kesalahan saat menghapus data, silahkan refresh halaman.';
        } else {
            $arr['status'] = 1;
            $arr['message'] = 'Data berhasil dihapus';
            $arr['tbody'] = $this->list_();
        }
        echo json_encode($arr);
    }
}
