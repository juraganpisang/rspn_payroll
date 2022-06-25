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

    public function get_rekap_gaji($uk_id)
    {
        $get_setting = $this->db->get('payroll_setting');
        $setting = [];
        foreach ($get_setting->result_array() as $key => $value) {
            $setting[$value['set_kode']] = $value['set_value'];
        }

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
                'tj_nama' => $value['tj_nama'],
                'tf_nama' => $value['tf_nama'],
            ];
            $th_kerja = $this->tahun_kerja($value['us_mulai_kerja']);
            if ($th_kerja > 16) {
                $temp['tj_nominal'] = $value['tj_lama'];
                $temp['tf_nominal'] = $value['tf_lama'];
            } else {
                $temp['tj_nominal'] = $value['tj_baru'];
                $temp['tf_nominal'] = $value['tf_baru'];
            }
            $temp['t_nominal'] = '';
            if (abs($value['is_transport'])) {
                $temp['t_nominal'] = $setting['is_transport'];
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
