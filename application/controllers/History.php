<?php
defined('BASEPATH') or exit('No direct script access allowed');

class History extends RS_Controller
{

    public function index()
    {
        $header = $this->sendHeader('nav_history', 'History');
        $header['css'] = array(
            'datepicker/css/tempusdominus-bootstrap-4.min.css',
            'select2/css/select2.min.css',
        );
        $header['js'] = array(
            'moment/moment.min.js',
            'datepicker/js/tempusdominus-bootstrap-4.min.js',
            'select2/js/select2.full.min.js',
        );
        $body = [];
        $data = array_merge($header, $body);
        $this->template->view('VHistory', $data);
    }

    public function list_($bulan = '', $tahun = '')
    {
        $arr_style = [
            'primary',
            'secondary',
            'success',
            'warning',
            'danger',
            'info',
        ];
        $history = $this->MCore->get_data('payroll_history', ['akses_user' => $this->session->userdata('user_fullname'), 'year(akses_waktu)' => $tahun, 'month(akses_waktu)' => $bulan], 'akses_waktu DESC');
        ob_start();
        foreach ($history->result_array() as $key => $value) {
            $tanggal = strtotime($value['akses_waktu']);
            $deskripsi = $value['deskripsi'];
            if (strpos($value['deskripsi'], '{') !== false) {
                $start = strpos($value['deskripsi'], '{');
                $end = strpos($value['deskripsi'], '}');
                $json = substr($value['deskripsi'], $start, $start + $end);
                $str_json = [];
                foreach (json_decode($json, true) as $key => $va) {
                    $attr = explode(';', $va);
                    $dt = $this->MCore->get_data($key, [$attr[0] => $attr[1]])->row_array();
                    $str_json[] = $dt[$attr[2]];
                }
                $deskripsi = substr($value['deskripsi'], 0, $start) . implode(', ', $str_json);
            }
            $rand_style = $arr_style[rand(0, 5)];
?>
            <li class="feed-item <?= 'feed-item-' .  $rand_style ?>">
                <time class="date" datetime="<?= date('n-d', $tanggal) ?>"><?= $this->bulan[date('n', $tanggal)] . ' ' . date('d', $tanggal) ?><span class="float-right" style="font-size: .875em;"><i class="fas fa-clock"></i> <?= date('H:i:s', $tanggal) ?></span></time>
                <span class="text"><span class="badge <?= 'badge-' . $rand_style ?>"><?= $this->cmenu($value['akses_menu']) ?></span>&nbsp;<?= $deskripsi ?></span>
            </li>
<?php
        }
        $arr['result'] = ob_get_contents();
        ob_clean();
        echo json_encode($arr);
    }

    public function cmenu($link = '')
    {
        switch ($link) {
            case 'gaji_generate':
                $result = 'Gaji';
                break;
            case 'locker':
                $result = 'Locker';
                break;
            case 'punishment':
                $result = 'Punishment';
                break;
            case 'mutasi_slip':
                $result = 'Pindah Slip Gaji';
                break;
            case 'revisi_slip':
                $result = 'Revisi Slip Gaji';
                break;
            case 'catatan':
                $result = 'Catatan';
                break;
            case 'auth':
                $result = '<i class="fa fa-user-friends"></i>';
                break;
            default:
                $result = 'undefined';
                break;
        }
        return $result;
    }
}
