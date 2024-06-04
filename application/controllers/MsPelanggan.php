<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class MsPelanggan extends CI_Controller	 {

	function __construct()
	{
		parent::__construct();  
	}

	public function index()
	{
	    $this->load->model('PelangganModel');
        $data['title'] = ucfirst('Master Pelanggan');
        $data['Pelanggan'] = $this->PelangganModel->get_data();
        $data['AutoNumber'] = $this->PelangganModel->generate_next_number();
		$this->load->view('templates/header', $data);
        $this->load->view('MsPelangganView', $data);
        $this->load->view('templates/footer', $data);
	}

	public function Add()
	{   
	    $this->load->model('PelangganModel'); 
	    $KdPelanggan = $this->input->post('KdPelanggan');
	    $NamaPelanggan = $this->input->post('NamaPelanggan'); 
	    $NoHp = $this->input->post('NoHp'); 
	    $CreatedBy = 'Admin'; 
	    $existingData = $this->PelangganModel->get_data_by_KdPelanggan($KdPelanggan);
	    if ($existingData) {
	    	$data = array(
	            'NamaPelanggan' => $NamaPelanggan, 
	            'NoHp' => $NoHp 
	        );

	        $this->PelangganModel->update_data($KdPelanggan, $data);
			echo "Success"; 
	    } 
	    else
	    {
		    $data = array(
		        'KdPelanggan' => $KdPelanggan, 
	            'NamaPelanggan' => $NamaPelanggan, 
	            'NoHp' => $NoHp,
		        'CreatedBy' => $CreatedBy,
	    		'CreatedDate' => date('Y-m-d H:i:s')
		    ); 
		    $this->PelangganModel->insert_data($data); 
			echo "Success"; 
	    }
	} 
    public function Delete()
    {
	    $this->load->model('PelangganModel');
        $KdPelanggan = $this->input->post('KdPelanggan'); 
        $this->PelangganModel->delete_data($KdPelanggan);
		echo "Success";
    }


 
}
