<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Lap_gaji extends RS_Controller
{

    public function index()
    {
        $header = $this->sendHeader('nav_l_gaji', 'Laporan Gaji', 'overlay-sidebar');
        $header['css'] = array(
            'jquery.stickytable/jquery.stickytable.css',
            'datepicker/css/tempusdominus-bootstrap-4.min.css',
        );
        $header['js'] = array(
            'jquery.stickytable/jquery.stickytable.js',
            'moment/moment.min.js',
            'datepicker/js/tempusdominus-bootstrap-4.min.js',
        );
        $setting = $this->MCore->get_setting();
        $body = [
            's' => $setting,
            'opt_slip' => $this->opt_slip(),
        ];
        $data = array_merge($header, $body);
        $this->template->view('laporan/VLapgaji', $data);
    }

    private function opt_slip($select = '')
    {
        $data = $this->MCore->get_data('payroll_lap_slip_gaji', [], 'sl_urut');
        $opt = '<option value="">-Pilih-</option>';
        foreach ($data->result_array() as $value) {
            $selected = $value['sl_id'] == $select ? 'selected=""' : '';
            $opt .= '<option ' . $selected . ' value="' . $value['sl_id'] . '">' . $value['sl_nama'] . '</option>';
        }
        return $opt;
    }

    public function list_($sl_id = 0, $bulan = '', $tahun = '')
    {
        if ($tahun == '') {
            $tahun = date('Y');
        }
        if ($bulan == '') {
            $bulan = date('m');
        }
        $this->load->model('MPayroll');
        $data = $this->MPayroll->get_slip_gaji($sl_id, $tahun, $bulan);
        ob_start();
        $total = [
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
        if (count($data) > 0) {
            $no = 1;
            foreach ($data as $value) {
                $punisment_desc = ($value['punishment'] > 0) ? '<br><span class="text-danger"><i class="fa fa-gavel"></i> -' . $value['punishment'] . ' Bulan</span>' : '';
?>
                <tr>
                    <td><?= $no ?></td>
                    <td class="sticky-cell">
                        <?= $value['us_nama'] . '<br><i class="fas fa-id-card-alt"></i> ' . $value['us_reg'] ?>
                    </td>
                    <td><?= date('d/m/Y', strtotime($value['us_mulai_kerja'])) ?></td>
                    <td><?= $value['lama_kerja_asli'] . $punisment_desc ?></td>
                    <td class="text-center"><?= $value['g_nama'] ?></td>
                    <td class="text-center"><?= $value['kp_nama'] ?></td>
                    <td class="text-right"><?= $this->nf($value['gaji_nominal']) ?></td>
                    <td class="text-right"><?= $this->nf($value['tj_nominal']) ?></td>
                    <td class="text-right"><?= $this->nf($value['tf_nominal']) ?></td>
                    <td class="text-right"><?= $this->nf($value['t_nominal']) ?></td>
                    <td class="text-right"><?= $this->nf($value['rs_bpjs_jkk']) ?></td>
                    <td class="text-right"><?= $this->nf($value['rs_bpjs_pensiun']) ?></td>
                    <td class="text-right"><?= $this->nf($value['rs_bpjs_kesehatan']) ?></td>
                    <td class="text-right"><?= $this->nf($value['ti_nominal']) ?></td>
                    <td class="text-right"><?= $this->nf($value['bruto']) ?></td>
                    <td class="text-right"><?= $this->nf($value['bpjs_jkk']) ?></td>
                    <td class="text-right"><?= $this->nf($value['rs_bpjs_jkk']) ?></td>
                    <td class="text-right"><?= $this->nf($value['bpjs_pensiun']) ?></td>
                    <td class="text-right"><?= $this->nf($value['rs_bpjs_pensiun']) ?></td>
                    <td class="text-right"><?= $this->nf($value['pajak_rp']) ?></td>
                    <td class="text-right"><?= $this->nf($value['bpjs_kesehatan']) ?></td>
                    <td class="text-right"><?= $this->nf($value['rs_bpjs_kesehatan']) ?></td>
                    <td class="text-right"><?= $this->nf($value['potongan']) ?></td>
                    <td class="text-right"><?= $this->nf($value['netto']) ?></td>
                </tr>
            <?php
                $total['gaji_nominal'] += $value['gaji_nominal'];
                $total['tj_nominal'] += $value['tj_nominal'];
                $total['tf_nominal'] += $value['tf_nominal'];
                $total['ti_nominal'] += $value['ti_nominal'];
                $total['t_nominal'] += $value['t_nominal'];
                $total['rs_bpjs_jkk'] += $value['rs_bpjs_jkk'];
                $total['rs_bpjs_pensiun'] += $value['rs_bpjs_pensiun'];
                $total['rs_bpjs_kesehatan'] += $value['rs_bpjs_kesehatan'];
                $total['bpjs_jkk'] += $value['bpjs_jkk'];
                $total['bpjs_pensiun'] += $value['bpjs_pensiun'];
                $total['bpjs_kesehatan'] += $value['bpjs_kesehatan'];
                $total['bruto'] += $value['bruto'];
                $total['pajak_rp'] += $value['pajak_rp'];
                $total['potongan'] += $value['potongan'];
                $total['netto'] += $value['netto'];
                $no++;
            }
        } else {
            ?>
        <?php
        }
        $arr['tbody'] = ob_get_contents();
        ob_clean();
        ?>
        <tr>
            <th></th>
            <th class="sticky-cell text-center">Total</th>
            <th></th>
            <th></th>
            <th></th>
            <th></th>
            <th class="text-right"><?= $this->nf($total['gaji_nominal']) ?></th>
            <th class="text-right"><?= $this->nf($total['tj_nominal']) ?></th>
            <th class="text-right"><?= $this->nf($total['tf_nominal']) ?></th>
            <th class="text-right"><?= $this->nf($total['t_nominal']) ?></th>
            <th class="text-right"><?= $this->nf($total['rs_bpjs_jkk']) ?></th>
            <th class="text-right"><?= $this->nf($total['rs_bpjs_pensiun']) ?></th>
            <th class="text-right"><?= $this->nf($total['rs_bpjs_kesehatan']) ?></th>
            <th class="text-right"><?= $this->nf($total['ti_nominal']) ?></th>
            <th class="text-right"><?= $this->nf($total['bruto']) ?></th>
            <th class="text-right"><?= $this->nf($total['bpjs_jkk']) ?></th>
            <th class="text-right"><?= $this->nf($total['rs_bpjs_jkk']) ?></th>
            <th class="text-right"><?= $this->nf($total['bpjs_pensiun']) ?></th>
            <th class="text-right"><?= $this->nf($total['rs_bpjs_pensiun']) ?></th>
            <th class="text-right"><?= $this->nf($total['pajak_rp']) ?></th>
            <th class="text-right"><?= $this->nf($total['bpjs_kesehatan']) ?></th>
            <th class="text-right"><?= $this->nf($total['rs_bpjs_kesehatan']) ?></th>
            <th class="text-right"><?= $this->nf($total['potongan']) ?></th>
            <th class="text-right"><?= $this->nf($total['netto']) ?></th>
        </tr>
<?php
        $arr['tfoot'] = ob_get_contents();
        ob_clean();
        echo json_encode($arr);
    }
}
