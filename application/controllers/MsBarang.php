<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class MsBarang extends CI_Controller	 {

	function __construct()
	{
		parent::__construct();  
        $this->load->model('WarnaModel');
	    $this->load->model('BarangModel');
	    $this->load->model('TipeModel');
	}

	public function index()
	{
        $data['title'] = ucfirst('Master Barang');
        $data['barang'] = $this->BarangModel->get_datas();
        $data['AutoNumber'] = $this->BarangModel->generate_next_number();
        $data['listWarna'] = $this->WarnaModel->getListWarna(); 
        $data['listTipe'] = $this->TipeModel->getListTipe(); 
		$this->load->view('templates/header', $data);
        $this->load->view('MsBarangView', $data);
        $this->load->view('templates/footer', $data);
	}

	public function Add() 
	{
	    $KdBarang = $this->input->post('KdBarang');
	    $NamaBarang = $this->input->post('NamaBarang');
	    $Qty = $this->input->post('Qty');
	    $Harga = $this->input->post('Harga');
	    $Harga_Jual = $this->input->post('Harga_Jual'); 
	    $Merk = $this->input->post('Merk');
	    $Tipe = $this->input->post('Tipe');
	    $Warna = $this->input->post('Warna');
	    $CreatedBy = 'Admin'; 
	    $existingData = $this->BarangModel->get_data_by_KdBarang($KdBarang);
	    if ($existingData) {

	    	$QtyReal = $Qty - $existingData->Qty + $existingData->Qty_Real;

	    	$data = array(
	            'NamaBarang' => $NamaBarang,
	            'Qty' => $Qty,
		        'Qty_Real' => $QtyReal,
	            'Harga' => $Harga,
	            'Harga_Jual' => $Harga_Jual,
	            'Merk' => $Merk,
	            'Tipe' => $Tipe,
	            'Warna' => $Warna
	        );

	        $this->BarangModel->update_data($KdBarang, $data);
			echo "Success"; 
	    } 
	    else
	    {
		    $data = array(
		        'KdBarang' => $KdBarang,
		        'NamaBarang' => $NamaBarang,
		        'Qty' => $Qty,
		        'Qty_Real' => $Qty,
		        'Qty_Jual' => 0,
		        'Harga' => $Harga,
	            'Harga_Jual' => $Harga_Jual,
		        'Merk' => $Merk,
		        'Tipe' => $Tipe,
		        'Warna' => $Warna,
		        'CreatedBy' => $CreatedBy,
	    		'CreatedDate' => date('Y-m-d H:i:s')
		    ); 
		    $this->BarangModel->insert_data($data); 
			echo "Success"; 
	    }
	} 
    public function Delete()
    {
	    $this->load->model('BarangModel');
        $KdBarang = $this->input->post('KdBarang'); 
        $this->BarangModel->delete_data($KdBarang);
		echo "Success";
    }


 
}
