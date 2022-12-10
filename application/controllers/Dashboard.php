<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Dashboard extends RS_Controller
{

	public function index()
	{
		$header = $this->sendHeader('nav_dashboard', 'Dashboard', 'overlay-sidebar');
		$header['css'] = array(
			'datepicker/css/tempusdominus-bootstrap-4.min.css',
			'select2/css/select2.min.css',
		);
		$header['js'] = array(
			'moment/moment.min.js',
			'datepicker/js/tempusdominus-bootstrap-4.min.js',
			'select2/js/select2.full.min.js',
		);
		$this->load->model('MDashboard');
		$body = [
			'peg' => $this->MDashboard->get_pegawai()
		];
		$data = array_merge($header, $body);
		$this->template->view('VDashboard', $data);
	}

	public function content($bulan, $tahun)
	{
		$this->load->model('MDashboard');
		$r = $this->MDashboard->get_rekap_gaji($bulan, $tahun);
		$arr = $r;
		$arr['periode'] = $this->bulan[abs($bulan)] . ' ' . $tahun;
		$k = $this->MDashboard->get_kontrak($bulan, $tahun);
		ob_start();
		// $i = 1;
		foreach ($k['kontrak'] as $key => $value) {
?>
			<tr>
				<td>
					<div class="avatar avatar-online">
						<span class="avatar-title rounded-circle border border-white bg-info"><?= substr($value['us_nama'], 0, 2) ?></span>
					</div>
				</td>
				<td>
					<h6 class="text-uppercase fw-bold mb-1"><?= $value['us_nama'] ?></h6>
					<span class="text-muted"><?= $value['uk_nama'] . ' - ' . $value['jb_nama'] ?></span>
				</td>
				<td>
					<small class="text-muted"><?= $value['masa_kerja']['y'] . ' thn ' . $value['masa_kerja']['m'] . ' bln' ?></small><br>
					<small class="text-danger"><?= 'Tahun ke-' . ($value['masa_kerja']['y']) ?></small>
				</td>
			</tr>
			<!-- <div class="d-flex">
				<div class="avatar avatar-online">
					<span class="avatar-title rounded-circle border border-white bg-info"><?= substr($value['us_nama'], 0, 2) ?></span>
				</div>
				<div class="flex-1 ml-3 pt-1">
					<h6 class="text-uppercase fw-bold mb-1"><?= $value['us_nama'] ?></h6>
					<span class="text-muted"><?= $value['uk_nama'] . ' - ' . $value['jb_nama'] ?></span>
				</div>
				<div class="float-right pt-1">
					<small class="text-muted"><?= $value['masa_kerja']['y'] . ' thn ' . $value['masa_kerja']['m'] . ' bln' ?></small><br>
					<small class="text-danger"><?= 'Tahun ke-' . ($value['masa_kerja']['y'] + 1) ?></small>
				</div>
			</div>
			<div class="separator-dashed"></div> -->
			<?php
			// $i++;
			// if ($i == 5) {
			// 	break;
			// }
		}
		$arr['kontrak'] = ob_get_contents();
		ob_clean();
		$arr['t_kontrak'] = count($k['kontrak']);
		if (count($k['kontrak_expired']) > 0) {
			foreach ($k['kontrak_expired'] as $key => $value) {
			?>
				<div class="item-list">
					<div class="avatar text-center">
						<i class="fa fa-3x fa-user"></i>
					</div>
					<div class="info-user ml-3">
						<div class="username"><?= $value['us_nama'] ?></div>
						<div class="status"><?= $value['uk_nama'] . ' - ' . $value['jb_nama'] . '<br>' . $this->cmk($value['masa_kerja']['m']); ?></div>
					</div>
					<button class="btn btn-icon btn-danger btn-round btn-xs">
						<i class="fa fa-file-contract"></i>
					</button>
				</div>
			<?php
			}
		} else {
			?>
			<div class="item-list">
				<div class="avatar text-center">
					<i class="fa fa-3x fa-times text-danger"></i>
				</div>
				<div class="info-user ml-3">
					<div class="username">Tidak ada data</div>
					<div class="status"></div>
				</div>
			</div>
		<?php
		}
		$arr['kontrak_expired'] = ob_get_contents();
		ob_clean();
		$p = $this->MDashboard->get_perubahankomponen($bulan, $tahun);
		foreach ($p['kgb'] as $key => $value) {
			$mulai_kerja = strtotime($value['us_mulai_kerja']);
			$not_yet = '';
			if (date('n', $mulai_kerja) < $value['periode'] && abs($bulan) < $value['periode']) {
				$not_yet = 'text-danger';
			}
		?>
			<tr>
				<td>
					<div class="avatar">
						<span class="avatar-title rounded-circle border border-white <?= $value['is_golongan'] ? 'bg-success' : 'bg-info' ?>"><?= $value['tahun_kerja'] ?></span>
					</div>
				</td>
				<td><?= $value['us_nama'] . '<br>' . $value['uk_nama'] . ' <small class="float-right"><i class="fa fa-file-contract"></i> ' . date('d/m/Y', $mulai_kerja) . '</small>' ?></td>
				<td><?= $value['g_nama'] ?></td>
				<td class="text-right <?= $not_yet ?>"><?= '<small>Rp.</small>' . $this->nf($value['gaji_nominal']) ?></td>
			</tr>
			<?php
		}
		$arr['kgb'] = ob_get_contents();
		ob_clean();
		if (count($p['mk_16']) > 0) {
			foreach ($p['mk_16'] as $key => $value) {
			?>
				<div class="d-flex">
					<!-- <div class="avatar text-center">
						<i class="fa fa-3x fa-user"></i>
					</div> -->
					<div class="flex-1 pt-1 ml-2">
						<h6 class="fw-bold mb-1"><?= $value['us_nama'] ?></h6>
						<small class="text-muted"><?= $value['uk_nama'] ?></small>
					</div>
					<div class="d-flex ml-auto align-items-center">
						<h3 class="text-info fw-bold"><?= '<small>Rp.</small>' . $this->nf($value['tf_lama']) ?></h3>
					</div>
				</div>
				<div class="separator-dashed"></div>
			<?php
			}
		} else {
			?>
			<div class="d-flex">
				<!-- <div class="avatar text-center">
					<i class="fa fa-2x fa-times text-danger"></i>
				</div> -->
				<div class="flex-1 pt-1 ml-2">
					<h6 class="fw-bold mb-1">Tidak ada data</h6>
					<small class="text-muted">&nbsp;</small>
				</div>
				<div class="d-flex ml-auto align-items-center">
					<h3 class="text-info fw-bold"></h3>
				</div>
			</div>
<?php
		}
		$arr['mk_16'] = ob_get_contents();
		ob_clean();
		echo json_encode($arr);
	}

	private function cmk($bulan)
	{
		switch ($bulan) {
			case 11:
				$result = '<span class="text-danger">Bulan ini</span>';
				break;
			default:
				$result =  (11 - $bulan) . ' Bulan lagi';
				break;
		}
		return $result;
	}

	public function hitung_estimasi($bulan, $tahun, $prosentase = 0)
	{
		$this->load->model('MPayroll');
		$arr['gaji'] = $this->MPayroll->estimasi_gaji($bulan, $tahun + 1, $prosentase);
		echo json_encode($arr);
	}
}
