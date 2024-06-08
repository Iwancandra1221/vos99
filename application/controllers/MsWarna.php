<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class MsWarna extends CI_Controller	 {

	function __construct()
	{
		parent::__construct();  
	}

	public function index()
	{
	    $this->load->model('WarnaModel');
        $data['title'] = ucfirst('Master Warna');
        $data['Warna'] = $this->WarnaModel->get_data();
        $data['AutoNumber'] = $this->WarnaModel->generate_next_number();
		$this->load->view('templates/header', $data);
        $this->load->view('MsWarnaView', $data);
        $this->load->view('templates/footer', $data);
	}

	public function Add()
	{   
	    $this->load->model('WarnaModel'); 
	    $KdWarna = $this->input->post('KdWarna');
	    $NamaWarna = $this->input->post('NamaWarna');  
	    $CreatedBy = 'Admin'; 
	    $existingData = $this->WarnaModel->get_data_by_KdWarna($KdWarna);
	    if ($existingData) {
	    	$data = array(
	            'NamaWarna' => $NamaWarna,  
	        );

	        $this->WarnaModel->update_data($KdWarna, $data);
			echo "Success"; 
	    } 
	    else
	    {
		    $data = array(
		        'KdWarna' => $KdWarna, 
	            'NamaWarna' => $NamaWarna,  
		        'CreatedBy' => $CreatedBy,
	    		'CreatedDate' => date('Y-m-d H:i:s')
		    ); 
		    $this->WarnaModel->insert_data($data); 
			echo "Success"; 
	    }
	} 
    public function Delete()
    {
	    $this->load->model('WarnaModel');
        $KdWarna = $this->input->post('KdWarna'); 
        $this->WarnaModel->delete_data($KdWarna);
		echo "Success";
    }


 
}
