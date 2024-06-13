<?php
class TipeModel  extends CI_Model
{
	public function __construct()
	{
		parent::__construct();
	}

    public function getListTipe()
    {
        return $this->db->get('Tipe')->result();
    }

	public function insert_data($data) { 
		$this->db->insert('Tipe', $data);
	}

	public function get_data() {
        return $this->db->get('Tipe')->result();
    }

	public function update_data($KdTipe, $data)
    {
        $this->db->where('KdTipe', $KdTipe);
        $this->db->update('Tipe', $data);
    }
 
    public function delete_data($KdTipe)
    {
        $this->db->where('KdTipe', $KdTipe);
        $this->db->delete('Tipe');
    }

	public function get_data_by_KdTipe($KdTipe) { 
        $this->db->where('KdTipe', $KdTipe);
        $query = $this->db->get('Tipe'); 
        return $query->row();  
    }
    public function generate_next_number()
    { 
        $this->db->select('KdTipe');
        $this->db->order_by('KdTipe', 'DESC');
        $this->db->limit(1);
        $query = $this->db->get('Tipe');
        $last_number = $query->row(); 
        if (!$last_number) {
            return 'TPE-000001';
        } 
        $last_number = $last_number->KdTipe; 
        $last_number = explode('-', $last_number)[1];
        $last_number = (int)$last_number; 
        $next_number = $last_number + 1; 
        $next_number = sprintf('%06d', $next_number); 
        return 'TPE-' . $next_number;
    }
}