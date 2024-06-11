<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class SignatureController extends CI_Controller {

    public function __construct() {
        parent::__construct(); 
        $this->load->model('Signature_model');
    }

    public function index() {  
        $data['title'] = ucfirst('Tanda Tangan Electronic'); 
        $data['listTandaTangan'] = $this->Signature_model->get_data();
        $this->load->view('templates/header', $data);
        $this->load->view('signatureview');
        $this->load->view('templates/footer', $data);
    }

    public function save_signature() {  
        $signature_data = $this->input->post('signature_data'); 
        $data = $this->Signature_model->get_datas(); 
        if ($data) { 
            foreach ($data as $dataRow) {
                $result = $this->Signature_model->update_signature($dataRow->id, $signature_data); 
            }  
            if ($result) {
                echo "Success";
            } else {
                echo "Failed to update signature.";
            }
        }
        else
        { 
            $signature_data = $this->input->post('signature_data');  
            $result = $this->Signature_model->save_signature($signature_data); 
            if ($result) {
                echo "Success";
            } else {
                echo "Failed to save signature.";
            }
        }
    } 
}
