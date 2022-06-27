<?php
defined('BASEPATH') or exit('No direct script access allowed');

use PhpOffice\PhpSpreadsheet\Spreadsheet as spreadsheet; // instead PHPExcel
use PhpOffice\PhpSpreadsheet\Writer\Xlsx as xlsx; // Instead PHPExcel_Writer_Excel2007
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing as drawing; // Instead PHPExcel_Worksheet_Drawing
use PhpOffice\PhpSpreadsheet\Style\Alignment as alignment; // Instead PHPExcel_Style_Alignment
use PhpOffice\PhpSpreadsheet\Style\Fill as fill; // Instead PHPExcel_Style_Fill
use PhpOffice\PhpSpreadsheet\Style\Border as border_;
use PhpOffice\PhpSpreadsheet\Style\Color as color_; //Instead PHPExcel_Style_Color
use PhpOffice\PhpSpreadsheet\Worksheet\PageSetup as pagesetup; // Instead PHPExcel_Worksheet_PageSetup
use PhpOffice\PhpSpreadsheet\IOFactory as io_factory; // Instead PHPExcel_IOFactory

class Golongan extends CI_Controller
{

    public function index()
    {

        if (!$this->session->has_userdata('logged_in')) {
            redirect('auth');
        }

        $data = [
            'title' => 'Master Golongan',
            'nav_id' => 'nav_golongan',
            'tbody' => $this->list_(),
        ];

        $this->template->view('VGolongan', $data);
    }


    private function list_()
    {

        $data = $this->MCore->list_data('payroll_golongan');

        $no = 1;
        ob_start();
        foreach ($data->result_array() as $value) {

            $button = '
            <button id="btn-detail" type="button" data-toggle="tooltip" data-id="' . $value['g_id'] . '" title="" class="btn btn-link btn-simple-primary btn-lg" data-original-title="Detail">
            <i class="fa fa-info text-secondary"></i></button>
                <button id="btn-edit" type="button" data-toggle="tooltip" data-id="' . $value['g_id'] . '" title="" class="btn btn-link btn-simple-primary btn-lg" data-original-title="Edit">
            <i class="fa fa-edit"></i></button>';
?>
            <tr class="mb-2">
                <td class="text-center"><?= $no ?></td>
                <td><?= $value['g_nama'] ?></td>
                <td><?= $value['g_pendidikan'] ?></td>
                <td><?= $value['g_keterangan'] ?></td>
                <td class="text-center">
                    <?= $button; ?>
                </td>
            </tr>
        <?php
            $no++;
        }
        $tbody = ob_get_contents();
        ob_clean();
        return $tbody;
    }

    public function edit($id = 0)
    {
        $data = $this->MCore->get_data('payroll_golongan', 'g_id = ' . $id);

        echo json_encode($data->row_array());
    }

    public function detail($id = 0)
    {

        $option = array(
            'select'    => 'g.g_id, g.g_nama, g.g_pendidikan, g.g_keterangan, gd.gd_tahun, gd.gd_tahun_kerja, gd.gd_nominal',
            'table'     => 'payroll_golongan g',
            'join'      => array('payroll_golongan_detail gd' => 'g.g_id = gd.gd_golongan_id'),
            'where'     => 'g.g_id = ' . $id
        );

        $data = $this->MCore->join_table($option)->result_array();

        ob_start();
        ?>

        <input type="hidden" id="id" name="id" value="<?= $data[0]['g_id']; ?>">
        <h4>Data Golongan <?= $data[0]['g_nama']; ?></h4>
        <div class="mb-2">
            <div class="row">
                <div class="col-4">
                    Pendidikan
                </div>
                <div class="col">
                    : <?= $data[0]['g_pendidikan'] ?>
                </div>
            </div>
            <div class="row">
                <div class="col-4">
                    Jenis Pekerjaan
                </div>
                <div class="col">
                    : <?= $data[0]['g_keterangan'] ?>
                </div>
            </div>
            <div class="row">
                <div class="col-4">
                    Tahun
                </div>
                <div class="col">
                    : <?= $data[0]['gd_tahun'] ?>
                </div>
            </div>
        </div>
        <h4>Standard Gaji</h4>
        <table class="display table table-bordered table-sm">
            <tr>
                <td>Masa Kerja</td>
                <td>Golongan <?= $data[0]['g_nama']; ?></td>
            </tr>
            <tr>
                <td>Golongan</td>
                <td rowspan="2">Ruang dan Kenaikan Gaji Pokok</td>
            </tr>
            <tr>
                <td>Tahun</td>
            </tr>
            <tr>
                <td></td>
                <td>Nominal</td>
            </tr>
            <?php
            foreach ($data as $row) {
            ?>
                <tr>
                    <td><?= $row['gd_tahun_kerja'] ?></td>
                    <td class="font-weight-bold"><?= rupiah($row['gd_nominal']) ?></td>
                </tr>
            <?php
            }
            ?>

        </table>
<?php
        $tbody = ob_get_contents();
        ob_clean();
        echo json_encode($tbody);
    }

