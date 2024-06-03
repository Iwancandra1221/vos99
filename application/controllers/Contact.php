<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Contact extends CI_Controller	 {

	function __construct()
	{
		parent::__construct();  
	}

	public function index()
	{
        $data['title'] = ucfirst('Contact');
		$this->load->view('templates/header', $data);
        $this->load->view('Contact', $data);
        $this->load->view('templates/footer', $data);
	}
 
}
