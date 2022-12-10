<?php
defined('BASEPATH') or exit('No direct script access allowed');

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use \PhpOffice\PhpSpreadsheet\Cell\Cell;
use \PhpOffice\PhpSpreadsheet\Cell\DataType;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Style;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Border;

class Lap_gaji_unit extends RS_Controller
{

    public function index()
    {
        $header = $this->sendHeader('nav_l_gaji_unit', 'Laporan Gaji per Unit', 'overlay-sidebar');
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
            's' => $setting
        ];
        $data = array_merge($header, $body);
        $this->template->view('laporan/VLapgajiunit', $data);
    }

    public function list_($bulan = '', $tahun = '')
    {
        if ($tahun == '') {
            $tahun = date('Y');
        }
        if ($bulan == '') {
            $bulan = date('m');
        }
        $this->load->model('MPayroll');
        $data = $this->MPayroll->get_slip_gaji_unit($tahun, $bulan);
        ob_start();
        $total = [
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
        if (count($data) > 0) {
            $no = 1;
            foreach ($data as $value) {
?>
                <tr>
                    <td><?= $no ?></td>
                    <td class="sticky-cell">
                        <?= $value['sl_nama']  ?>
                    </td>
                    <td class="text-center"><?= $value['jml_peg'] ?></td>
                    <td class="text-right"><?= $this->nf($value['gaji_nominal'] + $value['tj_nominal'] + $value['tf_nominal'] + $value['t_nominal'] + $value['ti_nominal']) ?></td>
                    <!-- <td class="text-right"><?= $this->nf($value['tj_nominal']) ?></td>
                    <td class="text-right"><?= $this->nf($value['tf_nominal']) ?></td>
                    <td class="text-right"><?= $this->nf($value['t_nominal']) ?></td> -->
                    <td class="text-right"><?= $this->nf($value['rs_bpjs_jkk']) ?></td>
                    <td class="text-right"><?= $this->nf($value['rs_bpjs_pensiun']) ?></td>
                    <td class="text-right"><?= $this->nf($value['rs_bpjs_kesehatan']) ?></td>
                    <!-- <td class="text-right"><?= $this->nf($value['ti_nominal']) ?></td> -->
                    <td class="text-right"><?= $this->nf($value['bruto']) ?></td>
                    <td class="text-right"><?= $this->nf($value['bpjs_jkk'] + $value['rs_bpjs_jkk']) ?></td>
                    <!-- <td class="text-right"><?= $this->nf($value['rs_bpjs_jkk']) ?></td> -->
                    <td class="text-right"><?= $this->nf($value['bpjs_pensiun'] + $value['rs_bpjs_pensiun']) ?></td>
                    <!-- <td class="text-right"><?= $this->nf($value['rs_bpjs_pensiun']) ?></td> -->
                    <td class="text-right"><?= $this->nf($value['pajak_rp']) ?></td>
                    <td class="text-right"><?= $this->nf($value['bpjs_kesehatan'] + $value['rs_bpjs_kesehatan']) ?></td>
                    <!-- <td class="text-right"><?= $this->nf($value['rs_bpjs_kesehatan']) ?></td> -->
                    <td class="text-right"><?= $this->nf($value['potongan']) ?></td>
                    <td class="text-right"><?= $this->nf($value['netto']) ?></td>
                </tr>
            <?php
                $total['jml_peg'] += $value['jml_peg'];
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
            <th class="text-center"><?= $total['jml_peg'] ?></th>
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

    public function excel($bulan, $tahun)
    {
        $s = $this->MCore->get_setting();
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        $titleStyle = array(
            'font' => array(
                'bold' => true,
                'size' => 13,
                'name' => 'Arial'
            ),
            'alignment' => array(
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER,
                'rotation' => 0
            )
        );
        $titlesubStyle = array(
            'font' => array(
                'bold' => false,
                'size' => 10,
                'name' => 'Arial'
            ),
            'alignment' => array(
                'horizontal' => Alignment::HORIZONTAL_LEFT,
                'vertical' => Alignment::VERTICAL_CENTER,
                'rotation' => 0
            )
        );
        $normalStyle = array(
            'font' => array(
                'bold' => false,
                'size' => 10,
                'name' => 'Arial'
            ),
            'alignment' => array(
                'horizontal' => Alignment::HORIZONTAL_LEFT,
                'vertical' => Alignment::VERTICAL_CENTER,
                'rotation' => 0
            )
        );
        $headerStyle = array(
            'font' => array(
                'bold' => true,
                'size' => 10,
                'name' => 'Arial'
            ),
            'alignment' => array(
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER,
                'rotation' => 0
            ),
            'fill' => array(
                'fillType' => Fill::FILL_SOLID,
                'startColor' => array('argb' => 'FFEEEEEE')
            ),
            'borders' => array(
                'allBorders' => array(
                    'borderStyle' => Border::BORDER_THIN
                )
            )
        );
        $footerStyle = array(
            'font' => array(
                'bold' => true,
                'size' => 10,
                'name' => 'Arial'
            ),
            'alignment' => array(
                'rotation' => 0
            ),
            'fill' => array(
                'fillType' => Fill::FILL_SOLID,
                'startColor' => array('argb' => 'FFEEEEEE')
            ),
            'borders' => array(
                'allBorders' => array(
                    'borderStyle' => Border::BORDER_THIN
                )
            )
        );
        $bodyStyle = array(
            'font' => array(
                'size' => 10,
                'name' => 'Arial'
            ),
            'alignment' => array(
                'vertical' => Alignment::VERTICAL_TOP
            ),
            'borders' => array(
                'allBorders' => array(
                    'borderStyle' => Border::BORDER_THIN
                )
            )
        );
        $centerStyle = array(
            'font' => array(
                'size' => 10,
                'name' => 'Arial'
            ),
            'alignment' => array('horizontal' => Alignment::HORIZONTAL_CENTER),
            'borders' => array(
                'allBorders' => array(
                    'borderStyle' => Border::BORDER_THIN
                )
            )
        );
        $rightStyle = array(
            'font' => array(
                'size' => 10,
                'name' => 'Arial'
            ),
            'alignment' => array('horizontal' => Alignment::HORIZONTAL_RIGHT),
            'borders' => array(
                'allBorders' => array(
                    'borderStyle' => Border::BORDER_THIN
                )
            )
        );

        $this->load->model('MPayroll');
        $spreadsheet->getActiveSheet()->setTitle('Rekap');
        $sheet->SetCellValue('A1', "REKAPITULASI GAJI KARYAWAN RUMAH SAKIT PANTI NIRMALA MALANG - " . strtoupper($this->bulan[$bulan]) . " $tahun");
        $sheet->mergeCells('A1:N1');
        //header
        $sheet->SetCellValue('A3', 'NO');
        $sheet->SetCellValue('B3', 'BAGIAN');
        $sheet->SetCellValue('C3', 'TK');
        $sheet->SetCellValue('D3', 'POKOK / TUNJ');
        $sheet->SetCellValue('E3', 'BPJS Naker ' . $s['persen_rs_bpjs_jkk'] . '% (jkk jht jkm)');
        $sheet->SetCellValue('F3', 'BPJS Naker ' . $s['persen_rs_bpjs_pensiun'] . '% (pensiun)');
        $sheet->SetCellValue('G3', 'BPJS Kes ' . $s['persen_rs_bpjs_kesehatan'] . '%');
        $sheet->SetCellValue('H3', 'JML KOTOR');
        $sheet->SetCellValue('I3', 'BPJS Naker ' . ($s['persen_rs_bpjs_jkk'] + $s['persen_bpjs_jkk']) . '% (jkk jht jkm)');
        $sheet->SetCellValue('J3', 'BPJS Naker ' . ($s['persen_rs_bpjs_pensiun'] + $s['persen_bpjs_pensiun']) . '% (pensiun)');
        $sheet->SetCellValue('K3', 'PAJAK');
        $sheet->SetCellValue('L3', 'BPJS Kes ' . ($s['persen_rs_bpjs_kesehatan'] + $s['persen_bpjs_kesehatan']) . '%');
        $sheet->SetCellValue('M3', 'JUML.POT');
        $sheet->SetCellValue('N3', 'JML. BERSIH');
        //body
        $data = $this->MPayroll->get_excel_slip_gaji_unit($tahun, $bulan);
        $no = 1;
        $row = $row_body = 4;
        $total = [
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
        foreach ($data['rekap'] as $key => $value) {
            $sheet->SetCellValue("A$row", $no);
            $sheet->SetCellValue("B$row", $value['sl_nama']);
            $sheet->getStyle("C$row")->getNumberFormat()->setFormatCode('###,###,###');
            $sheet->setCellValueExplicit("C$row", $value['jml_peg'], DataType::TYPE_NUMERIC);
            $sheet->getStyle("D$row")->getNumberFormat()->setFormatCode('###,###,###');
            $sheet->setCellValueExplicit("D$row", $value['gaji_nominal'] + $value['tj_nominal'] + $value['tf_nominal'] + $value['t_nominal'] + $value['ti_nominal'], DataType::TYPE_NUMERIC);
            $sheet->getStyle("E$row")->getNumberFormat()->setFormatCode('###,###,###');
            $sheet->setCellValueExplicit("E$row", $value['rs_bpjs_jkk'], DataType::TYPE_NUMERIC);
            $sheet->getStyle("F$row")->getNumberFormat()->setFormatCode('###,###,###');
            $sheet->setCellValueExplicit("F$row", $value['rs_bpjs_pensiun'], DataType::TYPE_NUMERIC);
            $sheet->getStyle("G$row")->getNumberFormat()->setFormatCode('###,###,###');
            $sheet->setCellValueExplicit("G$row", $value['rs_bpjs_kesehatan'], DataType::TYPE_NUMERIC);
            $sheet->getStyle("H$row")->getNumberFormat()->setFormatCode('###,###,###');
            $sheet->setCellValueExplicit("H$row", $value['bruto'], DataType::TYPE_NUMERIC);
            $sheet->getStyle("I$row")->getNumberFormat()->setFormatCode('###,###,###');
            $sheet->setCellValueExplicit("I$row", $value['bpjs_jkk'] + $value['rs_bpjs_jkk'], DataType::TYPE_NUMERIC);
            $sheet->getStyle("J$row")->getNumberFormat()->setFormatCode('###,###,###');
            $sheet->setCellValueExplicit("J$row", $value['bpjs_pensiun'] + $value['rs_bpjs_pensiun'], DataType::TYPE_NUMERIC);
            $sheet->getStyle("K$row")->getNumberFormat()->setFormatCode('###,###,###');
            $sheet->setCellValueExplicit("K$row", $value['pajak_rp'], DataType::TYPE_NUMERIC);
            $sheet->getStyle("L$row")->getNumberFormat()->setFormatCode('###,###,###');
            $sheet->setCellValueExplicit("L$row", $value['bpjs_kesehatan'] + $value['rs_bpjs_kesehatan'], DataType::TYPE_NUMERIC);
            $sheet->getStyle("M$row")->getNumberFormat()->setFormatCode('###,###,###');
            $sheet->setCellValueExplicit("M$row", $value['potongan'], DataType::TYPE_NUMERIC);
            $sheet->getStyle("N$row")->getNumberFormat()->setFormatCode('###,###,###');
            $sheet->setCellValueExplicit("N$row", $value['netto'], DataType::TYPE_NUMERIC);

            $total['jml_peg'] += $value['jml_peg'];
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
            $row++;
        }
        $row_body_end = $row - 1;
        $sheet->SetCellValue("A$row", 'Total');
        $sheet->mergeCells("A$row:B$row");
        $sheet->getStyle("C$row")->getNumberFormat()->setFormatCode('###,###,###');
        $sheet->setCellValueExplicit("C$row", $total['jml_peg'], DataType::TYPE_NUMERIC);
        $sheet->getStyle("D$row")->getNumberFormat()->setFormatCode('###,###,###');
        $sheet->setCellValueExplicit("D$row", $total['gaji_nominal'] + $total['tj_nominal'] + $total['tf_nominal'] + $total['t_nominal'] + $total['ti_nominal'], DataType::TYPE_NUMERIC);
        $sheet->getStyle("E$row")->getNumberFormat()->setFormatCode('###,###,###');
        $sheet->setCellValueExplicit("E$row", $total['rs_bpjs_jkk'], DataType::TYPE_NUMERIC);
        $sheet->getStyle("F$row")->getNumberFormat()->setFormatCode('###,###,###');
        $sheet->setCellValueExplicit("F$row", $total['rs_bpjs_pensiun'], DataType::TYPE_NUMERIC);
        $sheet->getStyle("G$row")->getNumberFormat()->setFormatCode('###,###,###');
        $sheet->setCellValueExplicit("G$row", $total['rs_bpjs_kesehatan'], DataType::TYPE_NUMERIC);
        $sheet->getStyle("H$row")->getNumberFormat()->setFormatCode('###,###,###');
        $sheet->setCellValueExplicit("H$row", $total['bruto'], DataType::TYPE_NUMERIC);
        $sheet->getStyle("I$row")->getNumberFormat()->setFormatCode('###,###,###');
        $sheet->setCellValueExplicit("I$row", $total['bpjs_jkk'] + $total['rs_bpjs_jkk'], DataType::TYPE_NUMERIC);
        $sheet->getStyle("J$row")->getNumberFormat()->setFormatCode('###,###,###');
        $sheet->setCellValueExplicit("J$row", $total['bpjs_pensiun'] + $total['rs_bpjs_pensiun'], DataType::TYPE_NUMERIC);
        $sheet->getStyle("K$row")->getNumberFormat()->setFormatCode('###,###,###');
        $sheet->setCellValueExplicit("K$row", $total['pajak_rp'], DataType::TYPE_NUMERIC);
        $sheet->getStyle("L$row")->getNumberFormat()->setFormatCode('###,###,###');
        $sheet->setCellValueExplicit("L$row", $total['bpjs_kesehatan'] + $total['rs_bpjs_kesehatan'], DataType::TYPE_NUMERIC);
        $sheet->getStyle("M$row")->getNumberFormat()->setFormatCode('###,###,###');
        $sheet->setCellValueExplicit("M$row", $total['potongan'], DataType::TYPE_NUMERIC);
        $sheet->getStyle("N$row")->getNumberFormat()->setFormatCode('###,###,###');
        $sheet->setCellValueExplicit("N$row", $total['netto'], DataType::TYPE_NUMERIC);

        $sheet->getColumnDimension('A')->setWidth(4);
        $sheet->getColumnDimension('B')->setWidth(23);
        $sheet->getColumnDimension('C')->setWidth(5);
        $sheet->getColumnDimension('D')->setWidth(16);
        $sheet->getColumnDimension('E')->setWidth(14);
        $sheet->getColumnDimension('F')->setWidth(14);
        $sheet->getColumnDimension('G')->setWidth(14);
        $sheet->getColumnDimension('H')->setWidth(16);
        $sheet->getColumnDimension('I')->setWidth(14);
        $sheet->getColumnDimension('J')->setWidth(14);
        $sheet->getColumnDimension('K')->setWidth(14);
        $sheet->getColumnDimension('L')->setWidth(14);
        $sheet->getColumnDimension('M')->setWidth(14);
        $sheet->getColumnDimension('N')->setWidth(16);

        $sheet->getStyle("A1:N1")->applyFromArray($titleStyle);
        $sheet->getStyle("A3:N3")->applyFromArray($headerStyle);
        $sheet->getStyle("A$row_body:N" . $row_body_end)->applyFromArray($bodyStyle);
        $sheet->getStyle("A$row_body:A$row_body_end")->applyFromArray($centerStyle);
        $sheet->getStyle("C$row_body:C$row")->applyFromArray($centerStyle);
        $sheet->getStyle("A3:N3")
            ->getAlignment()->setWrapText(true);
        $sheet->getStyle("B$row_body:B$row_body_end")
            ->getAlignment()->setWrapText(true);
        $sheet->getStyle("A$row:N$row")->applyFromArray($footerStyle);

        $spreadsheet->createSheet();
        $spreadsheet->setActiveSheetIndex(1);
        $sheet = $spreadsheet->getActiveSheet();
        $spreadsheet->getActiveSheet()->setTitle('Rekap PTT');
        $sheet->SetCellValue('A1', "REKAPITULASI GAJI PEGAWAI TIDAK TETAP RUMAH SAKIT PANTI NIRMALA MALANG");
        $sheet->mergeCells('A1:N1');
        $sheet->SetCellValue('A2', "DAFTAR GAJI BULAN : " . strtoupper($this->bulan[$bulan]) . " $tahun");
        $sheet->mergeCells('A2:N2');
        //header
        $sheet->SetCellValue('A4', 'NO');
        $sheet->SetCellValue('B4', 'BAGIAN');
        $sheet->SetCellValue('C4', 'TK');
        $sheet->SetCellValue('D4', 'POKOK / TUNJ');
        $sheet->SetCellValue('E4', 'BPJS Naker ' . $s['persen_rs_bpjs_jkk'] . '% (jkk jht jkm)');
        $sheet->SetCellValue('F4', 'BPJS Naker ' . $s['persen_rs_bpjs_pensiun'] . '% (pensiun)');
        $sheet->SetCellValue('G4', 'BPJS Kes ' . $s['persen_rs_bpjs_kesehatan'] . '%');
        $sheet->SetCellValue('H4', 'JML KOTOR');
        $sheet->SetCellValue('I4', 'BPJS Naker ' . ($s['persen_rs_bpjs_jkk'] + $s['persen_bpjs_jkk']) . '% (jkk jht jkm)');
        $sheet->SetCellValue('J4', 'BPJS Naker ' . ($s['persen_rs_bpjs_pensiun'] + $s['persen_bpjs_pensiun']) . '% (pensiun)');
        $sheet->SetCellValue('K4', 'PAJAK');
        $sheet->SetCellValue('L4', 'BPJS Kes ' . ($s['persen_rs_bpjs_kesehatan'] + $s['persen_bpjs_kesehatan']) . '%');
        $sheet->SetCellValue('M4', 'JUML.POT');
        $sheet->SetCellValue('N4', 'JML. BERSIH');
        // body
        $no = 1;
        $row = $row_body = 5;
        $total = [
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
        foreach ($data['ptt'] as $key => $value) {
            $sheet->SetCellValue("A$row", $no);
            $sheet->SetCellValue("B$row", $value['uk_nama']);
            $sheet->getStyle("C$row")->getNumberFormat()->setFormatCode('###,###,###');
            $sheet->setCellValueExplicit("C$row", $value['jml_peg'], DataType::TYPE_NUMERIC);
            $sheet->getStyle("D$row")->getNumberFormat()->setFormatCode('###,###,###');
            $sheet->setCellValueExplicit("D$row", $value['gaji_nominal'] + $value['tj_nominal'] + $value['tf_nominal'] + $value['t_nominal'] + $value['ti_nominal'], DataType::TYPE_NUMERIC);
            $sheet->getStyle("E$row")->getNumberFormat()->setFormatCode('###,###,###');
            $sheet->setCellValueExplicit("E$row", $value['rs_bpjs_jkk'], DataType::TYPE_NUMERIC);
            $sheet->getStyle("F$row")->getNumberFormat()->setFormatCode('###,###,###');
            $sheet->setCellValueExplicit("F$row", $value['rs_bpjs_pensiun'], DataType::TYPE_NUMERIC);
            $sheet->getStyle("G$row")->getNumberFormat()->setFormatCode('###,###,###');
            $sheet->setCellValueExplicit("G$row", $value['rs_bpjs_kesehatan'], DataType::TYPE_NUMERIC);
            $sheet->getStyle("H$row")->getNumberFormat()->setFormatCode('###,###,###');
            $sheet->setCellValueExplicit("H$row", $value['bruto'], DataType::TYPE_NUMERIC);
            $sheet->getStyle("I$row")->getNumberFormat()->setFormatCode('###,###,###');
            $sheet->setCellValueExplicit("I$row", $value['bpjs_jkk'] + $value['rs_bpjs_jkk'], DataType::TYPE_NUMERIC);
            $sheet->getStyle("J$row")->getNumberFormat()->setFormatCode('###,###,###');
            $sheet->setCellValueExplicit("J$row", $value['bpjs_pensiun'] + $value['rs_bpjs_pensiun'], DataType::TYPE_NUMERIC);
            $sheet->getStyle("K$row")->getNumberFormat()->setFormatCode('###,###,###');
            $sheet->setCellValueExplicit("K$row", $value['pajak_rp'], DataType::TYPE_NUMERIC);
            $sheet->getStyle("L$row")->getNumberFormat()->setFormatCode('###,###,###');
            $sheet->setCellValueExplicit("L$row", $value['bpjs_kesehatan'] + $value['rs_bpjs_kesehatan'], DataType::TYPE_NUMERIC);
            $sheet->getStyle("M$row")->getNumberFormat()->setFormatCode('###,###,###');
            $sheet->setCellValueExplicit("M$row", $value['potongan'], DataType::TYPE_NUMERIC);
            $sheet->getStyle("N$row")->getNumberFormat()->setFormatCode('###,###,###');
            $sheet->setCellValueExplicit("N$row", $value['netto'], DataType::TYPE_NUMERIC);

            $total['jml_peg'] += $value['jml_peg'];
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
            $row++;
        }
        $row_body_end = $row - 1;
        $sheet->SetCellValue("A$row", 'Total');
        $sheet->mergeCells("A$row:B$row");
        $sheet->getStyle("C$row")->getNumberFormat()->setFormatCode('###,###,###');
        $sheet->setCellValueExplicit("C$row", $total['jml_peg'], DataType::TYPE_NUMERIC);
        $sheet->getStyle("D$row")->getNumberFormat()->setFormatCode('###,###,###');
        $sheet->setCellValueExplicit("D$row", $total['gaji_nominal'] + $total['tj_nominal'] + $total['tf_nominal'] + $total['t_nominal'] + $total['ti_nominal'], DataType::TYPE_NUMERIC);
        $sheet->getStyle("E$row")->getNumberFormat()->setFormatCode('###,###,###');
        $sheet->setCellValueExplicit("E$row", $total['rs_bpjs_jkk'], DataType::TYPE_NUMERIC);
        $sheet->getStyle("F$row")->getNumberFormat()->setFormatCode('###,###,###');
        $sheet->setCellValueExplicit("F$row", $total['rs_bpjs_pensiun'], DataType::TYPE_NUMERIC);
        $sheet->getStyle("G$row")->getNumberFormat()->setFormatCode('###,###,###');
        $sheet->setCellValueExplicit("G$row", $total['rs_bpjs_kesehatan'], DataType::TYPE_NUMERIC);
        $sheet->getStyle("H$row")->getNumberFormat()->setFormatCode('###,###,###');
        $sheet->setCellValueExplicit("H$row", $total['bruto'], DataType::TYPE_NUMERIC);
        $sheet->getStyle("I$row")->getNumberFormat()->setFormatCode('###,###,###');
        $sheet->setCellValueExplicit("I$row", $total['bpjs_jkk'] + $total['rs_bpjs_jkk'], DataType::TYPE_NUMERIC);
        $sheet->getStyle("J$row")->getNumberFormat()->setFormatCode('###,###,###');
        $sheet->setCellValueExplicit("J$row", $total['bpjs_pensiun'] + $total['rs_bpjs_pensiun'], DataType::TYPE_NUMERIC);
        $sheet->getStyle("K$row")->getNumberFormat()->setFormatCode('###,###,###');
        $sheet->setCellValueExplicit("K$row", $total['pajak_rp'], DataType::TYPE_NUMERIC);
        $sheet->getStyle("L$row")->getNumberFormat()->setFormatCode('###,###,###');
        $sheet->setCellValueExplicit("L$row", $total['bpjs_kesehatan'] + $total['rs_bpjs_kesehatan'], DataType::TYPE_NUMERIC);
        $sheet->getStyle("M$row")->getNumberFormat()->setFormatCode('###,###,###');
        $sheet->setCellValueExplicit("M$row", $total['potongan'], DataType::TYPE_NUMERIC);
        $sheet->getStyle("N$row")->getNumberFormat()->setFormatCode('###,###,###');
        $sheet->setCellValueExplicit("N$row", $total['netto'], DataType::TYPE_NUMERIC);

        $sheet->getColumnDimension('A')->setWidth(4);
        $sheet->getColumnDimension('B')->setWidth(23);
        $sheet->getColumnDimension('C')->setWidth(5);
        $sheet->getColumnDimension('D')->setWidth(16);
        $sheet->getColumnDimension('E')->setWidth(14);
        $sheet->getColumnDimension('F')->setWidth(14);
        $sheet->getColumnDimension('G')->setWidth(14);
        $sheet->getColumnDimension('H')->setWidth(16);
        $sheet->getColumnDimension('I')->setWidth(14);
        $sheet->getColumnDimension('J')->setWidth(14);
        $sheet->getColumnDimension('K')->setWidth(14);
        $sheet->getColumnDimension('L')->setWidth(14);
        $sheet->getColumnDimension('M')->setWidth(14);
        $sheet->getColumnDimension('N')->setWidth(16);

        $sheet->getStyle("A1:N2")->applyFromArray($titleStyle);
        $sheet->getStyle("A4:N4")->applyFromArray($headerStyle);
        $sheet->getStyle("A$row_body:N" . $row_body_end)->applyFromArray($bodyStyle);
        $sheet->getStyle("A$row_body:A$row_body_end")->applyFromArray($centerStyle);
        $sheet->getStyle("C$row_body:C$row")->applyFromArray($centerStyle);
        $sheet->getStyle("A4:N4")
            ->getAlignment()->setWrapText(true);
        $sheet->getStyle("B$row_body:B$row_body_end")
            ->getAlignment()->setWrapText(true);
        $sheet->getStyle("A$row:N$row")->applyFromArray($footerStyle);

        $isheet = 2;
        $slip = $this->MCore->list_data('payroll_lap_slip_gaji', 'sl_urut');
        foreach ($slip->result_array() as $key => $mapping) {
            $spreadsheet->createSheet();
            $spreadsheet->setActiveSheetIndex($isheet);
            $sheet = $spreadsheet->getActiveSheet();
            $spreadsheet->getActiveSheet()->setTitle($mapping['sl_nama']);
            $sheet->SetCellValue('A1', "DAFTAR GAJI BULAN : " . strtoupper($this->bulan[$bulan]) . " $tahun");
            $sheet->mergeCells('A1:N1');
            $sheet->SetCellValue('A3', "BAGIAN : " . $mapping['sl_nama']);
            $sheet->mergeCells('A3:N3');
            //header
            $sheet->SetCellValue('A4', 'No');
            $sheet->mergeCells('A4:A6');
            $sheet->SetCellValue('B4', 'Reg.');
            $sheet->mergeCells('B4:B6');
            $sheet->SetCellValue('C4', 'Nama Pegawai');
            $sheet->mergeCells('C4:C6');
            $sheet->SetCellValue('D4', 'Bagian / Jabatan');
            $sheet->mergeCells('D4:D6');
            $sheet->SetCellValue('E4', 'Pend');
            $sheet->mergeCells('E4:E6');
            $sheet->SetCellValue('F4', 'Tgl. Mulai Bekerja');
            $sheet->mergeCells('F4:F6');
            $sheet->SetCellValue('G4', 'Masa Kerja');
            $sheet->mergeCells('G4:H6');
            $sheet->SetCellValue('I4', 'Gol');            
            $sheet->mergeCells('I4:I6');
            $sheet->SetCellValue('J4', 'Status');
            $sheet->mergeCells('J4:J6');
            $sheet->SetCellValue('K4', 'Gaji Pokok');
            $sheet->mergeCells('K4:K6');
            $sheet->SetCellValue('L4', 'Tunjangan');
            $sheet->mergeCells('L4:N4');
            $sheet->SetCellValue('O4', 'Diberikan Oleh RS');
            $sheet->mergeCells('O4:Q4');
            $sheet->SetCellValue('R4', 'Insentif Direksi');
            $sheet->mergeCells('R4:R6');
            $sheet->SetCellValue('S4', 'Bruto');
            $sheet->mergeCells('S4:S6');
            $sheet->SetCellValue('T4', 'Potongan');
            $sheet->mergeCells('T4:AA4');
            $sheet->SetCellValue('AB4', 'Netto');
            $sheet->mergeCells('AB4:AB6');

            $sheet->SetCellValue('L5', 'Jabatan');
            $sheet->mergeCells('L5:L6');
            $sheet->SetCellValue('M5', 'Fungsi');
            $sheet->mergeCells('M5:M6');
            $sheet->SetCellValue('N5', 'Transport');
            $sheet->mergeCells('N5:N6');
            
            $sheet->SetCellValue('O5', 'BPJS jkk jht jkm');    
            $sheet->SetCellValue('O6', $s['persen_rs_bpjs_jkk'] . '% ');
            $sheet->SetCellValue('P5', 'BPJS pensiun');
            $sheet->SetCellValue('P6', $s['persen_rs_bpjs_pensiun'] . '% ');
            $sheet->SetCellValue('Q5', 'BPJS Kes');
            $sheet->SetCellValue('Q6', $s['persen_rs_bpjs_kesehatan'] . '% ');

            $sheet->SetCellValue('T5', 'BPJS jkk jht jkm');        
            $sheet->mergeCells('T5:U5');
            $sheet->SetCellValue('T6', $s['persen_bpjs_jkk'] . '% ');
            $sheet->SetCellValue('U6', $s['persen_rs_bpjs_jkk'] . '% ');
            $sheet->SetCellValue('V5', 'BPJS pensiun');      
            $sheet->mergeCells('V5:W5');
            $sheet->SetCellValue('V6', $s['persen_bpjs_pensiun'] . '% ');
            $sheet->SetCellValue('W6', $s['persen_rs_bpjs_pensiun'] . '% ');
            $sheet->SetCellValue('X5', 'Pajak');
            $sheet->SetCellValue('Y5', 'BPJS Kes');   
            $sheet->mergeCells('Y5:Z5');
            $sheet->SetCellValue('Y6', $s['persen_bpjs_kesehatan'] . '% ');
            $sheet->SetCellValue('Z6', $s['persen_rs_bpjs_kesehatan'] . '% ');
            $sheet->SetCellValue('AA5', 'Jumlah');  
            $sheet->mergeCells('AA5:AA6');
            // body
            $no = 1;
            $row = $row_body = 5;
            $total = [
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
            // foreach ($data['list'][$mapping['sl_id']] as $key => $value) {
                # code...
            // }
            break;
        }

        $spreadsheet->setActiveSheetIndex(0);
        $filename = 'GAJI_' . strtoupper($this->bulan[$bulan]) . '-' . $tahun . ".xlsx";

        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="' . $filename . '"');
        header('Cache-Control: max-age=0');

        $writer = new Xlsx($spreadsheet);
        $writer->save('php://output');
    }

    public function excel_bca($bulan, $tahun)
    {
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        $titleStyle = array(
            'font' => array(
                'bold' => true,
                'size' => 13,
                'name' => 'Arial'
            ),
            'alignment' => array(
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER,
                'rotation' => 0
            )
        );
        $titlesubStyle = array(
            'font' => array(
                'bold' => false,
                'size' => 10,
                'name' => 'Arial'
            ),
            'alignment' => array(
                'horizontal' => Alignment::HORIZONTAL_LEFT,
                'vertical' => Alignment::VERTICAL_CENTER,
                'rotation' => 0
            )
        );
        $normalStyle = array(
            'font' => array(
                'bold' => false,
                'size' => 10,
                'name' => 'Arial'
            ),
            'alignment' => array(
                'horizontal' => Alignment::HORIZONTAL_LEFT,
                'vertical' => Alignment::VERTICAL_CENTER,
                'rotation' => 0
            )
        );
        $headerStyle = array(
            'font' => array(
                'bold' => true,
                'size' => 10,
                'name' => 'Arial'
            ),
            'alignment' => array(
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER,
                'rotation' => 0
            ),
            'fill' => array(
                'fillType' => Fill::FILL_SOLID,
                'startColor' => array('argb' => 'FFEEEEEE')
            ),
            'borders' => array(
                'allBorders' => array(
                    'borderStyle' => Border::BORDER_THIN
                )
            )
        );
        $bodyStyle = array(
            'font' => array(
                'size' => 10,
                'name' => 'Arial'
            ),
            'alignment' => array(
                'vertical' => Alignment::VERTICAL_TOP
            ),
            'borders' => array(
                'allBorders' => array(
                    'borderStyle' => Border::BORDER_THIN
                )
            )
        );
        $centerStyle = array(
            'font' => array(
                'size' => 10,
                'name' => 'Arial'
            ),
            'alignment' => array('horizontal' => Alignment::HORIZONTAL_CENTER),
            'borders' => array(
                'allBorders' => array(
                    'borderStyle' => Border::BORDER_THIN
                )
            )
        );
        $rightStyle = array(
            'font' => array(
                'size' => 10,
                'name' => 'Arial'
            ),
            'alignment' => array('horizontal' => Alignment::HORIZONTAL_RIGHT),
            'borders' => array(
                'allBorders' => array(
                    'borderStyle' => Border::BORDER_THIN
                )
            )
        );

        $spreadsheet->getActiveSheet()->setTitle('BCA');
        $sheet->SetCellValue('A1', "PEMBAYARAN GAJI KARYAWAN RSPN BULAN " . strtoupper($this->bulan[$bulan]) . " TAHUN $tahun VIA PAYROLL BCA");
        $sheet->mergeCells('A1:H1');
        //header
        $sheet->SetCellValue('A3', 'No.');
        $sheet->SetCellValue('B3', 'Acc No.');
        $sheet->SetCellValue('C3', 'Transc. Ammount');
        $sheet->SetCellValue('D3', 'Emp. No.');
        $sheet->SetCellValue('E3', 'Emp. Name');
        $sheet->SetCellValue('F3', 'Dept.');
        $sheet->SetCellValue('G3', 'Trans. Date');
        $sheet->SetCellValue('H3', 'ACC NAME');
        // body
        $this->load->model('MPayroll');
        $data = $this->MPayroll->get_slip_gaji_bca($tahun, $bulan);
        $no = 1;
        $row = $row_body = 4;
        $t_netto = 0;
        $t = [
            'bank' => [
                'rs' => [
                    'pegawai' => 0,
                    'pegawai_potongan' => 0,
                    'netto_asli' => 0,
                    'netto' => 0
                ],
                'ptt' => [
                    'pegawai' => 0,
                    'pegawai_potongan' => 0,
                    'netto_asli' => 0,
                    'netto' => 0,
                ]
            ],
            'tunai' => [
                'rs' => [
                    'pegawai' => 0,
                    'pegawai_potongan' => 0,
                    'netto_asli' => 0,
                    'netto' => 0,
                ],
                'ptt' => [
                    'pegawai' => 0,
                    'pegawai_potongan' => 0,
                    'netto_asli' => 0,
                    'netto' => 0,
                ]
            ]
        ];
        $arrTunai = [];
        foreach ($data as $key => $value) {
            $ptt = '';
            $jns_peg = 'rs';
            // jika PTT
            if ($value['g_id'] == 0) {
                $ptt = ' - PTT';
                $jns_peg = 'ptt';
            }
            $jns_pembayaran = 'tunai';
            // jika norek diisi
            if ($value['us_norek_bca'] != '') {
                $jns_pembayaran = 'bank';
            }
            $netto = $value['netto'];
            //jika ada potongan gaji, kolom yg diambil adalah terima_nominal
            if ($value['terima_nominal'] != '') {
                $netto = $value['terima_nominal'];
                $t[$jns_pembayaran][$jns_peg]['pegawai_potongan']++;
            }
            $t[$jns_pembayaran][$jns_peg]['pegawai']++;
            $t[$jns_pembayaran][$jns_peg]['netto_asli'] += $value['netto'];
            $t[$jns_pembayaran][$jns_peg]['netto'] += $netto;

            if ($value['us_norek_bca'] == '') {
                $arrTunai[] = $value;
                continue;
            }
            $sheet->SetCellValue("A$row", $no);
            $sheet->SetCellValue("B$row", $value['us_norek_bca']);
            $sheet->getStyle("C$row")->getNumberFormat()->setFormatCode('###,###,###.00');
            $sheet->setCellValueExplicit("C$row", $netto, DataType::TYPE_NUMERIC);
            $sheet->SetCellValue("D$row", $value['us_reg']);
            $sheet->SetCellValue("E$row", $value['us_nama']);
            $sheet->SetCellValue("F$row", $value['uk_nama'] . $ptt);
            $sheet->SetCellValue("G$row", '');
            $sheet->SetCellValue("H$row", $value['us_an_rek']);
            $t_netto += $netto;
            $no++;
            $row++;
        }
        $row_body_end = $row - 1;
        $sheet->SetCellValue("A$row", 'Total');
        $sheet->mergeCells("A$row:B$row");
        $sheet->getStyle("C$row")->getNumberFormat()->setFormatCode('###,###,###.00');
        $sheet->setCellValueExplicit("C$row", $t_netto, DataType::TYPE_NUMERIC);
        $sheet->mergeCells("D$row:H$row");

        $ketStyle = array(
            'font' => array(
                'bold' => true,
                'size' => 10,
                'name' => 'Arial',
                'color' => array('argb' => 'FFFFFFFF')
            ),
            'fill' => array(
                'fillType' => Fill::FILL_SOLID,
                'startColor' => array('argb' => '00000000')
            ),
            'alignment' => array('horizontal' => Alignment::HORIZONTAL_CENTER)
        );
        $sumHeaderStyle = array(
            'font' => array(
                'bold' => true,
                'size' => 10,
                'name' => 'Arial'
            ),
            'alignment' => array(
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER
            ),
            'fill' => array(
                'fillType' => Fill::FILL_SOLID,
                'startColor' => array('argb' => 'FFEEEEEE')
            )
        );
        $sumBodyStyle = array(
            'font' => array(
                'size' => 10,
                'name' => 'Arial'
            )
        );
        $totStyle = array(
            'font' => array(
                'bold' => true,
                'size' => 10,
                'name' => 'Arial'
            ),
            'fill' => array(
                'fillType' => Fill::FILL_SOLID,
                'startColor' => array('argb' => 'FFEEEEEE')
            )
        );

        $row_k = $row + 2;
        $sheet->SetCellValue("A$row_k", 'Keterangan');
        $sheet->mergeCells("A$row_k:H$row_k");
        $sheet->getStyle("A$row_k:H$row_k")->applyFromArray($ketStyle);
        $row_k++;
        $row_awal = $row_k;
        $sheet->SetCellValue("A$row_k", 'GAJI BERSIH');
        $sheet->mergeCells("A$row_k:D$row_k");
        $sheet->SetCellValue("E$row_k", 'POTONGAN GAJI');
        $sheet->mergeCells("E$row_k:F$row_k");
        $sheet->SetCellValue("G$row_k", 'JUMLAH GAJI BERSIH SETELAH POTONGAN GAJI');
        $sheet->mergeCells("G$row_k:H" . ($row_k + 1));
        $row_k++;
        $sheet->SetCellValue("A$row_k", 'setelah dikurangi BPJS dan PPh 21');
        $sheet->mergeCells("A$row_k:D$row_k");
        $master = $this->MCore->get_data('payroll_additional_gaji', ['p_record_status' => 'A'], 'p_urut');
        $arrmaster = [];
        $t_potongan = [];
        foreach ($master->result_array() as $key => $value) {
            $arrmaster[] = $value['p_nama'];
            $t_potongan[$value['p_id']] = 0;
        }
        $sheet->SetCellValue("E$row_k", implode(', ', $arrmaster));
        $sheet->mergeCells("E$row_k:F$row_k");
        $row_master = $row_k;
        $row_k++;
        $sheet->SetCellValue("A$row_k", 'Karyawan');
        $sheet->mergeCells("A$row_k:B" . ($row_k + 1));
        $sheet->SetCellValue("C$row_k", "Jumlah (Rp.)");
        $sheet->SetCellValue("D$row_k", "Jumlah (Orang)");
        $sheet->mergeCells("D$row_k:D" . ($row_k + 1));

        $sheet->SetCellValue("E$row_k", "Jumlah (Rp.)");
        $sheet->SetCellValue("F$row_k", "Jumlah (Orang)");
        $sheet->mergeCells("F$row_k:F" . ($row_k + 1));

        $sheet->SetCellValue("G$row_k", "Jumlah (Rp.)");
        $sheet->SetCellValue("H$row_k", "Jumlah (Orang)");
        $sheet->mergeCells("H$row_k:H" . ($row_k + 1));
        $row_k++;
        $sheet->SetCellValue("C$row_k", "(a)");
        $sheet->SetCellValue("E$row_k", "(b)");
        $sheet->SetCellValue("G$row_k", "c=(a-b)");
        $sheet->getStyle("A$row_awal:H$row_k")->applyFromArray($sumHeaderStyle);
        $row_k++;
        $sheet->SetCellValue("A$row_k", '1');
        $sheet->SetCellValue("B$row_k", 'RSPN');
        $sheet->getStyle("C$row_k")->getNumberFormat()->setFormatCode('###,###,###.00');
        $sheet->setCellValueExplicit("C$row_k", $t['bank']['rs']['netto_asli'], DataType::TYPE_NUMERIC);
        $sheet->SetCellValue("D$row_k", $t['bank']['rs']['pegawai']);

        $sheet->getStyle("E$row_k")->getNumberFormat()->setFormatCode('###,###,###.00');
        $sheet->setCellValueExplicit("E$row_k", $t['bank']['rs']['netto_asli'] - $t['bank']['rs']['netto'], DataType::TYPE_NUMERIC);
        $sheet->SetCellValue("F$row_k", $t['bank']['rs']['pegawai_potongan']);

        $sheet->getStyle("G$row_k")->getNumberFormat()->setFormatCode('###,###,###.00');
        $sheet->setCellValueExplicit("G$row_k", $t['bank']['rs']['netto'], DataType::TYPE_NUMERIC);
        $sheet->SetCellValue("H$row_k", $t['bank']['rs']['pegawai']);
        $sheet->getStyle("A$row_k:H$row_k")->applyFromArray($sumBodyStyle);
        $row_k++;
        $sheet->SetCellValue("A$row_k", '2');
        $sheet->SetCellValue("B$row_k", 'PTT');
        $sheet->getStyle("C$row_k")->getNumberFormat()->setFormatCode('###,###,###.00');
        $sheet->setCellValueExplicit("C$row_k", $t['bank']['ptt']['netto_asli'], DataType::TYPE_NUMERIC);
        $sheet->SetCellValue("D$row_k", $t['bank']['ptt']['pegawai']);

        $sheet->getStyle("E$row_k")->getNumberFormat()->setFormatCode('###,###,###.00');
        $sheet->setCellValueExplicit("E$row_k", $t['bank']['ptt']['netto_asli'] - $t['bank']['ptt']['netto'], DataType::TYPE_NUMERIC);
        $sheet->SetCellValue("F$row_k", $t['bank']['ptt']['pegawai_potongan']);

        $sheet->getStyle("G$row_k")->getNumberFormat()->setFormatCode('###,###,###.00');
        $sheet->setCellValueExplicit("G$row_k", $t['bank']['ptt']['netto'], DataType::TYPE_NUMERIC);
        $sheet->SetCellValue("H$row_k", $t['bank']['ptt']['pegawai']);
        $sheet->getStyle("A$row_k:H$row_k")->applyFromArray($sumBodyStyle);
        $row_k++;
        $sheet->SetCellValue("A$row_k", 'TOTAL');
        $sheet->mergeCells("A$row_k:B$row_k");
        $sheet->getStyle("C$row_k")->getNumberFormat()->setFormatCode('###,###,###.00');
        $sheet->setCellValueExplicit("C$row_k", $t['bank']['rs']['netto_asli'] + $t['bank']['ptt']['netto_asli'], DataType::TYPE_NUMERIC);
        $sheet->SetCellValue("D$row_k", $t['bank']['rs']['pegawai'] + $t['bank']['ptt']['pegawai']);
        $sheet->getStyle("E$row_k")->getNumberFormat()->setFormatCode('###,###,###.00');
        $sheet->setCellValueExplicit("E$row_k", $t['bank']['rs']['netto_asli'] + $t['bank']['ptt']['netto_asli'] - ($t['bank']['rs']['netto'] + $t['bank']['ptt']['netto']), DataType::TYPE_NUMERIC);
        $sheet->SetCellValue("F$row_k", $t['bank']['rs']['pegawai_potongan'] + $t['bank']['ptt']['pegawai_potongan']);
        $sheet->getStyle("G$row_k")->getNumberFormat()->setFormatCode('###,###,###.00');
        $sheet->setCellValueExplicit("G$row_k", $t['bank']['rs']['netto'] + $t['bank']['ptt']['netto'], DataType::TYPE_NUMERIC);
        $sheet->SetCellValue("H$row_k", $t['bank']['rs']['pegawai'] + $t['bank']['ptt']['pegawai']);
        $sheet->getStyle("A$row_k:H$row_k")->applyFromArray($totStyle);
        $row_k++;
        $row_k++;
        $row_k++;
        $sheet->SetCellValue("A$row_k", 'Keterangan');
        $sheet->mergeCells("A$row_k:F$row_k");
        $sheet->getStyle("A$row_k:F$row_k")->applyFromArray($ketStyle);
        $row_k++;
        $row_awal = $row_k;
        $sheet->SetCellValue("A$row_k", 'GAJI TUNAI LEWAT KASIR');
        $sheet->mergeCells("A$row_k:D$row_k");
        $sheet->SetCellValue("E$row_k", 'GAJI LEWAT BCA');
        $sheet->mergeCells("E$row_k:F$row_k");
        $row_k++;
        $sheet->SetCellValue("A$row_k", 'Karyawan');
        $sheet->mergeCells("A$row_k:B" . ($row_k + 1));
        $sheet->SetCellValue("C$row_k", "Jumlah (Rp.)");
        $sheet->SetCellValue("D$row_k", "Jumlah (Orang)");
        $sheet->mergeCells("D$row_k:D" . ($row_k + 1));

        $sheet->SetCellValue("E$row_k", "Jumlah (Rp.)");
        $sheet->SetCellValue("F$row_k", "Jumlah (Orang)");
        $sheet->mergeCells("F$row_k:F" . ($row_k + 1));
        $row_k++;
        $sheet->SetCellValue("C$row_k", "(d)");
        $sheet->SetCellValue("E$row_k", "e=(c-d)");
        $sheet->getStyle("A$row_awal:F$row_k")->applyFromArray($sumHeaderStyle);
        $row_k++;
        $sheet->SetCellValue("A$row_k", '1');
        $sheet->SetCellValue("B$row_k", 'RSPN');
        $sheet->getStyle("C$row_k")->getNumberFormat()->setFormatCode('###,###,###.00');
        $sheet->setCellValueExplicit("C$row_k", $t['tunai']['rs']['netto_asli'], DataType::TYPE_NUMERIC);
        $sheet->SetCellValue("D$row_k", $t['tunai']['rs']['pegawai']);

        $sheet->getStyle("E$row_k")->getNumberFormat()->setFormatCode('###,###,###.00');
        $sheet->setCellValueExplicit("E$row_k", $t['bank']['rs']['netto'], DataType::TYPE_NUMERIC);
        $sheet->SetCellValue("F$row_k", $t['bank']['rs']['pegawai']);
        $sheet->getStyle("A$row_k:F$row_k")->applyFromArray($sumBodyStyle);
        $row_k++;
        $sheet->SetCellValue("A$row_k", '2');
        $sheet->SetCellValue("B$row_k", 'PTT');
        $sheet->getStyle("C$row_k")->getNumberFormat()->setFormatCode('###,###,###.00');
        $sheet->setCellValueExplicit("C$row_k", $t['tunai']['ptt']['netto_asli'], DataType::TYPE_NUMERIC);
        $sheet->SetCellValue("D$row_k", $t['tunai']['ptt']['pegawai']);

        $sheet->getStyle("E$row_k")->getNumberFormat()->setFormatCode('###,###,###.00');
        $sheet->setCellValueExplicit("E$row_k", $t['bank']['ptt']['netto'], DataType::TYPE_NUMERIC);
        $sheet->SetCellValue("F$row_k", $t['bank']['ptt']['pegawai']);
        $sheet->getStyle("A$row_k:F$row_k")->applyFromArray($sumBodyStyle);
        $row_k++;
        $sheet->SetCellValue("A$row_k", 'TOTAL');
        $sheet->mergeCells("A$row_k:B$row_k");
        $sheet->getStyle("C$row_k")->getNumberFormat()->setFormatCode('###,###,###.00');
        $sheet->setCellValueExplicit("C$row_k", $t['tunai']['rs']['netto_asli'] + $t['tunai']['ptt']['netto_asli'], DataType::TYPE_NUMERIC);
        $sheet->SetCellValue("D$row_k", $t['tunai']['rs']['pegawai'] + $t['tunai']['ptt']['pegawai']);
        $sheet->getStyle("A$row_k:F$row_k")->applyFromArray($totStyle);
        $row_k++;
        $sheet->SetCellValue("A$row_k", 'Potongan');
        $sheet->mergeCells("A$row_k:B$row_k");
        $sheet->getStyle("C$row_k")->getNumberFormat()->setFormatCode('###,###,###.00');
        $sheet->setCellValueExplicit("C$row_k", $t['tunai']['rs']['netto_asli'] + $t['tunai']['ptt']['netto_asli'] - ($t['tunai']['rs']['netto'] + $t['tunai']['ptt']['netto']), DataType::TYPE_NUMERIC);
        $sheet->SetCellValue("D$row_k", $t['tunai']['rs']['pegawai_potongan'] + $t['tunai']['ptt']['pegawai_potongan']);
        $sheet->getStyle("A$row_k:D$row_k")->applyFromArray($sumBodyStyle);
        $sheet->getStyle("E$row_k:F$row_k")->applyFromArray($totStyle);
        $row_k++;
        $sheet->SetCellValue("A$row_k", 'TOTAL');
        $sheet->mergeCells("A$row_k:B$row_k");
        $sheet->getStyle("C$row_k")->getNumberFormat()->setFormatCode('###,###,###.00');
        $sheet->setCellValueExplicit("C$row_k", $t['tunai']['rs']['netto'] + $t['tunai']['ptt']['netto'], DataType::TYPE_NUMERIC);
        $sheet->SetCellValue("D$row_k", $t['tunai']['rs']['pegawai'] + $t['tunai']['ptt']['pegawai']);
        $sheet->getStyle("E$row_k")->getNumberFormat()->setFormatCode('###,###,###.00');
        $sheet->setCellValueExplicit("E$row_k", $t['bank']['rs']['netto'] + $t['bank']['ptt']['netto'], DataType::TYPE_NUMERIC);
        $sheet->SetCellValue("F$row_k", $t['bank']['rs']['pegawai'] + $t['bank']['ptt']['pegawai']);
        $sheet->getStyle("A$row_k:F$row_k")->applyFromArray($totStyle);

        $sheet->getColumnDimension('A')->setWidth(4);
        $sheet->getColumnDimension('B')->setWidth(13);
        $sheet->getColumnDimension('C')->setWidth(16);
        $sheet->getColumnDimension('D')->setWidth(13);
        $sheet->getColumnDimension('E')->setWidth(25);
        $sheet->getColumnDimension('F')->setWidth(20);
        $sheet->getColumnDimension('G')->setWidth(15);
        $sheet->getColumnDimension('H')->setWidth(30);

        $sheet->getStyle("A1:H1")->applyFromArray($titleStyle);
        $sheet->getStyle("A3:H3")->applyFromArray($headerStyle);
        $sheet->getStyle("A$row_body:H$row_body_end")->applyFromArray($bodyStyle);
        $sheet->getStyle("A$row_body:B$row_body_end")->applyFromArray($centerStyle);
        $sheet->getStyle("D$row_body:D$row_body_end")->applyFromArray($centerStyle);
        $sheet->getStyle("F$row_body:F$row_body_end")->applyFromArray($centerStyle);

        $sheet->getStyle("E$row_body:F$row_body_end")
            ->getAlignment()->setWrapText(true);
        $sheet->getStyle("H$row_body:H$row_body_end")
            ->getAlignment()->setWrapText(true);
        $sheet->getStyle("E$row_master")
            ->getAlignment()->setWrapText(true);

        $spreadsheet->createSheet();
        $spreadsheet->setActiveSheetIndex(1);
        $sheet = $spreadsheet->getActiveSheet();
        $spreadsheet->getActiveSheet()->setTitle('Tunai');
        $sheet->SetCellValue('A1', "PEMBAYARAN GAJI KARYAWAN RSPN BULAN " . strtoupper($this->bulan[$bulan]) . " TAHUN $tahun");
        $sheet->mergeCells('A1:F1');
        //header
        $sheet->SetCellValue('A3', 'No.');
        $sheet->SetCellValue('B3', 'Ammount');
        $sheet->SetCellValue('C3', 'Emp. No.');
        $sheet->SetCellValue('D3', 'Emp. Name');
        $sheet->SetCellValue('E3', 'Dept.');
        $sheet->SetCellValue('F3', 'Date');
        // body
        $no = 1;
        $row = $row_body = 4;
        $t_netto = 0;
        foreach ($arrTunai as $key => $value) {
            $ptt = '';
            // jika PTT
            if ($value['g_id'] == 0) {
                $ptt = ' - PTT';
            }
            $netto = $value['netto'];
            //jika ada potongan gaji, kolom yg diambil adalah terima_nominal
            if ($value['terima_nominal'] != '') {
                $netto = $value['terima_nominal'];
            }
            $sheet->SetCellValue("A$row", $no);
            $sheet->getStyle("B$row")->getNumberFormat()->setFormatCode('###,###,###.00');
            $sheet->setCellValueExplicit("B$row", $netto, DataType::TYPE_NUMERIC);
            $sheet->SetCellValue("C$row", $value['us_reg']);
            $sheet->SetCellValue("D$row", $value['us_nama']);
            $sheet->SetCellValue("E$row", $value['uk_nama'] . $ptt);
            $sheet->SetCellValue("F$row", '');
            $t_netto += $netto;
            $no++;
            $row++;
        }
        $row_body_end = $row - 1;
        $sheet->getStyle("B$row")->getNumberFormat()->setFormatCode('###,###,###.00');
        $sheet->setCellValueExplicit("B$row", $t_netto, DataType::TYPE_NUMERIC);
        $sheet->mergeCells("C$row:F$row");

        $sheet->getColumnDimension('A')->setWidth(4);
        $sheet->getColumnDimension('B')->setWidth(16);
        $sheet->getColumnDimension('C')->setWidth(13);
        $sheet->getColumnDimension('D')->setWidth(25);
        $sheet->getColumnDimension('E')->setWidth(20);
        $sheet->getColumnDimension('F')->setWidth(15);

        $sheet->getStyle("A1:F1")->applyFromArray($titleStyle);
        $sheet->getStyle("A3:F3")->applyFromArray($headerStyle);
        $sheet->getStyle("A$row_body:F$row_body_end")->applyFromArray($bodyStyle);
        $sheet->getStyle("A$row_body:A$row_body_end")->applyFromArray($centerStyle);
        $sheet->getStyle("C$row_body:C$row_body_end")->applyFromArray($centerStyle);
        $sheet->getStyle("E$row_body:E$row_body_end")->applyFromArray($centerStyle);

        $sheet->getStyle("D$row_body:D$row_body_end")
            ->getAlignment()->setWrapText(true);
        $sheet->getStyle("E$row_body:E$row_body_end")
            ->getAlignment()->setWrapText(true);

        $spreadsheet->createSheet();
        $spreadsheet->setActiveSheetIndex(2);
        $sheet = $spreadsheet->getActiveSheet();
        $spreadsheet->getActiveSheet()->setTitle('MASTER');
        $col = 'J';
        foreach ($arrmaster as $key => $value) {
            $sheet->SetCellValue($col . '3', $value);
            $sheet->getColumnDimension($col)->setWidth(20);
            $col++;
        }
        $last_col = chr(ord($col) - 1);

        $sheet->SetCellValue('A1', "KESELURUHAN GAJI KARYAWAN RSPN BULAN " . strtoupper($this->bulan[$bulan]) . " TAHUN $tahun");
        $sheet->mergeCells('A1:' . $last_col . '1');
        //header
        $sheet->SetCellValue('A3', 'No.');
        $sheet->SetCellValue('B3', 'Acc No.');
        $sheet->SetCellValue('C3', 'Transc. Ammount');
        $sheet->SetCellValue('D3', 'Emp. No.');
        $sheet->SetCellValue('E3', 'Emp. Name');
        $sheet->SetCellValue('F3', 'Dept.');
        $sheet->SetCellValue('G3', 'Trans. Date');
        $sheet->SetCellValue('H3', 'ACC NAME');
        $sheet->SetCellValue('I3', 'Bruto');
        // body
        $no = 1;
        $row = $row_body = 4;
        $t_netto = $t_netto_asli = 0;
        foreach ($data as $key => $value) {
            $ptt = '';
            // jika PTT
            if ($value['g_id'] == 0) {
                $ptt = ' - PTT';
            }
            $netto = $value['netto'];
            //jika ada potongan gaji, kolom yg diambil adalah terima_nominal
            $json = [];
            if ($value['terima_nominal'] != '') {
                $netto = $value['terima_nominal'];
                $json = json_decode($value['terima_json_additional'], true);
            }
            $sheet->SetCellValue("A$row", $no);
            $sheet->SetCellValue("B$row", $value['us_norek_bca']);
            $sheet->getStyle("C$row")->getNumberFormat()->setFormatCode('###,###,###.00');
            $sheet->setCellValueExplicit("C$row", $netto, DataType::TYPE_NUMERIC);
            $sheet->SetCellValue("D$row", $value['us_reg']);
            $sheet->SetCellValue("E$row", $value['us_nama']);
            $sheet->SetCellValue("F$row", $value['uk_nama'] . $ptt);
            $sheet->SetCellValue("G$row", '');
            $sheet->SetCellValue("H$row", $value['us_an_rek']);
            $sheet->getStyle("I$row")->getNumberFormat()->setFormatCode('###,###,###.00');
            $sheet->setCellValueExplicit("I$row", $value['netto'], DataType::TYPE_NUMERIC);

            $col = 'J';
            foreach ($master->result_array() as $key => $vm) {
                if (array_key_exists($vm['p_id'], $json)) {
                    $nominal = $json[$vm['p_id']];
                    $sheet->getStyle($col . $row)->getNumberFormat()->setFormatCode('###,###,###.00');
                    $sheet->setCellValueExplicit($col . $row, $nominal, DataType::TYPE_NUMERIC);
                    $t_potongan[$vm['p_id']] += $nominal;
                }
                $col++;
            }
            $t_netto += $netto;
            $t_netto_asli += $value['netto'];
            $no++;
            $row++;
        }
        $row_body_end = $row - 1;
        $sheet->SetCellValue("A$row", 'Total');
        $sheet->mergeCells("A$row:B$row");
        $sheet->getStyle("C$row")->getNumberFormat()->setFormatCode('###,###,###.00');
        $sheet->setCellValueExplicit("C$row", $t_netto, DataType::TYPE_NUMERIC);
        $sheet->getStyle("I$row")->getNumberFormat()->setFormatCode('###,###,###.00');
        $sheet->setCellValueExplicit("I$row", $t_netto_asli, DataType::TYPE_NUMERIC);
        $col = 'J';
        foreach ($master->result_array() as $key => $vm) {
            $sheet->getStyle($col . $row)->getNumberFormat()->setFormatCode('###,###,###.00');
            $sheet->setCellValueExplicit($col . $row, $t_potongan[$vm['p_id']], DataType::TYPE_NUMERIC);
            $col++;
        }
        $sheet->getStyle("A$row:" . $last_col . $row)->applyFromArray($totStyle);

        $sheet->getColumnDimension('A')->setWidth(4);
        $sheet->getColumnDimension('B')->setWidth(13);
        $sheet->getColumnDimension('C')->setWidth(16);
        $sheet->getColumnDimension('D')->setWidth(13);
        $sheet->getColumnDimension('E')->setWidth(25);
        $sheet->getColumnDimension('F')->setWidth(20);
        $sheet->getColumnDimension('G')->setWidth(15);
        $sheet->getColumnDimension('H')->setWidth(30);
        $sheet->getColumnDimension('I')->setWidth(16);

        $sheet->getStyle("A1:" . $last_col . "1")->applyFromArray($titleStyle);
        $sheet->getStyle("A3:" . $last_col . "3")->applyFromArray($headerStyle);
        $sheet->getStyle("A$row_body:" . $last_col . $row_body_end)->applyFromArray($bodyStyle);
        $sheet->getStyle("A$row_body:B$row_body_end")->applyFromArray($centerStyle);
        $sheet->getStyle("D$row_body:D$row_body_end")->applyFromArray($centerStyle);
        $sheet->getStyle("F$row_body:F$row_body_end")->applyFromArray($centerStyle);

        $sheet->getStyle("E$row_body:F$row_body_end")
            ->getAlignment()->setWrapText(true);
        $sheet->getStyle("H$row_body:H$row_body_end")
            ->getAlignment()->setWrapText(true);
        $sheet->getStyle("A3:" . $last_col . '3')
            ->getAlignment()->setWrapText(true);

        $spreadsheet->setActiveSheetIndex(0);
        $filename = 'BCA_GAJI_' . $tahun . '-' . $bulan . ".xlsx";

        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="' . $filename . '"');
        header('Cache-Control: max-age=0');

        $writer = new Xlsx($spreadsheet);
        $writer->save('php://output');
    }
}