    public function edit_detail($id = 0)
    {

        if (!$this->session->has_userdata('logged_in')) {
            redirect('auth');
        }

        $option = array(
            'select'    => 'g.g_id, gd.gd_id, g.g_nama, g.g_pendidikan, g.g_keterangan, gd.gd_tahun, gd.gd_tahun_kerja, gd.gd_nominal',
            'table'     => 'payroll_golongan g',
            'join'      => array('payroll_golongan_detail gd' => 'g.g_id = gd.gd_golongan_id'),
            'where'     => 'g.g_id = ' . $id
        );

        $detail = $this->MCore->join_table($option)->result_array();

        $data = [
            'title' => 'Master Golongan',
            'nav_id' => 'nav_golongan',
            'detail' => $detail,
            'js' => array(
                'maskmoney/jquery.maskMoney.min.js',
            )
        ];

        $this->template->view('VGolonganDetail', $data);
    }

    public function save_detail()
    {

        $this->load->library('form_validation');
        $this->form_validation->set_rules('id[]', 'Golongan', 'trim|required');

        $this->form_validation->set_message('required', '{field} tidak boleh kosong.');

        if ($this->form_validation->run() == FALSE) {
            $errors = $this->form_validation->error_array();
            $arr['status'] = 0;
            $arr['message'] = reset($errors);
            echo json_encode($arr);
            exit();
        }

        $id = $this->input->post('id');
        $nominal = str_replace(',', '', $this->input->post('nominal'));

        $date = date('Y-m-d H:i:s');

        for ($i = 0; $i < count($id); $i++) {

            $data = array(
                'gd_nominal' => $nominal[$i],
                'gd_updated_at' => $date
            );

            $sql = $this->MCore->save_data('payroll_golongan_detail', $data, true, array('gd_id' => $id[$i]));
        }

        $arr['status'] = $sql;
        if ($sql) {
            $id_gol = $this->input->post('id_golongan');
            $detail = $this->MCore->get_data("payroll_golongan_detail", 'gd_golongan_id = '.$id_gol)->result_array();
            
            $arr['message'] = 'Data berhasil disimpan';
            $arr['detail'] = $detail;

        } else {
            $arr['message'] = 'Data gagal disimpan';
        }
        echo json_encode($arr);
    }

    public function save()
    {

        $this->load->library('form_validation');
        $this->form_validation->set_rules('nama', 'Nama jabatan', 'trim|required');
        $this->form_validation->set_rules('pendidikan', 'Nominal Baru', 'trim|required');
        $this->form_validation->set_rules('keterangan', 'Nominal Lama', 'trim|required');

        $this->form_validation->set_message('required', '{field} tidak boleh kosong.');

        if ($this->form_validation->run() == FALSE) {
            $errors = $this->form_validation->error_array();
            $arr['status'] = 0;
            $arr['message'] = reset($errors);
            echo json_encode($arr);
            exit();
        }

        $id = $this->input->post('id');

        $date = date('Y-m-d H:i:s');
        $data = array(
            'g_nama' => $this->input->post('nama'),
            'g_pendidikan' => $this->input->post('pendidikan'),
            'g_keterangan' => $this->input->post('keterangan')
        );


        if ($id == '') {
            if ($this->MCore->get_data('payroll_golongan', array('LOWER(g_nama)' => strtolower($data['g_nama'])))->num_rows() > 0) {
                $arr['status'] = 0;
                $arr['message'] = 'Nama telah digunakan';
                echo json_encode($arr);
                exit();
            }

            $data['g_id'] = $this->MCore->get_newid('payroll_golongan', 'g_id');
            $data['g_created_at'] = $date;
            $sql = $this->MCore->save_data('payroll_golongan', $data);
        } else {

            $data['g_updated_at'] = $date;
            $sql = $this->MCore->save_data('payroll_golongan', $data, true, array('g_id' => $id));
        }
        $arr['status'] = $sql;
        if ($sql) {
            $arr['message'] = 'Data berhasil disimpan';
            $arr['tbody'] = $this->list_();
        } else {
            $arr['message'] = 'Data gagal disimpan';
        }
        echo json_encode($arr);
    }

