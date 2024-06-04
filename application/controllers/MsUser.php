<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class MsUser extends CI_Controller	 {

	function __construct()
	{
		parent::__construct();  
	}

	public function index()
	{
	    $this->load->model('UserModel');
        $data['title'] = ucfirst('Master User');
        $data['User'] = $this->UserModel->get_data();
        $data['AutoNumber'] = $this->UserModel->generate_next_number();
		$this->load->view('templates/header', $data);
        $this->load->view('MsUserView', $data);
        $this->load->view('templates/footer', $data);
	}

	public function Add()
	{   
	    $this->load->model('UserModel'); 
	    $KdUser = $this->input->post('KdUser');
	    $NamaUser = $this->input->post('NamaUser');
	    $Password = $this->input->post('Password');
	    $Role = $this->input->post('Role'); 
	    $CreatedBy = 'Admin'; 
	    $existingData = $this->UserModel->get_data_by_KdUser($KdUser);
	    if ($existingData) {
	    	$data = array(
	            'NamaUser' => $NamaUser,
	            'Password' => $Password,
	            'Role' => $Role 
	        );

	        $this->UserModel->update_data($KdUser, $data);
			echo "Success"; 
	    } 
	    else
	    {
		    $data = array(
		        'KdUser' => $KdUser, 
	            'NamaUser' => $NamaUser,
	            'Password' => $Password,
	            'Role' => $Role,
		        'CreatedBy' => $CreatedBy,
	    		'CreatedDate' => date('Y-m-d H:i:s')
		    ); 
		    $this->UserModel->insert_data($data); 
			echo "Success"; 
	    }
	} 
    public function Delete()
    {
	    $this->load->model('UserModel');
        $KdUser = $this->input->post('KdUser'); 
        $this->UserModel->delete_data($KdUser);
		echo "Success";
    }


 
}
