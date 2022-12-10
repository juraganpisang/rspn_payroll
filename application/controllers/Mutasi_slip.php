<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Mutasi_slip extends RS_Controller
{

    public function index()
    {
        $header = $this->sendHeader('nav_mutasi_slip', 'Pindah Slip Gaji');
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
            'opt_uk' => $this->opt_combobox('m_unit_kerja', 'uk_id', 'uk_nama')
        ];
        $data = array_merge($header, $body);
        $this->template->view('VMutasi_slip', $data);
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
            $result[] = [
                'id' => $value['id'],
                'text' => $value['us_nama'],
                'uk_id' => $value['unit_kerja']
            ];
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
        $this->form_validation->set_rules('uk', 'Unit Kerja', 'required');
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
            'unit_kerja' => $this->input->post('uk')
        ];
        $sql = $this->MCore->save_data('payroll_fixed', $data, true, ['id' => $id]);
        if (!$sql) {
            $arr['status'] = 0;
            $arr['message'] = 'Terjadi kesalahan saat menyimpan data, silahkan refresh halaman.';
        } else {
            $arr_id = explode(';', $id);
            $this->MCore->set_history($this->session->userdata('user_fullname'), 'mutasi_slip', 'Update ' . $nama . ' periode ' . $arr_id[0] . ' ke ' . json_encode(['m_unit_kerja' => 'uk_id;' . $this->input->post('uk').';uk_nama']));
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
