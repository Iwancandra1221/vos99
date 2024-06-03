<?php
class BarangModel  extends CI_Model
{
	public function __construct()
	{
		parent::__construct();
	}
	public function insert_data($data) { 
		$this->db->insert('Barang', $data);
	}

	public function get_data() {
        return $this->db->get('Barang')->result();
    }

	public function update_data($KdBarang, $data)
    {
        $this->db->where('KdBarang', $KdBarang);
        $this->db->update('Barang', $data);
    }
 
    public function delete_data($KdBarang)
    {
        $this->db->where('KdBarang', $KdBarang);
        $this->db->delete('Barang');
    }

	public function get_data_by_KdBarang($KdBarang) { 
        $this->db->where('KdBarang', $KdBarang);
        $query = $this->db->get('Barang'); 
        return $query->row();  
    }
    public function generate_next_number()
    { 
        $this->db->select('KdBarang');
        $this->db->order_by('KdBarang', 'DESC');
        $this->db->limit(1);
        $query = $this->db->get('Barang');
        $last_number = $query->row(); 
        if (!$last_number) {
            return 'BRG-000001';
        } 
        $last_number = $last_number->KdBarang; 
        $last_number = explode('-', $last_number)[1];
        $last_number = (int)$last_number; 
        $next_number = $last_number + 1; 
        $next_number = sprintf('%06d', $next_number); 
        return 'BRG-' . $next_number;
    }
}