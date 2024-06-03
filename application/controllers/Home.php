<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Home extends CI_Controller	 {

	function __construct()
	{
		parent::__construct();  
	}

	public function index()
	{
        $data['title'] = ucfirst('Home');
		$this->load->view('templates/header', $data);
        $this->load->view('Home', $data);
        $this->load->view('templates/footer', $data);
	}
 
}
