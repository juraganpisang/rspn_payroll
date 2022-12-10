<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Mapping_gaji extends RS_Controller
{

    public function index()
    {
        $header = $this->sendHeader('nav_mapping_gaji', 'Mapping Gaji', 'overlay-sidebar');
        $header['js'] = array(
            'maskmoney/jquery.maskMoney.min.js',
        );
        $setting = $this->MCore->get_setting();
        $body = [
            'opt_uk' => $this->opt_uk(31),
        ];
        $data = array_merge($header, $body);
        $this->template->view('VMapping_gaji', $data);
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

    public function list_($uk_id = 0)
    {
        $this->load->model('MPayroll');
        $data = $this->MPayroll->get_mapping_gaji($uk_id);
        ob_start();
        $no = 1;
        foreach ($data->result_array() as $value) {
            $catatan = $value['catatan'] != '' ? ' <i class="fas fa-exclamation-triangle text-danger float-right" data-toggle="tooltip" data-placement="right" title="'.$value['catatan'].'"></i>' : '';
?>
            <tr>
                <td class="text-center"><?= $no ?></td>
                <td>
                    <input type="hidden" name="id[]" value="<?= $value['id_user'] ?>">
                    <input type="hidden" name="id_user[]" value="<?= $value['us_id'] ?>">
                    <?= $value['us_nama'] . '<br><i class="fas fa-id-card-alt"></i> ' . $value['us_reg'] . $catatan  ?>
                </td>
                <td><?= date('d/m/Y', strtotime($value['us_mulai_kerja'])) . '<br>' . $this->masa_kerja($value['us_mulai_kerja']) ?></td>
                <td><?= $this->sel_combobox('payroll_golongan', 'g_id', 'g_nama', $value['id_gol_gaji'], 'g_nama') ?></td>
                <td><?= $this->sel_combobox('payroll_ptkp', 'kp_id', 'kp_kode', $value['id_status']) ?></td>
                <td><?= $this->sel_combobox('payroll_tunjangan_jabatan', 'tj_id', 'tj_nama', $value['id_tunj_jabatan']) ?></td>
                <td><?= $this->sel_combobox('payroll_tunjangan_fungsi', 'tf_id', 'tf_nama', $value['id_tunj_fungsi'], 'tf_urut') ?></td>
                <td><?= $this->sel_combobox('payroll_insentif', 'ti_id', 'ti_nama', $value['id_insentif'], 'ti_id') ?></td>
                <td><?= $this->inp_checkbox('is_transport', $value['is_transport']) ?></td>
                <td><?= $this->inp_checkbox('is_bpjs', $value['is_bpjs']) . $this->inp_checkbox('is_jamsostek', $value['is_jamsostek'], 'JMO') ?></td>
                <td><?= $this->inp_checkbox('is_kontrak', $value['is_kontrak']) ?></td>
                <td><input type="text" name="pajak_rp[]" class="form-control form-control-sm currency" value="<?= number_format($value['pajak_rp'], 0) ?>" autocomplete="off"></td>
            </tr>
<?php
            $no++;
        }
        $arr['tbody'] = ob_get_contents();
        ob_clean();
        echo json_encode($arr);
    }

    private function masa_kerja($mulai_kerja = '')
    {
        $start = new DateTime($mulai_kerja);
        $now = new DateTime(date('Y-m-d'));
        $interval = $start->diff($now);
        return $interval->y . " thn " . $interval->m . " bln";
    }

    public function save()
    {
        // $this->load->library('form_validation');
        // $this->form_validation->set_rules('nama', 'Nama Golongan', 'trim|required|max_length[50]');
        // $this->form_validation->set_rules('keterangan', 'Keterangan', 'trim|required');
        // $this->form_validation->set_rules('baru', 'Nominal Baru', 'trim|required');
        // $this->form_validation->set_rules('lama', 'Nominal Lama', 'trim|required');

        // $this->form_validation->set_message('required', '{field} tidak boleh kosong.');
        // $this->form_validation->set_message('matches', 'Password harus sama.');
        // $this->form_validation->set_message('max_length', '{field} melebihi {param} karakter.');
        // $this->form_validation->set_message('greater_than', '{field} harus lebih dari 0.');

        // if ($this->form_validation->run() == FALSE) {
        //     $errors = $this->form_validation->error_array();
        //     $arr['status'] = 0;
        //     $arr['message'] = reset($errors);
        //     echo json_encode($arr);
        //     exit();
        // }

        $id = $this->input->post('id');
        $id_user = $this->input->post('id_user');
        $g_id = $this->input->post('g_id');
        $kp_id = $this->input->post('kp_id');
        $tj_id = $this->input->post('tj_id');
        $tf_id = $this->input->post('tf_id');
        $ti_id = $this->input->post('ti_id');
        $is_transport = $this->input->post('is_transport');
        $is_bpjs = $this->input->post('is_bpjs');
        $is_jamsostek = $this->input->post('is_jamsostek');
        $is_kontrak = $this->input->post('is_kontrak');
        $pajak_rp = $this->input->post('pajak_rp');

        // SELECT 'id_user', 'id_gol_gaji', 'id_status', 'id_tunj_jabatan', 'id_tunj_fungsi', 
        // 'is_transport', 'is_bpjs_jamsostek', 'is_bpjs_pensiun', 'is_bpjs_kesehatan', 
        // 'penambahan', 'pengurangan', 'bulan', 'tahun', 'bruto' FROM 'payroll_user_mapping' WHERE 1
        foreach ($id as $key => $value) {
            if ($g_id[$key] == '') {
                continue;
            }
            $data = [
                'id_gol_gaji' => $g_id[$key],
                'id_status' => $this->c_null($kp_id[$key]),
                'id_tunj_jabatan' => $this->c_null($tj_id[$key]),
                'id_tunj_fungsi' => $this->c_null($tf_id[$key]),
                'id_insentif' => $this->c_null($ti_id[$key]),
                'is_transport' => $is_transport[$key],
                'is_bpjs' => $is_bpjs[$key],
                'is_jamsostek' => $is_jamsostek[$key],
                'is_kontrak' => $is_kontrak[$key],
                'pajak_rp' => str_replace(',', '', $pajak_rp[$key])
            ];
            if ($value == '') {
                $data['id_user'] = $id_user[$key];
                $sql = $this->MCore->save_data('payroll_user_mapping', $data);
            } else {
                $sql = $this->MCore->save_data('payroll_user_mapping', $data, true, ['id_user' => $value]);
            }
            if (!$sql) {
                $arr['status'] = 0;
                $arr['message'] = 'Data gagal disimpan';
                echo json_encode($arr);
                exit();
            }
        }
        $arr['status'] = 1;
        $arr['message'] = 'Data berhasil disimpan';
        echo json_encode($arr);
    }

    private function c_null($value = '')
    {
        if ($value == '') {
            return null;
        }
        return $value;
    }
}
