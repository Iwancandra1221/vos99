<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class MsMerk extends CI_Controller	 {

	function __construct()
	{
		parent::__construct();  
	}

	public function index()
	{
	    $this->load->model('MerkModel');
        $data['title'] = ucfirst('Master Merk');
        $data['Merk'] = $this->MerkModel->get_data();
        $data['AutoNumber'] = $this->MerkModel->generate_next_number();
		$this->load->view('templates/header', $data);
        $this->load->view('MsMerkView', $data);
        $this->load->view('templates/footer', $data);
	}

	public function Add()
	{   
	    $this->load->model('MerkModel'); 
	    $KdMerk = $this->input->post('KdMerk');
	    $NamaMerk = $this->input->post('NamaMerk');  
	    $CreatedBy = 'Admin'; 
	    $existingData = $this->MerkModel->get_data_by_KdMerk($KdMerk);
	    if ($existingData) {
	    	$data = array(
	            'NamaMerk' => $NamaMerk,  
	        );

	        $this->MerkModel->update_data($KdMerk, $data);
			echo "Success"; 
	    } 
	    else
	    {
		    $data = array(
		        'KdMerk' => $KdMerk, 
	            'NamaMerk' => $NamaMerk,  
		        'CreatedBy' => $CreatedBy,
	    		'CreatedDate' => date('Y-m-d H:i:s')
		    ); 
		    $this->MerkModel->insert_data($data); 
			echo "Success"; 
	    }
	} 
    public function Delete()
    {
	    $this->load->model('MerkModel');
        $KdMerk = $this->input->post('KdMerk'); 
        $this->MerkModel->delete_data($KdMerk);
		echo "Success";
    }


 
}
