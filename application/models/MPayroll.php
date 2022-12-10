<?php
defined('BASEPATH') or exit('No direct script access allowed');

class MPayroll extends CI_Model
{

    public function get_mapping_gaji($uk_id)
    {
        $q = $this->db->join('payroll_user_mapping', 'us_id = id_user', 'left')
            ->order_by('id_gol_gaji DESC, us_nama')
            ->get_where('z_user', ['us_uk_id' => $uk_id, 'us_level' => 2, 'us_status' => 1]);
        return $q;
    }

    public function get_mapping_pegawai($arrWhere = [])
    {
        if (count($arrWhere) > 0) {
            $this->db->where($arrWhere);
        }
        $q = $this->db->join('payroll_user_mapping', 'us_id = id_user', 'left')
            ->order_by('id_gol_gaji DESC, us_nama')
            ->get('z_user');
        return $q;
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

    public function get_table_gaji($tahun = 0, $g_nama = '')
    {
        if ($tahun == 0) {
            $tahun = date('Y');
        }
        $data = $this->db->order_by('g_id')
            ->like('g_nama', $g_nama, 'after')
            ->get('payroll_golongan');
        $arr_result = $arr_id = [];
        foreach ($data->result_array() as $value) {
            $arr_id[] = $value['g_id'];
            $temp = $value;
            $temp['list'] = [];
            $arr_result[$value['g_id']] = $temp;
        }
        $detail = $this->db->where_in('gd_golongan_id', $arr_id)
            ->order_by('gd_tahun_kerja')
            ->get_where('payroll_golongan_detail', ['gd_tahun' => $tahun]);
        foreach ($detail->result_array() as $value) {
            $arr_result[$value['gd_golongan_id']]['list'][$value['gd_tahun_kerja']] = $value['gd_nominal'];
        }
        return $arr_result;
    }

    public function set_rekap_gaji($uk_id, $bulan, $tahun)
    {
        $t_filter = strtotime($tahun . '-' . $bulan . '-01');
        $tanggal_akhir = date('Y-m-d', $t_filter);
        // Prosentase BPJS & Nominal Transport
        $get_setting = $this->db->get('payroll_setting');
        $setting = [];
        foreach ($get_setting->result_array() as $key => $value) {
            $setting[$value['set_kode']] = $value['set_value'];
        }
        // Nominal Gaji UMK
        $get_umk = $this->db->get_where('payroll_umk', ['tahun' => $tahun]);
        $umk = 0;
        if ($get_umk->num_rows() > 0) {
            $get_umk = $get_umk->row_array();
            $umk = $get_umk['nominal'];
        }
        // Pattern Gaji
        $t_gaji = $this->table_gaji($tahun);
        // get data
        $q = $this->db
            ->join('payroll_user_mapping', 'us_id = id_user', 'left')
            ->join('payroll_golongan', 'id_gol_gaji = g_id', 'left')
            ->join('payroll_ptkp', 'id_status = kp_id', 'left')
            ->join('payroll_tunjangan_jabatan', 'id_tunj_jabatan = tj_id', 'left')
            ->join('payroll_tunjangan_fungsi', 'id_tunj_fungsi = tf_id', 'left')
            ->join('payroll_insentif', 'id_insentif = ti_id', 'left')
            ->order_by('id_gol_gaji DESC, us_nama')
            ->get_where('z_user', ['us_uk_id' => $uk_id, 'us_level' => 2, 'us_status' => 1]);
        $arr_idpeg = [];
        foreach ($q->result_array() as $value) {
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
        $arr_result = [];
        foreach ($q->result_array() as $value) {
            // echo '<pre>';
            // print_r($value);
            if ($value['id_user'] == '') {
                continue;
            }
            $mk_asli = $this->masa_kerja($value['us_mulai_kerja'], $tanggal_akhir);
            $temp = [
                'id' => $tahun . '-' . $bulan . ';' . $value['us_id'],
                'id_user' => $value['us_id'],
                'tahun' => $tahun,
                'bulan' => $bulan,
                'unit_kerja' => $uk_id,
                'us_reg' => $value['us_reg'],
                'us_nama' => $value['us_nama'],
                'us_mulai_kerja' => $value['us_mulai_kerja'],
                'punishment' => 0,
                'lama_kerja_asli' => $mk_asli['y'] . ' thn ' . $mk_asli['m'] . ' bln',
                'lama_kerja' => '',
                'tahun_kerja' => 0,
                'g_id' => $value['g_id'],
                'g_nama' => $value['g_nama'],
                'kp_nama' => $value['kp_kode'],
                'gaji_nominal' => 0,
                'tj_nominal' => 0,
                'tf_nominal' => 0,
                'ti_nominal' => 0,
                't_nominal' => 0,
                'rs_bpjs_jkk' => 0,
                'rs_bpjs_pensiun' => 0,
                'rs_bpjs_kesehatan' => 0,
                'bpjs_jkk' => 0,
                'bpjs_pensiun' => 0,
                'bpjs_kesehatan' => 0,
                'bruto' => 0,
                'pajak_rp' => 0,
                'potongan' => 0,
                'netto' => 0,
                'terima_json_additional' => null,
                'terima_nominal' => null
            ];
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
            $mk_punisment = $this->masa_kerja($value['us_mulai_kerja'], $t_akhir);
            $temp['lama_kerja'] = $mk_punisment['y'] . ' thn ' . $mk_punisment['m'] . ' bln';
            // ketika bulan masuk 1-6 diikutkan kenaikan bulan 3, 7-12 kenaikan bulan 9
            // gaji pokok menggunakan tahun kerja - punishment
            $mk_ke = $this->masa_kerja($this->konversi_bulan($value['us_mulai_kerja']), $this->konversi_bulan($t_akhir));
            $th_kerja = $mk_ke['y'];
            $temp['tahun_kerja'] = $th_kerja;
            // ketika sudah waktunya naik berkala/golongan bulan jan, feb naik pada bulan maret gaji belum berubah
            $mulai_kerja = strtotime($value['us_mulai_kerja']);
            if (date('n', $mulai_kerja) < 3 && abs($bulan) < 3) {
                if ($th_kerja > 0) {
                    $th_kerja--;
                }
            } else if (date('n', $mulai_kerja) < 9 && abs($bulan) < 9) {
                if ($th_kerja > 0) {
                    $th_kerja--;
                }
            }
            $temp['gaji_nominal'] = ($value['id_gol_gaji'] == '') ? 0 : $t_gaji[$value['id_gol_gaji']][$th_kerja];
            // ketika masih status kontrak, maka gaji memakai gaji tahun ke 0
            if ($value['is_kontrak']) {
                $temp['gaji_nominal'] = $t_gaji[$value['id_gol_gaji']][0];
            }
            // tunjangan menggunakan tahun kerja asli
            $mk = $this->masa_kerja($value['us_mulai_kerja'], $tanggal_akhir);
            if ($value['tj_id'] != '') {
                $temp['tj_nominal'] = $value['tj_baru'];
                if ($mk['y'] >= 16) {
                    if ($mk['y'] == 16 && $mk['m'] > 0) {
                        $temp['tj_nominal'] = $value['tj_lama'];
                    } else if ($mk['y'] > 16) {
                        $temp['tj_nominal'] = $value['tj_lama'];
                    }
                }
            }
            if ($value['tf_id'] != '') {
                $temp['tf_nominal'] = $value['tf_baru'];
                if ($mk['y'] >= 16) {
                    if ($mk['y'] == 16 && $mk['m'] > 0) {
                        $temp['tf_nominal'] = $value['tf_lama'];
                    } else if ($mk['y'] > 16) {
                        $temp['tf_nominal'] = $value['tf_lama'];
                    }
                }
            }
            if ($value['ti_id'] != '') {
                $temp['ti_nominal'] = $value['ti_nominal'];
            }
            if ($value['is_transport']) {
                $temp['t_nominal'] = $setting['is_transport'];
            }
            $grand_gaji = $temp['gaji_nominal'] + $temp['tj_nominal'] + $temp['tf_nominal'] + $temp['t_nominal'];
            // Jika gaji bruto dibawah UMK
            if ($grand_gaji < $umk) {
                $temp['gaji_nominal'] = $umk;
                $grand_gaji = $temp['gaji_nominal'] + $temp['tj_nominal'] + $temp['tf_nominal'] + $temp['t_nominal'];
            }
            $gaji_pensiun = $grand_gaji;
            if ($grand_gaji > $setting['max_bpjs_pensiun']) {
                $gaji_pensiun = $setting['max_bpjs_pensiun'];
            }
            // diberikan RS
            $grand_rs = 0;
            if ($value['is_bpjs']) {
                $temp['rs_bpjs_kesehatan'] = round($grand_gaji * $setting['persen_rs_bpjs_kesehatan'] / 100);
                $grand_rs += $temp['rs_bpjs_kesehatan'];
            }
            if ($value['is_jamsostek']) {
                $temp['rs_bpjs_jkk'] = round($grand_gaji * $setting['persen_rs_bpjs_jkk'] / 100);
                $temp['rs_bpjs_pensiun'] = round($gaji_pensiun * $setting['persen_rs_bpjs_pensiun'] / 100);
                $grand_rs += $temp['rs_bpjs_jkk'] + $temp['rs_bpjs_pensiun'];
            }
            // gaji bruto
            $temp['bruto'] = $grand_gaji + $grand_rs + $temp['ti_nominal'];
            // porongan BPJS
            if ($value['is_bpjs']) {
                $temp['bpjs_kesehatan'] = round($grand_gaji * $setting['persen_bpjs_kesehatan'] / 100);
            }
            if ($value['is_jamsostek']) {
                $temp['bpjs_jkk'] = round($grand_gaji * $setting['persen_bpjs_jkk'] / 100);
                $temp['bpjs_pensiun'] = round($gaji_pensiun * $setting['persen_bpjs_pensiun'] / 100);
            }
            $temp['pajak_rp'] = $value['pajak_rp'];
            $temp['potongan'] = $grand_rs + $temp['bpjs_jkk'] + $temp['bpjs_kesehatan'] + $temp['bpjs_pensiun'] + $value['pajak_rp'];
            $temp['netto'] = $temp['bruto'] - $temp['potongan'];
            $arr_result[] = $temp;
        }
        // die();
        $this->db->delete('payroll_fixed', ['unit_kerja' => $uk_id, 'tahun' => $tahun, 'bulan' => $bulan]);
        $sql = true;
        if (count($arr_result) > 0) {
            $sql = $this->db->insert_batch('payroll_fixed', $arr_result);
        }
        return $sql;
    }

    private function konversi_bulan($tanggal = '')
    {
        $tanggal = strtotime($tanggal);
        if (in_array(date('n', $tanggal), [1, 2, 3, 4, 5, 6])) {
            return date('Y', $tanggal) . '-03-01';
        }
        return date('Y', $tanggal) . '-09-01';
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

    public function get_punishment($arrWhere = [])
    {
        if (count($arrWhere) > 0) {
            $this->db->where($arrWhere);
        }
        $q = $this->db
            ->join('payroll_punishment', 'us_id = id_user')
            ->select('us_nama, payroll_punishment.*')
            ->order_by('tanggal_mulai')
            ->get_where('z_user', ['us_level' => 2]);
        return $q;
    }

    public function get_pengangkatan($arrWhere = [])
    {
        if (count($arrWhere) > 0) {
            $this->db->where($arrWhere);
        }
        $q = $this->db
            ->join('payroll_pengangkatan', 'us_id = id_user')
            ->join('payroll_punishment', 'ref_pn_id = pn_id', 'left')
            ->select('us_nama, payroll_pengangkatan.*, payroll_punishment.*')
            ->order_by('us_nama')
            ->get_where('z_user', ['us_level' => 2]);
        return $q;
    }

    var $nama_bulan = [
        '1' => 'Januari',
        '2' => 'Februari',
        '3' => 'Maret',
        '4' => 'April',
        '5' => 'Mei',
        '6' => 'Juni',
        '7' => 'Juli',
        '8' => 'Agustus',
        '9' => 'September',
        '10' => 'Oktober',
        '11' => 'November',
        '12' => 'Desember',
    ];

    public function get_locker($tahun, $arrWhere = [])
    {
        if (count($arrWhere) > 0) {
            $this->db->where($arrWhere);
        }
        $uk = $this->db->select('uk_id, uk_nama')
            ->get('m_unit_kerja');
        $arr_uk = [];
        foreach ($uk->result_array() as $key => $value) {
            $arr_uk[$value['uk_id']] = $value['uk_nama'];
        }
        $data = $this->db->get_where('payroll_locker', ['tahun' => $tahun]);
        $result = [];
        if ($data->num_rows() > 0) {
            foreach ($data->result_array() as $key => $value) {
                $str_uk = [];
                if (strpos($value['uk_id'], ';') !== false) {
                    foreach (explode(';', $value['uk_id']) as $key => $vuk) {
                        if ($vuk == '') {
                            continue;
                        }
                        $str_uk[] = $arr_uk[$vuk];
                    }
                } else {
                    $str_uk[] = $arr_uk[$value['uk_id']];
                }
                $result[] = [
                    'lc_id' => $value['lc_id'],
                    'tahun' => $value['tahun'],
                    'bulan' => $this->nama_bulan[abs($value['bulan'])],
                    'unit_kerja' => (count($str_uk) == count($arr_uk)) ? 'Seluruh Unit' : implode(', ', $str_uk)
                ];
            }
        }
        return $result;
    }

    public function cek_locker($tahun, $bulan)
    {
        $data = $this->db->get_where('payroll_locker', ['tahun' => $tahun, 'bulan' => $bulan]);
        if ($data->row_array() > 0) {
            $data = $data->row_array();
            $arr_uk = [];
            if (strpos($data['uk_id'], ';') !== false) {
                $arr_uk = explode(';', $data['uk_id']);
            } else {
                $arr_uk[] = $data['uk_id'];
            }
            return ['status' => true, 'id' => $data['lc_id'], 'uk' => $arr_uk];
        }
        return ['status' => false];
    }

    public function is_lock($tahun, $bulan, $uk_id)
    {
        $data = $this->db->get_where('payroll_locker', ['tahun' => $tahun, 'bulan' => $bulan]);
        if ($data->row_array() > 0) {
            $data = $data->row_array();
            $arr_uk = [];
            if (strpos($data['uk_id'], ';') !== false) {
                $arr_uk = explode(';', $data['uk_id']);
            } else {
                $arr_uk[] = $data['uk_id'];
            }
            if (in_array($uk_id, $arr_uk)) {
                return true;
            }
        }
        return false;
    }

    public function list_slip_gaji($tahun)
    {
        $mapping = $this->db->join('m_unit_kerja', 'uk_id = sl_uk_id', 'left')
            ->join('payroll_golongan', 'g_id = sl_g_id', 'left')
            ->order_by('sl_urut, sl_nama')
            ->get_where('payroll_lap_slip_gaji', ['sl_tahun' => $tahun]);
        $result = [];
        foreach ($mapping->result_array() as $key => $mp) {
            $temp = $mp;
            $temp['content'] = '';
            switch ($mp['sl_jenis']) {
                case 0:
                    $arr_mjab = json_decode($mp['sl_jm_id'], true);
                    $data = $this->db->join('m_jabatan_mapping', 'us_jabatan_id = jm_jb_id AND us_uk_id = jm_uk_id')
                        ->where_in('jm_id', $arr_mjab)
                        ->select('us_nama')
                        ->get_where('z_user', ['us_level' => 2, 'us_status' => 1]);
                    $nmpeg = [];
                    foreach ($data->result_array() as $key => $value) {
                        $nmpeg[] = $value['us_nama'];
                    }
                    $temp['content'] = implode('<br>', $nmpeg);
                    break;
                case 2:
                    $arr_idpeg = json_decode($mp['sl_us_id'], true);
                    $data = $this->db->where_in('us_id', $arr_idpeg)
                        ->select('us_nama')
                        ->get_where('z_user');
                    $nmpeg = [];
                    foreach ($data->result_array() as $key => $value) {
                        $nmpeg[] = $value['us_nama'];
                    }
                    $temp['content'] = implode('<br>', $nmpeg);
                    break;
                case 3:
                    $temp['content'] = $mp['g_nama'];
                    break;
                default:
                    $temp['content'] = $mp['uk_nama'];
                    break;
            }
            $result[] = $temp;
        }
        return $result;
    }

    public function edit_slip_gaji($sl_id)
    {
        $mapping = $this->db->get_where('payroll_lap_slip_gaji', ['sl_id' => $sl_id])->row_array();
        $result = $mapping;
        $result['table'] = '';
        switch ($mapping['sl_jenis']) {
            case 0:
                // berdasarkan Jabatan
                $arr_mjab = json_decode($mapping['sl_jm_id'], true);
                $jab = $this->db->join('m_jabatan', 'jb_id = jm_jb_id')
                    ->join('m_unit_kerja', 'uk_id = jm_uk_id')
                    ->select('jm_id, uk_nama, jb_nama')
                    ->where_in('jm_id', $arr_mjab)
                    ->order_by('uk_nama, jb_nama')
                    ->get('m_jabatan_mapping');
                foreach ($jab->result_array() as $key => $value) {
                    $result['table'] .= '<tr id="' . $value['jm_id'] . '"><td class="text-center">#</td>' .
                        '<td><input type="hidden" name="idjm[]" value="' . $value['jm_id'] . '">' . $value['jb_nama'] . ' - ' . $value['uk_nama'] . '</td>' .
                        '<td class="text-center"><button id="btn-remove" type="button" class="btn btn-xs btn-danger"><i class="fa fa-trash"></i></button></td></tr>';
                }
                break;
            case 1:
                // berdasarkan Pegawai
                $arr_mjab = json_decode($mapping['sl_jm_id'], true);
                $jab = $this->db->join('m_jabatan', 'jb_id = jm_jb_id')
                    ->join('m_unit_kerja', 'uk_id = jm_uk_id')
                    ->select('jm_id, uk_nama, jb_nama')
                    ->get_where('m_jabatan_mapping', ['jm_id' => $arr_mjab]);
                foreach ($jab->result_array() as $key => $value) {
                    $result['table'] .= '<tr id="' . $value['jm_id'] . '"><td class="text-center">#</td>' .
                        '<td><input type="hidden" name="idjm[]" value="' . $value['jm_id'] . '">' . $value['jb_nama'] . ' - ' . $value['uk_nama'] . '</td>' .
                        '<td class="text-center"><button id="btn-remove" type="button" class="btn btn-xs btn-danger"><i class="fa fa-trash"></i></button></td></tr>';
                }
                break;
            case 2:
                $arr_idpeg = json_decode($mapping['sl_us_id'], true);
                $peg = $this->db->where_in('us_id', $arr_idpeg)
                    ->select('us_id, us_nama')
                    ->get('z_user');
                foreach ($peg->result_array() as $key => $value) {
                    $result['table'] .= '<tr id="' . $value['us_id'] . '"><td class="text-center">#</td>' .
                        '<td><input type="hidden" name="idpeg[]" value="' . $value['us_id'] . '">' . $value['us_nama'] . '</td>' .
                        '<td class="text-center"><button id="btn-remove" type="button" class="btn btn-xs btn-danger"><i class="fa fa-trash"></i></button></td></tr>';
                }
                break;
            default:
                break;
        }
        return $result;
    }

    public function get_slip_gaji($sl_id, $tahun, $bulan)
    {
        $mapping = $this->MCore->get_data('payroll_lap_slip_gaji', ['sl_id' => $sl_id])->row_array();
        switch ($mapping['sl_jenis']) {
            case 0:
                $arr_mjab = json_decode($mapping['sl_jm_id'], true);
                $data = $this->db->join('m_jabatan_mapping', 'us_jabatan_id = jm_jb_id AND us_uk_id = jm_uk_id')
                    ->where_in('jm_id', $arr_mjab)
                    ->select('us_id')
                    ->get_where('z_user', ['us_level' => 2, 'us_status' => 1]);
                $idpeg = [];
                foreach ($data->result_array() as $key => $value) {
                    $idpeg[] = $value['us_id'];
                }
                $this->db->where_in('id_user', $idpeg)
                    ->where('g_id <>', 0);
                break;
            case 2:
                $idpeg = json_decode($mapping['sl_us_id'], true);
                $this->db->where_in('id_user', $idpeg)
                    ->where('g_id <>', 0);
                break;
            case 3:
                $this->db->where_in('g_id', 0);
                break;
            default:
                $this->db->where('unit_kerja', $mapping['sl_uk_id'])
                    ->where('g_id <>', 0);
                break;
        }
        $sql = $this->db->order_by('g_id DESC, us_nama')
            ->get_where('payroll_fixed', ['bulan' => $bulan, 'tahun' => $tahun]);
        return $sql->result_array();
    }

    public function get_slip_gaji_unit($tahun, $bulan)
    {
        $data = $this->MCore->list_data('payroll_lap_slip_gaji', 'sl_urut');
        $result = [];
        foreach ($data->result_array() as $key => $mapping) {
            switch ($mapping['sl_jenis']) {
                case 0:
                    $arr_mjab = json_decode($mapping['sl_jm_id'], true);
                    $data = $this->db->join('m_jabatan_mapping', 'us_jabatan_id = jm_jb_id AND us_uk_id = jm_uk_id')
                        ->where_in('jm_id', $arr_mjab)
                        ->select('us_id')
                        ->get_where('z_user', ['us_level' => 2, 'us_status' => 1]);
                    $idpeg = [];
                    foreach ($data->result_array() as $key => $value) {
                        $idpeg[] = $value['us_id'];
                    }
                    $this->db->where_in('id_user', $idpeg)
                        ->where('g_id <>', 0);
                    break;
                case 2:
                    $idpeg = json_decode($mapping['sl_us_id'], true);
                    $this->db->where_in('id_user', $idpeg)
                        ->where('g_id <>', 0);
                    break;
                case 3:
                    $this->db->where_in('g_id', 0);
                    break;
                default:
                    $this->db->where('unit_kerja', $mapping['sl_uk_id'])
                        ->where('g_id <>', 0);
                    break;
            }
            $unit = $this->db->select('COUNT(id) as jml_peg, SUM(gaji_nominal) as gaji_nominal, SUM(tj_nominal) as tj_nominal, SUM(tf_nominal) as tf_nominal, SUM(ti_nominal) as ti_nominal, SUM(t_nominal) as t_nominal,  
                SUM(rs_bpjs_jkk) as rs_bpjs_jkk, SUM(rs_bpjs_pensiun) as rs_bpjs_pensiun, SUM(rs_bpjs_kesehatan) as rs_bpjs_kesehatan, SUM(bpjs_jkk) as bpjs_jkk, 
                SUM(bpjs_pensiun) as bpjs_pensiun, SUM(bpjs_kesehatan) as bpjs_kesehatan, SUM(bruto) as bruto, SUM(pajak_rp) as pajak_rp, SUM(potongan) as potongan, SUM(netto) as netto')
                ->get_where('payroll_fixed', ['bulan' => $bulan, 'tahun' => $tahun]);
            if ($unit->num_rows() > 0) {
                $temp = $unit->row_array();
                $temp['sl_nama'] = $mapping['sl_nama'];
                $result[] = $temp;
            }
        }
        return $result;
    }

    public function get_excel_slip_gaji_unit($tahun, $bulan)
    {
        $data = $this->MCore->list_data('payroll_lap_slip_gaji', 'sl_urut');
        $result = [
            'rekap' => [],
            'list' => [],
            'ptt' => []
        ];
        foreach ($data->result_array() as $key => $mapping) {
            switch ($mapping['sl_jenis']) {
                case 0:
                    $arr_mjab = json_decode($mapping['sl_jm_id'], true);
                    $data = $this->db->join('m_jabatan_mapping', 'us_jabatan_id = jm_jb_id AND us_uk_id = jm_uk_id')
                        ->where_in('jm_id', $arr_mjab)
                        ->select('us_id')
                        ->get_where('z_user', ['us_level' => 2, 'us_status' => 1]);
                    $idpeg = [];
                    foreach ($data->result_array() as $key => $value) {
                        $idpeg[] = $value['us_id'];
                    }
                    $this->db->where_in('id_user', $idpeg)
                        ->where('g_id <>', 0);
                    break;
                case 2:
                    $idpeg = json_decode($mapping['sl_us_id'], true);
                    $this->db->where_in('id_user', $idpeg)
                        ->where('g_id <>', 0);
                    break;
                case 3:
                    $this->db->where_in('g_id', 0);
                    break;
                default:
                    $this->db->where('unit_kerja', $mapping['sl_uk_id'])
                        ->where('g_id <>', 0);
                    break;
            }
            $unit = $this->db->join('m_unit_kerja', 'uk_id = unit_kerja')
                ->get_where('payroll_fixed', ['bulan' => $bulan, 'tahun' => $tahun]);
            foreach ($unit->result_array() as $key => $value) {
                $result['list'][$mapping['sl_id']][] = $value;
                if ($mapping['sl_nama'] == 'PTT') {
                    if (!array_key_exists($value['uk_id'], $result['ptt'])) {
                        $result['ptt'][$value['uk_id']] = [
                            'uk_nama' => $mapping['sl_nama'] . ' - ' . $value['uk_nama'],
                            'jml_peg' => 0,
                            'gaji_nominal' => 0,
                            'tj_nominal' => 0,
                            'tf_nominal' => 0,
                            'ti_nominal' => 0,
                            't_nominal' => 0,
                            'rs_bpjs_jkk' => 0,
                            'rs_bpjs_pensiun' => 0,
                            'rs_bpjs_kesehatan' => 0,
                            'bpjs_jkk' => 0,
                            'bpjs_pensiun' => 0,
                            'bpjs_kesehatan' => 0,
                            'bruto' => 0,
                            'pajak_rp' => 0,
                            'potongan' => 0,
                            'netto' => 0
                        ];
                    }
                    $result['ptt'][$value['uk_id']]['jml_peg']++;
                    $result['ptt'][$value['uk_id']]['gaji_nominal'] += $value['gaji_nominal'];
                    $result['ptt'][$value['uk_id']]['tj_nominal'] += $value['tj_nominal'];
                    $result['ptt'][$value['uk_id']]['tf_nominal'] += $value['tf_nominal'];
                    $result['ptt'][$value['uk_id']]['ti_nominal'] += $value['ti_nominal'];
                    $result['ptt'][$value['uk_id']]['t_nominal'] += $value['t_nominal'];
                    $result['ptt'][$value['uk_id']]['rs_bpjs_jkk'] += $value['rs_bpjs_jkk'];
                    $result['ptt'][$value['uk_id']]['rs_bpjs_pensiun'] += $value['rs_bpjs_pensiun'];
                    $result['ptt'][$value['uk_id']]['rs_bpjs_kesehatan'] += $value['rs_bpjs_kesehatan'];
                    $result['ptt'][$value['uk_id']]['bpjs_jkk'] += $value['bpjs_jkk'];
                    $result['ptt'][$value['uk_id']]['bpjs_pensiun'] += $value['bpjs_pensiun'];
                    $result['ptt'][$value['uk_id']]['bpjs_kesehatan'] += $value['bpjs_kesehatan'];
                    $result['ptt'][$value['uk_id']]['bruto'] += $value['bruto'];
                    $result['ptt'][$value['uk_id']]['pajak_rp'] += $value['pajak_rp'];
                    $result['ptt'][$value['uk_id']]['potongan'] += $value['potongan'];
                    $result['ptt'][$value['uk_id']]['netto'] += $value['netto'];
                    continue;
                }
                if (!array_key_exists($mapping['sl_id'], $result['rekap'])) {
                    $result['rekap'][$mapping['sl_id']] = [
                        'sl_nama' => $mapping['sl_nama'],
                        'jml_peg' => 0,
                        'gaji_nominal' => 0,
                        'tj_nominal' => 0,
                        'tf_nominal' => 0,
                        'ti_nominal' => 0,
                        't_nominal' => 0,
                        'rs_bpjs_jkk' => 0,
                        'rs_bpjs_pensiun' => 0,
                        'rs_bpjs_kesehatan' => 0,
                        'bpjs_jkk' => 0,
                        'bpjs_pensiun' => 0,
                        'bpjs_kesehatan' => 0,
                        'bruto' => 0,
                        'pajak_rp' => 0,
                        'potongan' => 0,
                        'netto' => 0
                    ];
                }
                // hitung total
                $result['rekap'][$mapping['sl_id']]['jml_peg']++;
                $result['rekap'][$mapping['sl_id']]['gaji_nominal'] += $value['gaji_nominal'];
                $result['rekap'][$mapping['sl_id']]['tj_nominal'] += $value['tj_nominal'];
                $result['rekap'][$mapping['sl_id']]['tf_nominal'] += $value['tf_nominal'];
                $result['rekap'][$mapping['sl_id']]['ti_nominal'] += $value['ti_nominal'];
                $result['rekap'][$mapping['sl_id']]['t_nominal'] += $value['t_nominal'];
                $result['rekap'][$mapping['sl_id']]['rs_bpjs_jkk'] += $value['rs_bpjs_jkk'];
                $result['rekap'][$mapping['sl_id']]['rs_bpjs_pensiun'] += $value['rs_bpjs_pensiun'];
                $result['rekap'][$mapping['sl_id']]['rs_bpjs_kesehatan'] += $value['rs_bpjs_kesehatan'];
                $result['rekap'][$mapping['sl_id']]['bpjs_jkk'] += $value['bpjs_jkk'];
                $result['rekap'][$mapping['sl_id']]['bpjs_pensiun'] += $value['bpjs_pensiun'];
                $result['rekap'][$mapping['sl_id']]['bpjs_kesehatan'] += $value['bpjs_kesehatan'];
                $result['rekap'][$mapping['sl_id']]['bruto'] += $value['bruto'];
                $result['rekap'][$mapping['sl_id']]['pajak_rp'] += $value['pajak_rp'];
                $result['rekap'][$mapping['sl_id']]['potongan'] += $value['potongan'];
                $result['rekap'][$mapping['sl_id']]['netto'] += $value['netto'];
            }
        }
        return $result;
    }

    public function get_slip_gaji_bca($tahun, $bulan)
    {
        $data = $this->MCore->list_data('payroll_lap_slip_gaji', 'sl_urut');
        $result = [];
        foreach ($data->result_array() as $key => $mapping) {
            switch ($mapping['sl_jenis']) {
                case 0:
                    $arr_mjab = json_decode($mapping['sl_jm_id'], true);
                    $data = $this->db->join('m_jabatan_mapping', 'us_jabatan_id = jm_jb_id AND us_uk_id = jm_uk_id')
                        ->where_in('jm_id', $arr_mjab)
                        ->select('us_id')
                        ->get_where('z_user', ['us_level' => 2, 'us_status' => 1]);
                    $idpeg = [];
                    foreach ($data->result_array() as $key => $value) {
                        $idpeg[] = $value['us_id'];
                    }
                    $this->db->where_in('id_user', $idpeg)
                        ->where('g_id <>', 0);
                    break;
                case 2:
                    $idpeg = json_decode($mapping['sl_us_id'], true);
                    $this->db->where_in('id_user', $idpeg)
                        ->where('g_id <>', 0);
                    break;
                case 3:
                    $this->db->where_in('g_id', 0);
                    break;
                default:
                    $this->db->where('unit_kerja', $mapping['sl_uk_id'])
                        ->where('g_id <>', 0);
                    break;
            }
            $unit = $this->db->join('m_unit_kerja', 'unit_kerja = uk_id')
                ->join('z_user', 'us_id = id_user')
                ->select('payroll_fixed.*, uk_nama, us_norek_bca, us_an_rek')
                ->get_where('payroll_fixed', ['bulan' => $bulan, 'tahun' => $tahun]);
            if ($unit->num_rows() > 0) {
                // $temp = $unit->row_array();
                // $temp['sl_nama'] = $mapping['sl_nama'];
                // $temp['sl_urut'] = $mapping['sl_urut'];
                foreach ($unit->result_array() as $key => $value) {
                    $temp = $value;
                    $temp['sl_nama'] = $mapping['sl_nama'];
                    $result[] = $temp;
                }
            }
        }
        return $result;
    }

    // public function search_gaji($uk, $tahun, $bulan, $word = '')
    // {
    //     $sql = $this->db
    //         // ->join('z_user', 'us_id = id_user')
    //         ->like('us_nama', $word)
    //         ->select('id, us_nama')
    //         ->get_where('payroll_fixed', ['bulan' => $bulan, 'tahun' => $tahun, 'unit_kerja' => $uk]);
    //     return $sql;
    // }

    public function get_rekap_gaji_unit($tahun, $bulan)
    {
        $locker = $this->db->get_where('payroll_locker', ['tahun' => $tahun, 'bulan' => $bulan]);
        $lock_uk = [];
        if ($locker->num_rows() > 0) {
            $value = $locker->row_array();
            if (strpos($value['uk_id'], ';') !== false) {
                foreach (explode(';', $value['uk_id']) as $key => $vuk) {
                    if ($vuk == '') {
                        continue;
                    }
                    $lock_uk[] = $vuk;
                }
            } else {
                $lock_uk[] = $value['uk_id'];
            }
        }
        $uk = $this->db
            ->join('payroll_fixed', "uk_id = unit_kerja AND tahun = '$tahun' AND bulan = '$bulan'", 'left')
            // SUM(case when netto-COALESCE(terima_nominal,0) <> netto then netto-COALESCE(terima_nominal,0) else 0 end) as tpotongan
            ->select('uk_id, uk_nama, COALESCE(COUNT(id),0) as tk, COALESCE(SUM(netto),0) as tnetto, COALESCE(SUM(case when terima_nominal is null then netto else terima_nominal end),0) as diterima')
            ->order_by('uk_nama')
            ->group_by('uk_id')
            ->get('m_unit_kerja');
        $result = [];
        foreach ($uk->result_array() as $key => $value) {
            $temp = $value;
            $temp['status'] = (in_array($value['uk_id'], $lock_uk));
            $result[] = $temp;
        }
        return $result;
    }

    public function get_table_additional($tahun, $bulan, $jenis)
    {
        switch ($jenis) {
            case 'kontrak':
                $this->db->where('is_kontrak', 1);
                break;
            default:
                # code...
                break;
        }
        $result = $this->db->join('payroll_user_mapping b', 'a.id_user = b.id_user')
            ->order_by('a.g_id DESC, us_nama')
            ->get_where('payroll_fixed a', ['tahun' => $tahun, 'bulan' => $bulan]);
        return $result;
    }

    public function estimasi_gaji($bulan, $tahun, $prosentase = 0)
    {
        $t_filter = strtotime($tahun . '-' . $bulan . '-01');
        $tanggal_akhir = date('Y-m-d', $t_filter);
        // Prosentase BPJS & Nominal Transport
        $get_setting = $this->db->get('payroll_setting');
        $setting = [];
        foreach ($get_setting->result_array() as $key => $value) {
            $setting[$value['set_kode']] = $value['set_value'];
        }
        // Nominal Gaji UMK
        $get_umk = $this->db->get_where('payroll_umk', ['tahun' => $tahun]);
        $umk = 0;
        if ($get_umk->num_rows() > 0) {
            $get_umk = $get_umk->row_array();
            $umk = $get_umk['nominal'];
        }
        // Pattern Gaji
        $t_gaji = $this->table_gaji($tahun - 1);
        // get data
        $q = $this->db
            ->join('payroll_user_mapping', 'us_id = id_user', 'left')
            ->join('payroll_golongan', 'id_gol_gaji = g_id', 'left')
            ->join('payroll_ptkp', 'id_status = kp_id', 'left')
            ->join('payroll_tunjangan_jabatan', 'id_tunj_jabatan = tj_id', 'left')
            ->join('payroll_tunjangan_fungsi', 'id_tunj_fungsi = tf_id', 'left')
            ->join('payroll_insentif', 'id_insentif = ti_id', 'left')
            ->order_by('id_gol_gaji DESC, us_nama')
            ->get_where('z_user', ['us_level' => 2, 'us_status' => 1]);
        $arr_idpeg = [];
        foreach ($q->result_array() as $value) {
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
        $tnetto = 0;
        foreach ($q->result_array() as $value) {
            if ($value['id_user'] == '') {
                continue;
            }
            $mk_asli = $this->masa_kerja($value['us_mulai_kerja'], $tanggal_akhir);
            $temp = [
                'id' => $tahun . '-' . $bulan . ';' . $value['us_id'],
                'id_user' => $value['us_id'],
                'tahun' => $tahun,
                'bulan' => $bulan,
                'unit_kerja' => $value['us_uk_id'],
                'us_reg' => $value['us_reg'],
                'us_nama' => $value['us_nama'],
                'us_mulai_kerja' => $value['us_mulai_kerja'],
                'punishment' => 0,
                'lama_kerja_asli' => $mk_asli['y'] . ' thn ' . $mk_asli['m'] . ' bln',
                'lama_kerja' => '',
                'tahun_kerja' => 0,
                'g_id' => $value['g_id'],
                'g_nama' => $value['g_nama'],
                'kp_nama' => $value['kp_kode'],
                'gaji_nominal' => 0,
                'tj_nominal' => 0,
                'tf_nominal' => 0,
                'ti_nominal' => 0,
                't_nominal' => 0,
                'rs_bpjs_jkk' => 0,
                'rs_bpjs_pensiun' => 0,
                'rs_bpjs_kesehatan' => 0,
                'bpjs_jkk' => 0,
                'bpjs_pensiun' => 0,
                'bpjs_kesehatan' => 0,
                'bruto' => 0,
                'pajak_rp' => 0,
                'potongan' => 0,
                'netto' => 0,
                'terima_json_additional' => null,
                'terima_nominal' => null
            ];
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
            $mk_punisment = $this->masa_kerja($value['us_mulai_kerja'], $t_akhir);
            $temp['lama_kerja'] = $mk_punisment['y'] . ' thn ' . $mk_punisment['m'] . ' bln';
            // ketika bulan masuk 1-6 diikutkan kenaikan bulan 3, 7-12 kenaikan bulan 9
            // gaji pokok menggunakan tahun kerja - punishment
            $mk_ke = $this->masa_kerja($this->konversi_bulan($value['us_mulai_kerja']), $this->konversi_bulan($t_akhir));
            $th_kerja = $mk_ke['y'];
            $temp['tahun_kerja'] = $th_kerja;
            // ketika sudah waktunya naik berkala/golongan bulan jan, feb naik pada bulan maret gaji belum berubah
            $mulai_kerja = strtotime($value['us_mulai_kerja']);
            if (date('n', $mulai_kerja) < 3 && abs($bulan) < 3) {
                if ($th_kerja > 0) {
                    $th_kerja--;
                }
            } else if (date('n', $mulai_kerja) < 9 && abs($bulan) < 9) {
                if ($th_kerja > 0) {
                    $th_kerja--;
                }
            }
            $temp['gaji_nominal'] = 0;
            if ($value['id_gol_gaji'] != '') {
                if ($temp['g_id'] == 0 && $th_kerja > 5) {
                    $th_kerja = 5;
                }
                $temp['gaji_nominal'] = $t_gaji[$value['id_gol_gaji']][$th_kerja];
            }
            // ketika masih status kontrak, maka gaji memakai gaji tahun ke 0
            if ($value['is_kontrak']) {
                $temp['gaji_nominal'] = $t_gaji[$value['id_gol_gaji']][0];
            }
            $temp['gaji_nominal'] = round($temp['gaji_nominal'] + ($temp['gaji_nominal'] * $prosentase / 100));
            // tunjangan menggunakan tahun kerja asli
            $mk = $this->masa_kerja($value['us_mulai_kerja'], $tanggal_akhir);
            if ($value['tj_id'] != '') {
                $temp['tj_nominal'] = $value['tj_baru'];
                if ($mk['y'] >= 16) {
                    if ($mk['y'] == 16 && $mk['m'] > 0) {
                        $temp['tj_nominal'] = $value['tj_lama'];
                    } else if ($mk['y'] > 16) {
                        $temp['tj_nominal'] = $value['tj_lama'];
                    }
                }
            }
            if ($value['tf_id'] != '') {
                $temp['tf_nominal'] = $value['tf_baru'];
                if ($mk['y'] >= 16) {
                    if ($mk['y'] == 16 && $mk['m'] > 0) {
                        $temp['tf_nominal'] = $value['tf_lama'];
                    } else if ($mk['y'] > 16) {
                        $temp['tf_nominal'] = $value['tf_lama'];
                    }
                }
            }
            if ($value['ti_id'] != '') {
                $temp['ti_nominal'] = $value['ti_nominal'];
            }
            if ($value['is_transport']) {
                $temp['t_nominal'] = $setting['is_transport'];
            }
            $grand_gaji = $temp['gaji_nominal'] + $temp['tj_nominal'] + $temp['tf_nominal'] + $temp['t_nominal'];
            // Jika gaji bruto dibawah UMK
            if ($grand_gaji < $umk) {
                $temp['gaji_nominal'] = $umk;
                $grand_gaji = $temp['gaji_nominal'] + $temp['tj_nominal'] + $temp['tf_nominal'] + $temp['t_nominal'];
            }
            $gaji_pensiun = $grand_gaji;
            if ($grand_gaji > $setting['max_bpjs_pensiun']) {
                $gaji_pensiun = $setting['max_bpjs_pensiun'];
            }
            // diberikan RS
            $grand_rs = 0;
            if ($value['is_bpjs']) {
                $temp['rs_bpjs_kesehatan'] = round($grand_gaji * $setting['persen_rs_bpjs_kesehatan'] / 100);
                $grand_rs += $temp['rs_bpjs_kesehatan'];
            }
            if ($value['is_jamsostek']) {
                $temp['rs_bpjs_jkk'] = round($grand_gaji * $setting['persen_rs_bpjs_jkk'] / 100);
                $temp['rs_bpjs_pensiun'] = round($gaji_pensiun * $setting['persen_rs_bpjs_pensiun'] / 100);
                $grand_rs += $temp['rs_bpjs_jkk'] + $temp['rs_bpjs_pensiun'];
            }
            // gaji bruto
            $temp['bruto'] = $grand_gaji + $grand_rs + $temp['ti_nominal'];
            // porongan BPJS
            if ($value['is_bpjs']) {
                $temp['bpjs_kesehatan'] = round($grand_gaji * $setting['persen_bpjs_kesehatan'] / 100);
            }
            if ($value['is_jamsostek']) {
                $temp['bpjs_jkk'] = round($grand_gaji * $setting['persen_bpjs_jkk'] / 100);
                $temp['bpjs_pensiun'] = round($gaji_pensiun * $setting['persen_bpjs_pensiun'] / 100);
            }
            $temp['pajak_rp'] = $value['pajak_rp'];
            $temp['potongan'] = $grand_rs + $temp['bpjs_jkk'] + $temp['bpjs_kesehatan'] + $temp['bpjs_pensiun'] + $value['pajak_rp'];
            $temp['netto'] = $temp['bruto'] - $temp['potongan'];
            $tnetto += $temp['netto'];
        }
        return $tnetto;
    }
}
