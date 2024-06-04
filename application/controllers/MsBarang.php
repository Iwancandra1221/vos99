<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class MsBarang extends CI_Controller	 {

	function __construct()
	{
		parent::__construct();  
	}

	public function index()
	{
	    $this->load->model('BarangModel');
        $data['title'] = ucfirst('Master Barang');
        $data['barang'] = $this->BarangModel->get_data();
        $data['AutoNumber'] = $this->BarangModel->generate_next_number();
		$this->load->view('templates/header', $data);
        $this->load->view('MsBarangView', $data);
        $this->load->view('templates/footer', $data);
	}

	public function Add()
	{ 
	    $this->load->model('BarangModel'); 
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
	    	$data = array(
	            'NamaBarang' => $NamaBarang,
	            'Qty' => $Qty,
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
