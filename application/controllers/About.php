<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class About extends CI_Controller	 {

	function __construct()
	{
		parent::__construct();  
	}

	public function index()
	{
        $data['title'] = ucfirst('About');
		$this->load->view('templates/header', $data);
        $this->load->view('About', $data);
        $this->load->view('templates/footer', $data);
	}
 
}
