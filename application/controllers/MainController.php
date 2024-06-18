<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class MainController extends CI_Controller	 {

	function __construct()
	{
		parent::__construct();  
        $this->load->model('PenjualanModel');
        $this->load->model('BarangModel');
	}

	public function index()
	{

        $data['overdue_penjualan'] = $this->PenjualanModel->get_overdue_penjualan();
        $data['overdue_barang'] = $this->BarangModel->get_overdue_barang();
        // print_r($data['overdue_penjualan']);die;
        $data['title'] = "Home"; 
		$this->load->view('templates/header', $data);
        $this->load->view('home', $data);
        $this->load->view('templates/footer', $data);
	}
 
}
