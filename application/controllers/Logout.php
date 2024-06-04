<?php
// Login.php (Controller)

defined('BASEPATH') OR exit('No direct script access allowed');

class Logout extends CI_Controller    {

    function __construct()
    {
        parent::__construct(); 
        $this->load->library('session'); 
    }

    public function index()
    { 
        $this->session->set_userdata('logged_in', false);
        redirect('Login');  
    } 
}
?>
