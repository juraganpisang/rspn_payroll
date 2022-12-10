<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Insentif_jabatan extends RS_Controller
{

    public function index()
    {
        $header = $this->sendHeader('nav_insentif_jabatan', 'Insentif Jabatan');
        $header['js'] = array(
            'maskmoney/jquery.maskMoney.min.js',
        );
        $body = [
            'tbody' => $this->list_()
        ];
        $data = array_merge($header, $body);
        $this->template->view('VInsentif_jabatan', $data);
    }


    private function list_()
    {

        $data = $this->MCore->list_data('payroll_insentif', 'ti_nama DESC');

        $no = 1;
        ob_start();
        foreach ($data->result_array() as $value) {

            $status = '<span class="badge badge-primary">Aktif</span>';

            $button = '';
            if ($value['ti_status'] == 0) {
                $status = '<span class="badge badge-danger">Tidak Aktif</span>';
                $button .= '
                <button id="btn-aktif" data-id="' . $value['ti_id'] . '" type="button" data-toggle="tooltip" title="" class="btn btn-link btn-simple-danger" data-original-title="Aktifkan">
                    <i class="fa fa-check text-success"></i>
                </button>';
            } else {
                $button .= '
                <button id="btn-edit" type="button" data-toggle="tooltip" data-id="' . $value['ti_id'] . '" title="" class="btn btn-link btn-simple-primary btn-lg" data-original-title="Edit">
            <i class="fa fa-edit"></i></button>
                <button id="btn-nonaktif" data-id="' . $value['ti_id'] . '" type="button" data-toggle="tooltip" title="" class="btn btn-link btn-simple-danger" data-original-title="Nonaktifkan">
                    <i class="fa fa-times text-danger"></i>
                </button>';
            }
?>
            <tr class="mb-2">
                <td class="text-center"><?= $no ?></td>
                <td><?= $status ?></td>
                <td><?= $value['ti_nama'] ?></td>
                <td class="text-right"><b><?= rupiah($value['ti_nominal']) ?></b></td>
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
        $data = $this->MCore->get_data('payroll_insentif', 'ti_id = ' . $id);

        echo json_encode($data->row_array());
    }

    public function save()
    {
        $id = $this->input->post('id');
        $baru = str_replace(',', '', $this->input->post('baru'));

        $this->load->library('form_validation');
        $this->form_validation->set_rules('nama', 'Nama jabatan', 'trim|required');
        $this->form_validation->set_rules('baru', 'Nominal Baru', 'trim|required');

        $this->form_validation->set_message('required', '{field} tidak boleh kosong.');

        if ($this->form_validation->run() == FALSE) {
            $errors = $this->form_validation->error_array();
            $arr['status'] = 0;
            $arr['message'] = reset($errors);
            echo json_encode($arr);
            exit();
        }

        $date = date('Y-m-d H:i:s');
        $data = array(
            'ti_nama' => $this->input->post('nama'),
            'ti_nominal' => $baru
        );


        if ($id == '') {
            if ($this->MCore->get_data('payroll_insentif', array('LOWER(ti_nama)' => strtolower($data['ti_nama'])))->num_rows() > 0) {
                $arr['status'] = 0;
                $arr['message'] = 'Nama telah digunakan';
                echo json_encode($arr);
                exit();
            }

            $data['ti_id'] = $this->MCore->get_newid('payroll_insentif', 'ti_id');
            $data['ti_created_at'] = $date;
            $sql = $this->MCore->save_data('payroll_insentif', $data);
        } else {

            $data['ti_updated_at'] = $date;
            $sql = $this->MCore->save_data('payroll_insentif', $data, true, array('ti_id' => $id));
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

    public function aktif($id = 0)
    {
        $sql = $this->MCore->save_data('payroll_insentif', array('ti_status' => 1), true, array('ti_id' => $id));

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
        $sql = $this->MCore->save_data('payroll_insentif', array('ti_status' => 0), true, array('ti_id' => $id));

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
