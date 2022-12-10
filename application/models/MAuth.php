<?php
defined('BASEPATH') or exit('No direct script access allowed');

class MAuth extends CI_Model
{

	function getLogin($data)
	{
		// cek berdasarkan username
		$this->db->where('user_name', $data["username"]);
		$user = $this->db->get('payroll_user');
		$user_row = $user->row();
		// jika user terdaftar
		if ($user_row) {
			// periksa password-nya

			$isPasswordTrue = $user_row->user_password == md5($data['password']);

			// jika password benar 
			if ($isPasswordTrue) {
				$newdata = array(
					'user_id' => $user_row->user_id,
					'user_name' => $user_row->user_name,
					'user_fullname'  => $user_row->user_fullname,
					'user_level' => $user_row->user_level,
					'logged_in' => TRUE
				);

				$this->session->set_userdata($newdata);

				$this->MCore->set_history($this->session->userdata('user_fullname'), 'auth', 'Login pada ' . date('d/m/Y H:i:s'));
				return true;
			} else {
				return false;
			}
		} else {
			return false;
		}
	}
}
