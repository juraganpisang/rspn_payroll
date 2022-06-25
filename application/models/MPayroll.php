<?php
defined('BASEPATH') or exit('No direct script access allowed');

class MPayroll extends CI_Model
{

    public function get_mapping_gaji($uk_id)
    {
        $q = $this->db->join('payroll_user_mapping', 'us_id = id_user', 'left')
            ->get_where('z_user', ['us_uk_id' => $uk_id, 'us_level' => 2]);
        return $q;
    }

    // $c_bulan = [
    //     1 => 3,
    // ]

    public function table_gaji($tahun = 0)
    {
        if ($tahun == 0) {
            $tahun = date('Y');
        }
        $data = $this->db->order_by('g_id')
            ->get('payroll_golongan');
        $arr_result = [];
        foreach ($data->result_array() as $value) {
            $arr_result[$value['g_id']] = [];
        }
        $detail = $this->db->get_where('payroll_golongan_detail', ['gd_tahun' => $tahun]);
        foreach ($detail->result_array() as $value) {
            $arr_result[$value['gd_golongan_id']][$value['gd_tahun_kerja']] = $value['gd_nominal'];
        }
        return $arr_result;
    }

    public function get_rekap_gaji($uk_id)
    {
        $get_setting = $this->db->get('payroll_setting');
        $setting = [];
        foreach ($get_setting->result_array() as $key => $value) {
            $setting[$value['set_kode']] = $value['set_value'];
        }
        $t_gaji = $this->table_gaji();
        $q = $this->db
            ->join('payroll_user_mapping', 'us_id = id_user', 'left')
            ->join('payroll_golongan', 'id_gol_gaji = g_id', 'left')
            ->join('payroll_ptkp', 'id_status = kp_id', 'left')
            ->join('payroll_tunjangan_jabatan', 'id_tunj_jabatan = tj_id', 'left')
            ->join('payroll_tunjangan_fungsi', 'id_tunj_fungsi = tf_id', 'left')
            ->get_where('z_user', ['us_uk_id' => $uk_id, 'us_level' => 2]);
        $arr_result = [];
        foreach ($q->result_array() as $value) {
            $temp = [
                'id_user' => $value['id_user'],
                'us_reg' => $value['us_reg'],
                'us_nama' => $value['us_nama'],
                'us_mulai_kerja' => $value['us_mulai_kerja'],
                'g_nama' => $value['g_nama'],
                'kp_nama' => $value['kp_kode'],
                'gaji_nominal' => '',
                'tj_nominal' => '',
                'tf_nominal' => '',
                't_nominal' => '',
                'rs_bpjs_jkk' => '',
                'rs_bpjs_pensiun' => '',
                'rs_bpjs_kesehatan' => '',
                'bpjs_jkk' => '',
                'bpjs_pensiun' => '',
                'bpjs_kesehatan' => '',
                'bruto' => '',
                'pajak_rp' => '',
                'potongan' => '',
                'netto' => ''
            ];
            if ($value['id_user'] != '') {
                $th_kerja = $this->tahun_kerja($value['us_mulai_kerja']);
                $i_th = $th_kerja == 0 ? 0 : $th_kerja - 1;
                $temp['gaji_nominal'] = ($value['id_gol_gaji'] == '') ? '' : $t_gaji[$value['id_gol_gaji']][$i_th];
                if ($th_kerja > 16) {
                    $temp['tj_nominal'] = $value['tj_lama'];
                    $temp['tf_nominal'] = $value['tf_lama'];
                } else {
                    $temp['tj_nominal'] = $value['tj_baru'];
                    $temp['tf_nominal'] = $value['tf_baru'];
                }
                $temp['t_nominal'] = 0;
                if ($value['is_transport']) {
                    $temp['t_nominal'] = $setting['is_transport'];
                }
                $grand_gaji = $temp['gaji_nominal'] + $temp['tj_nominal'] + $temp['tf_nominal'] + $temp['t_nominal'];
                $temp['rs_bpjs_jkk'] = round($grand_gaji * $setting['persen_rs_bpjs_jkk'] / 100);
                $temp['rs_bpjs_kesehatan'] = round($grand_gaji * $setting['persen_rs_bpjs_kesehatan'] / 100);
                $temp['rs_bpjs_pensiun'] = round($grand_gaji * $setting['persen_rs_bpjs_pensiun'] / 100);
                $grand_rs = $temp['rs_bpjs_jkk'] + $temp['rs_bpjs_kesehatan'] + $temp['rs_bpjs_pensiun'];
                $temp['bruto'] = $grand_gaji + $grand_rs;
                $temp['bpjs_jkk'] = round($grand_gaji * $setting['persen_bpjs_jkk'] / 100);
                $temp['bpjs_kesehatan'] = round($grand_gaji * $setting['persen_bpjs_kesehatan'] / 100);
                $temp['bpjs_pensiun'] = round($grand_gaji * $setting['persen_bpjs_pensiun'] / 100);
                $temp['pajak_rp'] = $value['pajak_rp'];
                $temp['potongan'] = $grand_rs + $temp['bpjs_jkk'] + $temp['bpjs_kesehatan'] + $temp['bpjs_pensiun'] + $value['pajak_rp'];
                $temp['netto'] = $temp['bruto'] - $temp['potongan'];
            }
            $arr_result[] = $temp;
        }
        return $arr_result;
    }

    private function tahun_kerja($mulai_kerja = '')
    {
        $start = new DateTime($mulai_kerja);
        $now = new DateTime(date('Y-m-d'));
        $interval = $start->diff($now);
        return $interval->y;
    }
}
