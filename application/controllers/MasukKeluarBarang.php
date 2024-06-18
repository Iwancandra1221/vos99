<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class MasukKeluarBarang extends CI_Controller	 {

	function __construct()
	{
		parent::__construct();  
	    $this->load->model('StockBarangModel');
        $this->load->library('session'); 
	}

	public function index()
	{ 
        $data['title'] = ucfirst('Master Masuk Keluar Barang');
        $data['StockBarang'] = $this->StockBarangModel->get_data(); 
		$this->load->view('templates/header', $data);
        $this->load->view('MasukKeluarBarangView', $data);
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
		redirect('MasukKeluarBarang');
    }

	 
}
