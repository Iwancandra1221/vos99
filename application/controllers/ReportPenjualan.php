<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class ReportPenjualan extends CI_Controller	 {

	function __construct()
	{
		parent::__construct();  
	}

	public function index()
	{ 
        $data['title'] = ucfirst('Laporan Penjualan');  
		$this->load->view('templates/header', $data);
        $this->load->view('LaporanPenjualanView', $data);
        $this->load->view('templates/footer', $data);
	} 


 
}
