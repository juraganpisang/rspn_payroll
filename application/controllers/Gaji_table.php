<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Gaji_table extends RS_Controller
{

    public function index()
    {
        $header = $this->sendHeader('nav_gaji_table', 'Tabel Gaji');
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
            'opt_golongan' => $this->opt_golongan()
        ];
        $data = array_merge($header, $body);
        $this->template->view('VGaji_table', $data);
    }

    public function opt_golongan($select = '')
    {
        $sel = '<option value="">-Pilih-</option>';
        $data = $this->MCore->list_data('payroll_golongan', 'g_id');
        $arrdata = [];
        foreach ($data->result_array() as $value) {
            if ($value['g_id'] == 0) {
                $arrdata['PTT'] = 'PTT';
                continue;
            }
            $init = substr($value['g_nama'], 0, 1);
            if (!array_key_exists($init, $arrdata)) {
                $arrdata[$init] = 'Golongan ' . $init;
            }
        }
        foreach ($arrdata as $key => $value) {
            $selected = $select == $value ? 'selected=""' : '';
            $sel .= '<option ' . $selected . ' value="' . $key . '">' . $value . '</option>';
        }
        return $sel;
    }



    public function list_($tahun = 0, $g_nama)
    {
        $this->load->model('MPayroll');
        $data = $this->MPayroll->get_table_gaji($tahun, $g_nama);
        ob_start();
?>
        <tr>
            <th rowspan="2" class="text-center">Masa Kerja</th>
            <th colspan="<?= count($data) ?>" class="text-center">Golongan</th>
        </tr>
        <tr>
            <?php
            $arrgol = $list = [];
            foreach ($data as $key => $value) { ?>
                <th class="text-center"><?= $value['g_nama'] ?></th>
            <?php
                $arrgol[] = $value['g_id'];
                for ($i = 0; $i < count($data[$value['g_id']]['list']); $i++) {
                    $list[$i][$value['g_id']] = $data[$value['g_id']]['list'][$i];
                }
            } ?>
        </tr>
        <?php
        $arr['thead'] = ob_get_contents();
        ob_clean();
        foreach ($list as $th_kerja => $value) {
        ?>
            <tr>
                <td class="text-center"><?= $th_kerja ?></td>
                <?php foreach ($arrgol as $g_id) { ?>
                    <td class="text-right"><?= $this->nf($value[$g_id]) ?></td>
                <?php } ?>
            </tr>
<?php
        }
        $arr['tbody'] = ob_get_contents();
        ob_clean();
        echo json_encode($arr);
    }
}
