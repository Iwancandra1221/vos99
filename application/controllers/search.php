<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Search extends CI_Controller {

    function __construct()
    {
        parent::__construct();   
        $this->load->model('BarangModel');
    }

    public function index() {
        header('Content-Type: application/json');
 
        $data = $this->BarangModel->get_datas();
        $items = [];  
        foreach ($data as $key => $value) {
            $items[] = ["code" => $value->KdBarang, "name" => $value->NamaBarang, "warna" => $value->Warna]; 
        } 
        if ($this->input->get('q') != "")
        {
            $query = strtolower($this->input->get('q'));
            $results = array_filter($items, function($item) use ($query) {
                return strpos(strtolower($item['code']), $query) !== false || strpos(strtolower($item['name']), $query) !== false;
            });

            echo json_encode(array_values($results));
        }
        
    }
}
?>
