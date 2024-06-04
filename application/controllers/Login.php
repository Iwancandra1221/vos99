<?php
// Login.php (Controller)

defined('BASEPATH') OR exit('No direct script access allowed');

class Login extends CI_Controller	 {

    function __construct()
    {
        parent::__construct(); 
	    $this->load->library('session'); 
    }

    public function index()
    { 
        if ($this->session->userdata('logged_in')) {
            redirect('MainController'); 
        } 
        $data['title'] = "Login";
        $data['version'] = Version; 
        $this->load->view('LoginView', $data); 
    }

    public function process_login()
    { 
	    $this->load->model('UserModel');
        $username = $this->input->post('username');
        $password = $this->input->post('password');  
	    $user = $this->UserModel->find_user($username, $password); 
	    if ($user) { 
	        $this->session->set_userdata('logged_in', TRUE);
	        redirect('MainController');  
	    } else { 
	        $data['error'] = "Username atau password salah.";
	        $this->load->view('LoginView', $data); 
	    } 
    }
}
?>
