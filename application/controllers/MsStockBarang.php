<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class MsStockBarang extends CI_Controller	 {

	function __construct()
	{
		parent::__construct();  
	    $this->load->model('StockBarangModel');
	}

	public function index()
	{ 
        $data['title'] = ucfirst('Master Stock Barang');
        $data['StockBarang'] = $this->StockBarangModel->get_data();
        $data['AutoNumber'] = $this->StockBarangModel->generate_next_number();
		$this->load->view('templates/header', $data);
        $this->load->view('MsStockBarangView', $data);
        $this->load->view('templates/footer', $data);
	}
  

	public function Add()
	{    
	    $KdStockBarang = $this->input->post('KdStockBarang');
	    $NamaStockBarang = $this->input->post('NamaStockBarang');  
	    // $Qty = $this->input->post('Qty');  
	    $CreatedBy = 'Admin'; 
	    $existingData = $this->StockBarangModel->get_data_by_KdStockBarang($KdStockBarang);
	    if ($existingData) {
	    	$data = array(
	            'NamaStockBarang' => $NamaStockBarang,  
	            // 'Qty_Real' => $Qty,  
	        );

	        $this->StockBarangModel->update_data($KdStockBarang, $data);
			echo "Success"; 
	    } 
	    else
	    {
		    $data = array(
		        'KdStockBarang' => $KdStockBarang, 
	            'NamaStockBarang' => $NamaStockBarang,  
	            'Qty_Real' => 0,  
	            'Qty_Out' => 0,  
	            'Qty_In' => 0,  
		        'CreatedBy' => $CreatedBy,
	    		'CreatedDate' => date('Y-m-d H:i:s')
		    );  
		    $this->StockBarangModel->insert_data($data); 
			echo "Success"; 
	    }
	} 
    public function Delete()
    { 
        $KdStockBarang = $this->input->post('KdStockBarang'); 
        $this->StockBarangModel->delete_data($KdStockBarang);
		echo "Success";
    }


 
}
