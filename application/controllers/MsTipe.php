<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class MsTipe extends CI_Controller	 {

	function __construct()
	{
		parent::__construct();  
	}

	public function index()
	{
	    $this->load->model('TipeModel');
        $data['title'] = ucfirst('Master Tipe');
        $data['Tipe'] = $this->TipeModel->get_data();
        $data['AutoNumber'] = $this->TipeModel->generate_next_number();
		$this->load->view('templates/header', $data);
        $this->load->view('MsTipeView', $data);
        $this->load->view('templates/footer', $data);
	}

	public function Add()
	{   
	    $this->load->model('TipeModel'); 
	    $KdTipe = $this->input->post('KdTipe');
	    $NamaTipe = $this->input->post('NamaTipe');  
	    $CreatedBy = 'Admin'; 
	    $existingData = $this->TipeModel->get_data_by_KdTipe($KdTipe);
	    if ($existingData) {
	    	$data = array(
	            'NamaTipe' => $NamaTipe,  
	        );

	        $this->TipeModel->update_data($KdTipe, $data);
			echo "Success"; 
	    } 
	    else
	    {
		    $data = array(
		        'KdTipe' => $KdTipe, 
	            'NamaTipe' => $NamaTipe,  
		        'CreatedBy' => $CreatedBy,
	    		'CreatedDate' => date('Y-m-d H:i:s')
		    ); 
		    $this->TipeModel->insert_data($data); 
			echo "Success"; 
	    }
	} 
    public function Delete()
    {
	    $this->load->model('TipeModel');
        $KdTipe = $this->input->post('KdTipe'); 
        $this->TipeModel->delete_data($KdTipe);
		echo "Success";
    }


 
}
