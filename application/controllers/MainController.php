<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class MainController extends CI_Controller	 {

	function __construct()
	{
		parent::__construct(); 
		$this->version = "1.0.0";
	}

	public function index()
	{
        $data['title'] = "Home";
        $data['version'] = $this->version;
		$this->load->view('templates/header', $data);
        $this->load->view('home', $data);
        $this->load->view('templates/footer', $data);
	}
 
}
