<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Signature_model extends CI_Model {

    public function __construct() {
        parent::__construct(); 
        $this->load->database();
    } 
    public function get_data() {
        $result = $this->db->get('signatures')->row();
        if ($result) {
            return $result->signature_image;
        } else {
            return "";
        }
    }

    public function get_datas() {
        return $this->db->get('signatures')->result();
    }


    public function save_signature($signature_data) { 
        $data = array( 
            'signature_image' => $signature_data 
        ); 
        $result = $this->db->insert('signatures', $data); 
        return $result; 
    }

    public function update_signature($id, $signature_data) {
        $data = array(
            'signature_image' => $signature_data
        );
        $this->db->where('id', $id);
        $result = $this->db->update('signatures', $data);
        return $result;
    }

}
