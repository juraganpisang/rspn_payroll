<?php
defined('BASEPATH') or exit('No direct script access allowed');

class MDashboard extends CI_Model
{

    public function get_kontrak($bulan, $tahun)
    {
        $t_filter = strtotime($tahun . '-' . $bulan . '-01');
        $tanggal_akhir = date('Y-m-d', $t_filter);
        $kontrak = $this->db->join('payroll_user_mapping', 'us_id = id_user')
            ->join('m_unit_kerja', 'us_uk_id = uk_id')
            ->join('m_jabatan', 'us_jabatan_id = jb_id')
            ->where('is_kontrak', 1)
            ->order_by('us_nama')
            ->get('z_user');
        $arr['kontrak'] = $arr['kontrak_expired'] = [];
        foreach ($kontrak->result_array() as $key => $value) {
            $temp = $value;
            $temp['masa_kerja'] = $this->masa_kerja($value['us_mulai_kerja'], $tanggal_akhir);
            $arr['kontrak'][] = $temp;
            if ($temp['masa_kerja']['m'] > 10) {
                $arr['kontrak_expired'][] = $temp;
            }
        }
        return $arr;
    }

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

    public function get_perubahankomponen($bulan, $tahun)
    {
        $t_filter = strtotime($tahun . '-' . $bulan . '-01');
        $tanggal_akhir = date('Y-m-d', $t_filter);

        // Pattern Gaji
        $t_gaji = $this->table_gaji($tahun);
        // Cari pegawai yg mengalami kenaikan berkala, golongan menyesuaikan
        $range_b = $this->range_bulan($bulan);
        $arr['kgb'] = [];
        $data = $this->db->join('payroll_user_mapping', 'us_id = id_user')
            ->join('m_unit_kerja', 'us_uk_id = uk_id')
            ->join('m_jabatan', 'us_jabatan_id = jb_id')
            ->join('payroll_golongan', 'id_gol_gaji = g_id')
            ->where("month(us_mulai_kerja) BETWEEN '" . $range_b['ts'] . "' AND '" . $range_b['te'] . "'")
            ->where('is_kontrak', 0)
            ->where('g_id <>', 0)
            ->order_by('id_gol_gaji DESC, us_nama')
            ->get('z_user');
        // echo $this->db->last_query();
        // die();
        $arr_idpeg = [];
        foreach ($data->result_array() as $value) {
            if (is_null($value['id_user'])) {
                continue;
            }
            $arr_idpeg[] = $value['id_user'];
        }
        // Pengurangan masa kerja
        $arr_pn = [];
        if (count($arr_idpeg) > 0) {
            $punishment = $this->db
                ->where_in('id_user', $arr_idpeg)
                ->where("(tanggal_mulai < '" . date('Y-m-d', $t_filter) . "' OR ('" . date('Y-m-d', $t_filter) . "' BETWEEN tanggal_mulai AND tanggal_akhir))")
                ->get('payroll_punishment');
            foreach ($punishment->result_array() as $key => $value) {
                $arr_pn[$value['id_user']][] = $value;
            }
        }
        foreach ($data->result_array() as $key => $value) {
            $temp = $value;
            $temp['punishment'] = 0;
            // punishment
            $t_akhir = $tanggal_akhir;
            // jika ada punishment, masa kerja akan berkurang
            if (array_key_exists($value['id_user'], $arr_pn)) {
                foreach ($arr_pn[$value['id_user']] as $vpn) {
                    // cari punishment yg masih dalam range 
                    // masa punishment melebihi tanggal filter, yg dihitung hingga tanggal filter
                    if ($t_filter >= strtotime($vpn['tanggal_mulai']) && $t_filter <= strtotime($vpn['tanggal_akhir'])) {
                        $temp['punishment'] += $this->selisih_bulan($vpn['tanggal_mulai'], date('Y-m-d', $t_filter));
                        continue;
                    }
                    $temp['punishment'] += $vpn['durasi'];
                }
                if ($temp['punishment'] > 0) {
                    $akhir = new DateTime(date('Y-m-d', $t_filter));
                    $akhir = $akhir->modify('-' . $temp['punishment'] . ' month');
                    $t_akhir = $akhir->format('Y-m-d');
                }
            }
            $mk = $this->masa_kerja($value['us_mulai_kerja'], $t_akhir);
            $temp['masa_kerja'] = $mk;
            // ketika bulan masuk 1-6 diikutkan kenaikan bulan 3, 7-12 kenaikan bulan 9
            // gaji pokok menggunakan tahun kerja - punishment
            $mk_ke = $this->masa_kerja($this->konversi_bulan($value['us_mulai_kerja']), $this->konversi_bulan($t_akhir));
            $th_kerja = $mk_ke['y'];
            $temp['tahun_kerja'] = $th_kerja;
            $temp['periode'] = $range_b['periode'];

            if ($th_kerja % 2 > 0 || $th_kerja == 0) {
                continue;
            }
            $temp['is_golongan'] = false;
            if ($th_kerja <= 16 && $th_kerja % 4 == 0) {
                $temp['is_golongan'] = true;
            }
            $temp['gaji_nominal'] = $t_gaji[$value['id_gol_gaji']][$th_kerja];
            $arr['kgb'][] = $temp;
        }

        $b_month = strtotime("-1 day", strtotime($tahun . '-' . $bulan . '-01'));
        $arr['mk_16'] = [];
        $data = $this->db->join('payroll_user_mapping', 'us_id = id_user')
            ->join('m_unit_kerja', 'us_uk_id = uk_id')
            ->join('m_jabatan', 'us_jabatan_id = jb_id')
            ->join('payroll_tunjangan_fungsi', 'id_tunj_fungsi = tf_id')
            ->where("month(us_mulai_kerja) BETWEEN '" . date('m', $b_month) . "' AND '$bulan'")
            ->where('is_kontrak', 0)
            ->get('z_user');
        foreach ($data->result_array() as $key => $value) {
            $mk = $this->masa_kerja($value['us_mulai_kerja'], $tanggal_akhir);
            $temp = $value;
            $temp['masa_kerja'] = $mk;
            if ($mk['y'] == 16 && $mk['m'] == 1) {
                $arr['mk_16'][] = $temp;
            }
        }
        return $arr;
    }

