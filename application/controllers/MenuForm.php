<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class MenuForm extends CI_Controller {

    public function __construct() {
        parent::__construct();
        // Load form helper and URL helper
        $this->load->helper(['form', 'url']);
        // Load form validation library
        $this->load->library('form_validation');
    }

    public function index() {
        $this->load->view('menu_form');
    }

    public function submit() {
        // Set form validation rules
        $this->form_validation->set_rules('menu', 'Menu', 'required');
        $this->form_validation->set_rules('input_field', 'Input Field', 'required');

        if ($this->form_validation->run() == FALSE) {
            // Form validation failed
            $this->load->view('menu_form');
        } else {
            // Form validation success
            $data = [
                'menu' => $this->input->post('menu'),
                'input_field' => $this->input->post('input_field')
            ];

            // Here you can write code to save data to database

            // Load success view
            $this->load->view('form_success', $data);
        }
    }
}
