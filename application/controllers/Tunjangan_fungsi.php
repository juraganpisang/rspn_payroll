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

class Tunjangan_fungsi extends RS_Controller
{

    public function index()
    {
        $header = $this->sendHeader('nav_tunjangan_fungsi', 'Tunjangan Fungsi');
        $header['js'] = array('maskmoney/jquery.maskMoney.min.js');
        $body = [
            'tbody' => $this->list_()
        ];
        $data = array_merge($header, $body);
        $this->template->view('VTunjangan_fungsi', $data);
    }


    private function list_()
    {

        $data = $this->MCore->list_data('payroll_tunjangan_fungsi');

        $no = 1;
        ob_start();
        foreach ($data->result_array() as $value) {

            $status = '<span class="badge badge-primary">Aktif</span>';

            $button = '';
            if ($value['tf_status'] == 0) {
                $status = '<span class="badge badge-danger">Tidak Aktif</span>';
                $button .= '
                <button id="btn-aktif" data-id="' . $value['tf_id'] . '" type="button" data-toggle="tooltip" title="" class="btn btn-link btn-simple-danger" data-original-title="Aktifkan">
                    <i class="fa fa-check text-success"></i>
                </button>';
            } else {
                $button .= '
                <button id="btn-edit" type="button" data-toggle="tooltip" data-id="' . $value['tf_id'] . '" title="" class="btn btn-link btn-simple-primary btn-lg" data-original-title="Edit">
            <i class="fa fa-edit"></i></button>
                <button id="btn-nonaktif" data-id="' . $value['tf_id'] . '" type="button" data-toggle="tooltip" title="" class="btn btn-link btn-simple-danger" data-original-title="Nonaktifkan">
                    <i class="fa fa-times text-danger"></i>
                </button>';
            }
?>
            <tr class="mb-2">
                <td class="text-center"><?= $no ?></td>
                <td><?= $status ?></td>
                <td><?= $value['tf_nama'] ?></td>
                <td><?= $value['tf_keterangan'] ?></td>
                <td>
                    <span>0 - 16 Thn : <br> <b><?= rupiah($value['tf_baru']) ?></b></span>
                    <div class="py-1" style="border-top:1px solid #dddddd;"> </div>
                    <span>> 16 TH : <br> <b><?= rupiah($value['tf_lama']) ?></b></span>
                </td>
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
        $data = $this->MCore->get_data('payroll_tunjangan_fungsi', 'tf_id = ' . $id);

        echo json_encode($data->row_array());
    }

    public function save()
    {
        $id = $this->input->post('id');
        $baru = str_replace(',','', $this->input->post('baru'));
        $lama = str_replace(',','', $this->input->post('lama'));

        $this->load->library('form_validation');
        $this->form_validation->set_rules('nama', 'Nama Golongan', 'trim|required|max_length[50]');
        $this->form_validation->set_rules('keterangan', 'Keterangan', 'trim|required');
        $this->form_validation->set_rules('baru', 'Nominal Baru', 'trim|required');
        $this->form_validation->set_rules('lama', 'Nominal Lama', 'trim|required');

        $this->form_validation->set_message('required', '{field} tidak boleh kosong.');
        $this->form_validation->set_message('matches', 'Password harus sama.');
        $this->form_validation->set_message('max_length', '{field} melebihi {param} karakter.');
        $this->form_validation->set_message('greater_than', '{field} harus lebih dari 0.');

        if ($this->form_validation->run() == FALSE) {
            $errors = $this->form_validation->error_array();
            $arr['status'] = 0;
            $arr['message'] = reset($errors);
            echo json_encode($arr);
            exit();
        }

        $date = date('Y-m-d H:i:s');
        $data = array(
            'tf_nama' => $this->input->post('nama'),
            'tf_keterangan' => $this->input->post('keterangan'),
            'tf_baru' => $baru,
            'tf_lama' => $lama
        );


        if ($id == '') {
            if ($this->MCore->get_data('payroll_tunjangan_fungsi', array('LOWER(tf_nama)' => strtolower($data['tf_nama'])))->num_rows() > 0) {
                $arr['status'] = 0;
                $arr['message'] = 'Nama telah digunakan';
                echo json_encode($arr);
                exit();
            }

            $data['tf_id'] = $this->MCore->get_newid('payroll_tunjangan_fungsi', 'tf_id');
            $data['tf_created_at'] = $date;
            $sql = $this->MCore->save_data('payroll_tunjangan_fungsi', $data);
        } else {

            $data['tf_updated_at'] = $date;
            $sql = $this->MCore->save_data('payroll_tunjangan_fungsi', $data, true, array('tf_id' => $id));
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

        $listInfo = $this->MCore->list_data('payroll_tunjangan_fungsi')->result();

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
        $sheet->mergeCells('A5:A6');
        $sheet->SetCellValue('B5', 'Status');
        $sheet->mergeCells('B5:B6');
        $sheet->SetCellValue('C5', 'Fungsional');
        $sheet->mergeCells('C5:C6');
        $sheet->SetCellValue('D5', 'Nominal');
        $sheet->mergeCells('D5:E5');
        $sheet->SetCellValue('D6', 'Masa Kerja 0-16 Tahun');
        $sheet->SetCellValue('E6', 'Masa Kerja > 16 Tahun');
        $sheet->getRowDimension('5')->setRowHeight(20);
        $sheet->getRowDimension('6')->setRowHeight(20);

        $judul = 'Data Tunjangan Fungsional';

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
            switch ($list->tf_status) {
                case 0:
                    $status = 'Tidak Aktif';
                    break;
                case 1:
                    $status = "Aktif";
                    break;
            }
            $sheet->SetCellValue('B' . $rowCount, $status);
            $sheet->SetCellValue('C' . $rowCount, $list->tf_nama);
            $sheet->SetCellValue('D' . $rowCount, rupiah($list->tf_baru));
            $sheet->SetCellValue('E' . $rowCount, rupiah($list->tf_lama));

            $no++;
            $rowCount++;
        }

        // SIZE WIDTH
        $sheet->getColumnDimension('B')->setWidth(12);
        $sheet->getColumnDimension('C')->setWidth(30);
        $sheet->getColumnDimension('D')->setWidth(30);
        $sheet->getColumnDimension('E')->setWidth(30);

        // TABEL
        $styleArray = array(
            'borders' => array(
                'allBorders' => array(
                    'borderStyle' => border_::BORDER_THIN,
                    'color' => array('argb' => '00000000'),
                ),
            )
        );
        $sheet->getStyle("A5:E" . ($rowCount - 1))->applyFromArray($styleArray);

        // ini untuk style header
        $from = "A5"; // or any value
        $to =  "E6"; // or any value
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

        // create file name
        $filename = $judul . ' #' . date("YmdHis") . ".xlsx";

        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="' . $filename . '"');
        header('Cache-Control: max-age=0');

        $writer = new xlsx($spreadsheet);
        $writer->save('php://output');
    }

    public function aktif($id = 0)
    {
        $sql = $this->MCore->save_data('payroll_tunjangan_fungsi', array('tf_status' => 1), true, array('tf_id' => $id));

        $arr['status'] = $sql;
        if ($sql) {
            $arr['message'] = 'Data berhasil diaktifkan';
            $arr['tbody'] = $this->list_();
        } else {
            $arr['message'] = 'Data gagal diaktifkan';
        }
        echo json_encode($arr);
    }

    public function nonaktif($id = 0)
    {
        $sql = $this->MCore->save_data('payroll_tunjangan_fungsi', array('tf_status' => 0), true, array('tf_id' => $id));

        $arr['status'] = $sql;
        if ($sql) {
            $arr['message'] = 'Data berhasil dinonaktifkan';
            $arr['tbody'] = $this->list_();
        } else {
            $arr['message'] = 'Data gagal dinonaktifkan';
        }
        echo json_encode($arr);
    }
}
