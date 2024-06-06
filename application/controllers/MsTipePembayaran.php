<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class MsTipePembayaran extends CI_Controller	 {

	function __construct()
	{
		parent::__construct();  
	}

	public function index()
	{
	    $this->load->model('TipePembayaranModel');
        $data['title'] = ucfirst('Master TipePembayaran');
        $data['TipePembayaran'] = $this->TipePembayaranModel->get_data();
        $data['AutoNumber'] = $this->TipePembayaranModel->generate_next_number();
		$this->load->view('templates/header', $data);
        $this->load->view('MsTipePembayaranView', $data);
        $this->load->view('templates/footer', $data);
	}

	public function Add()
	{   
	    $this->load->model('TipePembayaranModel'); 
	    $KdTipePembayaran = $this->input->post('KdTipePembayaran');
	    $NamaTipePembayaran = $this->input->post('NamaTipePembayaran');  
	    $CreatedBy = 'Admin'; 
	    $existingData = $this->TipePembayaranModel->get_data_by_KdTipePembayaran($KdTipePembayaran);
	    if ($existingData) {
	    	$data = array(
	            'NamaTipePembayaran' => $NamaTipePembayaran,  
	        );

	        $this->TipePembayaranModel->update_data($KdTipePembayaran, $data);
			echo "Success"; 
	    } 
	    else
	    {
		    $data = array(
		        'KdTipePembayaran' => $KdTipePembayaran, 
	            'NamaTipePembayaran' => $NamaTipePembayaran,  
		        'CreatedBy' => $CreatedBy,
	    		'CreatedDate' => date('Y-m-d H:i:s')
		    ); 
		    $this->TipePembayaranModel->insert_data($data); 
			echo "Success"; 
	    }
	} 
    public function Delete()
    {
	    $this->load->model('TipePembayaranModel');
        $KdTipePembayaran = $this->input->post('KdTipePembayaran'); 
        $this->TipePembayaranModel->delete_data($KdTipePembayaran);
		echo "Success";
    }


 
}
