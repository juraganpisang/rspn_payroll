<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Pengangkatan extends RS_Controller
{

    public function index()
    {
        $header = $this->sendHeader('nav_pengangkatan', 'Pengangkatan Pegawai');
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
        $this->template->view('VPengangkatan', $data);
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
        $data = $this->MPayroll->get_pengangkatan();
        $no = 1;
        ob_start();
        foreach ($data->result_array() as $value) {
?>
            <tr class="mb-2">
                <td><?= $no ?></td>
                <td><?= $value['us_nama'] ?></td>
                <td><?= 'Nomor: ' . $value['nomor_sk'] . '<br>' . date('d/m/Y', strtotime($value['tanggal_sk'])) ?></td>
                <td>
                    <?= $value['durasi'] . ' Bulan' ?>
                    <a id="btn-delete-punishment" class="text-danger" data-id="<?= $value['pn_id'] ?>" data-toggle="tooltip" data-placement="bottom" data-original-title="Hapus Punishment" style="cursor: pointer;">
                        <i class="fa fa-trash-alt"></i>
                    </a>
                </td>
                <td>
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
        $this->form_validation->set_rules('id_peg', 'Pegawai', 'trim|required');
        $this->form_validation->set_rules('tanggal', 'Tanggal SK', 'trim|required');
        $this->form_validation->set_rules('nomor', 'Nomor SK', 'trim|required');

        $this->form_validation->set_message('required', '{field} tidak boleh kosong.');
        if ($this->form_validation->run() == FALSE) {
            $errors = $this->form_validation->error_array();
            $arr['status'] = 0;
            $arr['message'] = reset($errors);
            echo json_encode($arr);
            exit();
        }
        $nama = $this->input->post('nama');
        // SELECT 'id_user', 'tanggal_sk', 'nomor_sk', 'ref_pn_id' FROM 'payroll_pengangkatan' WHERE 1
        $tanggal = strtotime(str_replace('/', '-', '01/' . $this->input->post('tanggal')));
        $tanggal_mk = strtotime($this->input->post('mulai_kerja'));
        $data = [
            'id_user' => $this->input->post('id_peg'),
            'tanggal_sk' => date('Y-m-d', $tanggal),
            'nomor_sk' => $this->input->post('nomor'),
        ];
        $this->load->model('MPayroll');
        $bulan_lalu = strtotime(date('Y-m', strtotime(date('Y-m-d', $tanggal) . ' -1 days')) . '-01');
        // $bulan_lalu = strtotime(date('Y-m-d', $tanggal) . ' -1 days');
        $bln_kerja = floor($this->MPayroll->selisih_bulan(date('Y-m-d', $tanggal_mk), date('Y-m-d', $bulan_lalu)) / 12) * 12;
        $punishment = [
            'pn_id' => $this->MCore->get_newid('payroll_punishment', 'pn_id'),
            'id_user' => $this->input->post('id_peg'),
            'tanggal_mulai' => date('Y-m-d', strtotime(date('Y-m-d', $tanggal) . ' -' . $bln_kerja . ' months')),
            'tanggal_akhir' => date('Y-m-d', $bulan_lalu),
            'durasi' => $bln_kerja,
            'tipe' => 0,
            'keterangan' => 'Pengangkatan Pegawai Tetap'
        ];
        $data['ref_pn_id'] = $punishment['pn_id'];
        // echo $bln_kerja;
        // echo '<pre>';
        // print_r($data);
        // print_r($punishment);
        // die();
        $action = 'Tambah';
        // if ($this->input->post('id') == '') {
        $sql = $this->MCore->save_data('payroll_pengangkatan', $data);
        // } else {
        //     $sql = $this->MCore->save_data('payroll_punishment', $data, true, ['pn_id' => $this->input->post('id')]);
        //     $action = 'Edit';
        // }
        if ($sql) {
            $this->MCore->save_data('payroll_user_mapping', ['is_kontrak' => 0], true, ['id_user' => $data['id_user']]);
            $this->MCore->save_data('payroll_punishment', $punishment);
            $this->MCore->set_history($this->session->userdata('user_fullname'), 'pengangkatan', 'Pengangkatan ' . $nama . ' dan punishment tanggal ' . $punishment['tanggal_mulai'] . ' s/d ' . $punishment['tanggal_akhir'] . ' (' . $punishment['keterangan'] . ')');
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
        $sql = $this->MCore->delete_data('payroll_pengangkatan', ['id_user' => $id]);
        if ($sql) {
            $this->MCore->delete_data('payroll_punishment', ['pn_id' => $data['ref_pn_id']]);
            $this->MCore->save_data('payroll_user_mapping', ['is_kontrak' => 1], true, ['id_user' => $id]);
            $this->MCore->set_history($this->session->userdata('user_fullname'), 'pengangkatan', 'Hapus data ID:' . $id . ' ' . json_encode(['z_user' => 'us_id;' . $id . ';us_nama']));
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