    public function get_rekap_gaji($bulan, $tahun)
    {
        $arr['gaji'] = $this->db->select("COALESCE(COUNT(id),0) as tk, COALESCE(COUNT(CASE when us_norek_bca='' then 1 end),0) as tk_tunai, COALESCE(COUNT(CASE when payroll_fixed.g_id=0 then 1 end),0) as tk_ptt, SUM(netto) as tnetto, SUM(netto-COALESCE(terima_nominal,0)) as tselisih")
            ->join('z_user', "us_id = id_user")
            ->get_where('payroll_fixed', ['tahun' => $tahun, 'bulan' => $bulan])
            ->row_array();
        return $arr;
    }

    public function get_pegawai()
    {
        $data = $this->db->join('payroll_user_mapping', 'us_id = id_user')
            ->select("COALESCE(COUNT(us_id),0) as tk, COALESCE(COUNT(CASE when is_kontrak=0 AND us_status = 1 then 1 end),0) as tk_tetap, COALESCE(COUNT(CASE when is_kontrak=1 then 1 end),0) as tk_kontrak, COALESCE(COUNT(CASE when us_status=2 then 1 end),0) as tk_na, COALESCE(COUNT(CASE when id_gol_gaji=0 then 1 end),0) as tk_ptt")
            ->get_where('z_user', ['us_level' => 2]);
        return $data->row_array();
    }

    public function selisih_bulan($mulai_kerja = '', $tanggal_akhir = '')
    {
        $begin = new DateTime($mulai_kerja);
        $end = new DateTime($tanggal_akhir);
        $interval = DateInterval::createFromDateString('1 month');
        //iterate
        $period = new DatePeriod($begin, $interval, $end);
        $t_month = iterator_count($period);
        return $t_month;
    }

    public function masa_kerja($mulai_kerja = '', $tanggal_akhir = '')
    {
        $t_month = $this->selisih_bulan($mulai_kerja, $tanggal_akhir);
        return ['y' => floor($t_month / 12), 'm' => ($t_month % 12)];
    }

    private function konversi_bulan($tanggal = '')
    {
        $tanggal = strtotime($tanggal);
        if (in_array(date('n', $tanggal), [1, 2, 3, 4, 5, 6])) {
            return date('Y', $tanggal) . '-03-01';
        }
        return date('Y', $tanggal) . '-09-01';
    }

    private function range_bulan($bulan)
    {
        if (in_array(abs($bulan), [1, 2, 3, 4, 5, 6])) {
            return ['ts' => str_pad(1, 2, "0", STR_PAD_LEFT), 'te' => str_pad(6, 2, "0", STR_PAD_LEFT), 'periode' => 3];
        }
        return ['ts' => str_pad(7, 2, "0", STR_PAD_LEFT), 'te' => str_pad(12, 2, "0", STR_PAD_LEFT), 'periode' => 9];
    }
}
