<?php
class WarnaModel  extends CI_Model
{
	public function __construct()
	{
		parent::__construct();
	}

    public function getListWarna()
    {
        return $this->db->get('Warna')->result();
    }

	public function insert_data($data) { 
		$this->db->insert('Warna', $data);
	}

	public function get_data() {
        return $this->db->get('Warna')->result();
    }

	public function update_data($KdWarna, $data)
    {
        $this->db->where('KdWarna', $KdWarna);
        $this->db->update('Warna', $data);
    }
 
    public function delete_data($KdWarna)
    {
        $this->db->where('KdWarna', $KdWarna);
        $this->db->delete('Warna');
    }

	public function get_data_by_KdWarna($KdWarna) { 
        $this->db->where('KdWarna', $KdWarna);
        $query = $this->db->get('Warna'); 
        return $query->row();  
    }
    public function generate_next_number()
    { 
        $this->db->select('KdWarna');
        $this->db->order_by('KdWarna', 'DESC');
        $this->db->limit(1);
        $query = $this->db->get('Warna');
        $last_number = $query->row(); 
        if (!$last_number) {
            return 'WRN-000001';
        } 
        $last_number = $last_number->KdWarna; 
        $last_number = explode('-', $last_number)[1];
        $last_number = (int)$last_number; 
        $next_number = $last_number + 1; 
        $next_number = sprintf('%06d', $next_number); 
        return 'WRN-' . $next_number;
    }
}