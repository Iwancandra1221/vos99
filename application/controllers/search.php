<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Search extends CI_Controller {

    public function index() {
        header('Content-Type: application/json');

        // Dummy data for items
        $items = [
            ["code" => "BRG001", "name" => "Barang Satu"],
            ["code" => "BRG002", "name" => "Barang Dua"],
            ["code" => "BRG003", "name" => "Barang Tiga"],
            ["code" => "BRG004", "name" => "Barang Empat"],
            // Add more items as needed
        ];
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
