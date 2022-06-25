<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Gaji extends CI_Controller
{

    public function index()
    {

        if (!$this->session->has_userdata('logged_in')) {
            redirect('auth');
        }
        $setting = $this->MCore->get_setting();
        $data = [
            'title' => 'Gaji Karyawan',
            'nav_id' => 'nav_gaji',
            'opt_uk' => $this->opt_uk(),
            's' => $setting,
            'css' => array(
                'jquery.stickytable/jquery.stickytable.css',
            ),
            'js' => array(
                'jquery.stickytable/jquery.stickytable.js',
            )
        ];

        $this->template->view('VGaji', $data);
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
        $data = $this->MPayroll->get_rekap_gaji($uk_id);
        ob_start();
        $no = 1;
        foreach ($data as $value) {
?>
            <tr>
                <td><?= $no ?></td>
                <td class="sticky-cell">
                    <?= $value['us_nama'] . '<br><i class="fas fa-id-card-alt"></i> ' . $value['us_reg']  ?>
                </td>
                <td><?= date('d/m/Y', strtotime($value['us_mulai_kerja'])) . '<br><i class="fa fa-clock"></i> ' . $this->masa_kerja($value['us_mulai_kerja']) ?></td>
                <td><?= $value['g_nama'] ?></td>
                <td><?= $value['kp_nama'] ?></td>
                <td><?= rupiah($value['gaji_nominal']) ?></td>
                <td><?= rupiah($value['tj_nominal']) ?></td>
                <td><?= rupiah($value['tf_nominal']) ?></td>
                <td><?= rupiah($value['t_nominal']) ?></td>
                <td><?= rupiah($value['rs_bpjs_jkk']) ?></td>
                <td><?= rupiah($value['rs_bpjs_pensiun']) ?></td>
                <td><?= rupiah($value['rs_bpjs_kesehatan']) ?></td>
                <td><?= rupiah($value['bruto']) ?></td>
                <td><?= rupiah($value['bpjs_jkk']) ?></td>
                <td><?= rupiah($value['rs_bpjs_jkk']) ?></td>
                <td><?= rupiah($value['bpjs_pensiun']) ?></td>
                <td><?= rupiah($value['rs_bpjs_pensiun']) ?></td>
                <td><?= rupiah($value['pajak_rp']) ?></td>
                <td><?= rupiah($value['bpjs_kesehatan']) ?></td>
                <td><?= rupiah($value['rs_bpjs_kesehatan']) ?></td>
                <td><?= rupiah($value['potongan']) ?></td>
                <td><?= rupiah($value['netto']) ?></td>
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
}
