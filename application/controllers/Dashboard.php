<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Dashboard extends CI_Controller
{

	public function index()
	{
		if (!$this->session->has_userdata('logged_in')) {
			redirect('auth/do_logout');
		}

		$now = date('Y-m-d');

		$awal_bulan = date('Y-m-01');
		$akhir_bulan = date('Y-m-t');
		$data = array(
			'title' => 'Dashboard',
			'nav_id' => 'nav_dashboard',
		);

		$this->template->view('VDashboard', $data);
	}

}
