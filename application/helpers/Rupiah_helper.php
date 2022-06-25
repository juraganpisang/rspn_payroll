<?php
defined('BASEPATH') or exit('No direct script access allowed');
if (!function_exists('rupiah')) {
    function rupiah($angka)
    {
        if(!is_numeric($angka)){
            return $angka;
        }
        $hasil_rupiah = number_format($angka, 0, ',', '.');
        return "Rp.&nbsp;".$hasil_rupiah;
    }
}