    public function export_excel()
    {
        //unlimited
        ini_set('max_execution_time', 0);
        // load excel library
        $spreadsheet = new spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        $listInfo = $this->MCore->list_data('payroll_golongan')->result();

        //set logo
        $objDrawing = new drawing();
        $objDrawing->setName('Logo RSPN');
        $objDrawing->setPath('./assets/img/logo_pantinirmala_panjang.png');
        $objDrawing->setCoordinates('A1');
        //setOffsetX works properly
        $objDrawing->setOffsetX(10);
        $objDrawing->setOffsetY(1);
        //set width, height
        $objDrawing->setWidth(50);
        $objDrawing->setHeight(50);
        $objDrawing->setWorksheet($sheet);

        $sheet->getRowDimension('1')->setRowHeight(40);

        // set header
        $sheet->SetCellValue('A5', 'No');
        $sheet->SetCellValue('B5', 'Nama Golongan');
        $sheet->SetCellValue('C5', 'Pendidikan');
        $sheet->SetCellValue('D5', 'Keterangan');
        $sheet->getRowDimension('5')->setRowHeight(20);

        $judul = 'Data Golongan';

        $sheet->SetCellValue('A2', $judul);
        $sheet->getStyle("A2")->getFont()->setSize(18);
        $sheet->getStyle("A2")->getAlignment()->setVertical(alignment::VERTICAL_CENTER);
        $sheet->mergeCells('A2:C2');

        $sheet->SetCellValue('A3', "Tanggal : ");
        $sheet->SetCellValue('B3', date('d-m-Y H:i:s'));
        // set Row
        $rowCount = 7;
        $no = 1;

        foreach ($listInfo as $list) {

            $sheet->SetCellValue('A' . $rowCount, $no);
            $sheet->SetCellValue('B' . $rowCount, $list->g_nama);
            $sheet->SetCellValue('C' . $rowCount, $list->g_pendidikan);
            $sheet->SetCellValue('D' . $rowCount, $list->g_keterangan);

            $no++;
            $rowCount++;
        }

        // SIZE WIDTH
        $sheet->getColumnDimension('B')->setWidth(12);
        $sheet->getColumnDimension('C')->setWidth(12);
        $sheet->getColumnDimension('D')->setWidth(30);

        // TABEL
        $styleArray = array(
            'borders' => array(
                'allBorders' => array(
                    'borderStyle' => border_::BORDER_THIN,
                    'color' => array('argb' => '00000000'),
                ),
            )
        );
        $sheet->getStyle("A5:D" . ($rowCount - 1))->applyFromArray($styleArray);

        // ini untuk style header
        $from = "A5"; // or any value
        $to =  "D5"; // or any value
        //style
        $style_cell = array(
            'alignment' => array(
                'horizontal' => alignment::HORIZONTAL_CENTER,
                'vertical' => alignment::VERTICAL_CENTER,
            ),
            'fill' => array(
                'fillType' => fill::FILL_SOLID,
                'color' => array('argb' => '1269db')
            ),
            'font' => [
                'size' => 12,
                'bold' => true,
                'color' => array('rgb' => 'FFFFFF')
            ]
        );
        $sheet->getStyle("$from:$to")->applyFromArray($style_cell);

        // Wrap text
        $sheet->getStyle('D6:D' . ($rowCount - 1))->getAlignment()->setWrapText(true);

        // create file name
        $filename = $judul . ' #' . date("YmdHis") . ".xlsx";

        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="' . $filename . '"');
        header('Cache-Control: max-age=0');

        $writer = new xlsx($spreadsheet);
        $writer->save('php://output');
    }
}
