<?php
class MerkModel  extends CI_Model
{
	public function __construct()
	{
		parent::__construct();
	}

    public function getListMerk()
    {
        return $this->db->get('Merk')->result();
    }

	public function insert_data($data) { 
		$this->db->insert('Merk', $data);
	}

	public function get_data() {
        return $this->db->get('Merk')->result();
    }

	public function update_data($KdMerk, $data)
    {
        $this->db->where('KdMerk', $KdMerk);
        $this->db->update('Merk', $data);
    }
 
    public function delete_data($KdMerk)
    {
        $this->db->where('KdMerk', $KdMerk);
        $this->db->delete('Merk');
    }

	public function get_data_by_KdMerk($KdMerk) { 
        $this->db->where('KdMerk', $KdMerk);
        $query = $this->db->get('Merk'); 
        return $query->row();  
    }
    public function generate_next_number()
    { 
        $this->db->select('KdMerk');
        $this->db->order_by('KdMerk', 'DESC');
        $this->db->limit(1);
        $query = $this->db->get('Merk');
        $last_number = $query->row(); 
        if (!$last_number) {
            return 'TPE-000001';
        } 
        $last_number = $last_number->KdMerk; 
        $last_number = explode('-', $last_number)[1];
        $last_number = (int)$last_number; 
        $next_number = $last_number + 1; 
        $next_number = sprintf('%06d', $next_number); 
        return 'TPE-' . $next_number;
    }
}