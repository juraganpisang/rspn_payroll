<?php
defined('BASEPATH') or exit('No direct script access allowed');

class MPayroll extends CI_Model
{
    
    public function get_mapping_gaji($uk_id)
    {
        $q = $this->db->join('payroll_user_mapping', 'us_id = id_user', 'left')
        ->get_where('z_user', ['us_uk_id' => $uk_id, 'us_level' => 2]);
        return $q;
    }

}