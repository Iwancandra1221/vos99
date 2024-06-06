<?php
class PelangganModel  extends CI_Model
{
	public function __construct()
	{
		parent::__construct();
	}

    public function getListPelanggan()
    {
        return $this->db->get('Pelanggan')->result();
    }

	public function insert_data($data) { 
		$this->db->insert('Pelanggan', $data);
	}

	public function get_data() {
        return $this->db->get('Pelanggan')->result();
    }

	public function update_data($KdPelanggan, $data)
    {
        $this->db->where('KdPelanggan', $KdPelanggan);
        $this->db->update('Pelanggan', $data);
    }
 
    public function delete_data($KdPelanggan)
    {
        $this->db->where('KdPelanggan', $KdPelanggan);
        $this->db->delete('Pelanggan');
    }

	public function get_data_by_KdPelanggan($KdPelanggan) { 
        $this->db->where('KdPelanggan', $KdPelanggan);
        $query = $this->db->get('Pelanggan'); 
        return $query->row();  
    }
    public function generate_next_number()
    { 
        $this->db->select('KdPelanggan');
        $this->db->order_by('KdPelanggan', 'DESC');
        $this->db->limit(1);
        $query = $this->db->get('Pelanggan');
        $last_number = $query->row(); 
        if (!$last_number) {
            return 'PLG-000001';
        } 
        $last_number = $last_number->KdPelanggan; 
        $last_number = explode('-', $last_number)[1];
        $last_number = (int)$last_number; 
        $next_number = $last_number + 1; 
        $next_number = sprintf('%06d', $next_number); 
        return 'PLG-' . $next_number;
    }
}