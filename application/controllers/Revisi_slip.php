<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Revisi_slip extends RS_Controller
{

    public function index()
    {
        $header = $this->sendHeader('nav_revisi_slip', 'Revisi Slip Gaji');
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
        $setting = $this->MCore->get_setting();
        $body = [
            's' => $setting,
        ];
        $data = array_merge($header, $body);
        $this->template->view('VRevisi_slip', $data);
    }

    public function cari()
    {
        $word = $this->input->get('searchTerm');
        $tanggal = $this->input->get('tanggal');
        $result = [];
        if ($word == '' || $tanggal == '') {
            echo json_encode($result);
            exit();
        }
        $tanggal = explode('/', $tanggal);
        $data = $this->MCore->search_data('payroll_fixed', 'us_nama', $word, 'us_nama', ['bulan' => $tanggal[0], 'tahun' => $tanggal[1]]);
        foreach ($data->result_array() as $value) {
            $temp = $value;
            $temp['id'] = $value['id'];
            $temp['text'] = $value['us_nama'];
            $result[] = $temp;
        }
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($result);
    }

    public function save()
    {
        // die();
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
        $data = [
            'gaji_nominal' => $this->cnf($this->input->post('gaji_nominal')),
            'tj_nominal' => $this->cnf($this->input->post('tj_nominal')),
            'tf_nominal' => $this->cnf($this->input->post('tf_nominal')),
            'ti_nominal' => $this->cnf($this->input->post('ti_nominal')),
            't_nominal' => $this->cnf($this->input->post('t_nominal')),
            'rs_bpjs_jkk' => $this->cnf($this->input->post('rs_bpjs_jkk')),
            'rs_bpjs_pensiun' => $this->cnf($this->input->post('rs_bpjs_pensiun')),
            'rs_bpjs_kesehatan' => $this->cnf($this->input->post('rs_bpjs_kesehatan')),
            'bpjs_jkk' => $this->cnf($this->input->post('bpjs_jkk')),
            'bpjs_pensiun' => $this->cnf($this->input->post('bpjs_pensiun')),
            'bpjs_kesehatan' => $this->cnf($this->input->post('bpjs_kesehatan')),
            // 'bruto' => $this->input->post(''),
            'pajak_rp' => $this->cnf($this->input->post('pajak_rp')),
            // 'potongan' => $this->input->post(''),
            // 'netto' => $this->input->post('')
        ];
        // print_r($data);
        // die;
        $data['bruto'] = $data['gaji_nominal'] + $data['tj_nominal'] + $data['tf_nominal'] + $data['t_nominal'] + $data['rs_bpjs_jkk'] + $data['rs_bpjs_pensiun'] + $data['rs_bpjs_kesehatan'] + $data['ti_nominal'];
        $data['potongan'] = $data['rs_bpjs_jkk'] + $data['rs_bpjs_pensiun'] + $data['rs_bpjs_kesehatan'] + $data['bpjs_jkk'] + $data['bpjs_kesehatan'] + $data['bpjs_pensiun'] + $data['pajak_rp'];
        $data['netto'] = $data['bruto'] - $data['potongan'];
        $sql = $this->MCore->save_data('payroll_fixed', $data, true, ['id' => $id]);
        if (!$sql) {
            $arr['status'] = 0;
            $arr['message'] = 'Terjadi kesalahan saat menyimpan data, silahkan refresh halaman.';
        } else {
            $arr_id = explode(';', $id);
            $this->MCore->set_history($this->session->userdata('user_fullname'), 'revisi_slip', 'Update ' . $nama . ' periode ' . $arr_id[0] . ' netto menjadi Rp.' . $this->nf($data['netto']));
            $arr['status'] = 1;
            $arr['message'] = 'Data berhasil diupdate';
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
