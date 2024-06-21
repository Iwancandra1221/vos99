<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class MsStockBarang extends CI_Controller	 {

	function __construct()
	{
		parent::__construct();  
	    $this->load->model('StockBarangModel');
        $this->load->library('session'); 
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

	public function tambah_transaksi()
    { 
	    $CreatedBy = 'Admin'; 
        $data = array(
            'IdTransaksi' => $this->StockBarangModel->generate_next_number_trans(),
            'KdStockBarang' => $this->input->post('KdStockBarangTrans'),
            'transaksi_type' => $this->input->post('transaksi_type'),  
            'jumlah' => $this->input->post('jumlah'),
            'keterangan' => $this->input->post('keterangan'),
            'created_by' => $CreatedBy, 
            'created_date' => date('Y-m-d H:i:s')
        ); 
        $this->StockBarangModel->recordTransaction($data);  
        $type = "";
        if ($this->input->post('transaksi_type') === "out")
        {
        	$type = "Keluar";
        }
        else
        {
        	$type = "Masuk";
        }

   		$this->session->set_flashdata('success_message', $type. ' Barang Berhasil'); 
		redirect('MsStockBarang');
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
