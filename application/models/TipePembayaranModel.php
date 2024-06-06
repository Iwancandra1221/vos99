<?php
class TipePembayaranModel  extends CI_Model
{
	public function __construct()
	{
		parent::__construct();
	}

    public function getListTipePembayaran()
    {
        return $this->db->get('TipePembayaran')->result();
    }

	public function insert_data($data) { 
		$this->db->insert('TipePembayaran', $data);
	}

	public function get_data() {
        return $this->db->get('TipePembayaran')->result();
    }

	public function update_data($KdTipePembayaran, $data)
    {
        $this->db->where('KdTipePembayaran', $KdTipePembayaran);
        $this->db->update('TipePembayaran', $data);
    }
 
    public function delete_data($KdTipePembayaran)
    {
        $this->db->where('KdTipePembayaran', $KdTipePembayaran);
        $this->db->delete('TipePembayaran');
    }

	public function get_data_by_KdTipePembayaran($KdTipePembayaran) { 
        $this->db->where('KdTipePembayaran', $KdTipePembayaran);
        $query = $this->db->get('TipePembayaran'); 
        return $query->row();  
    }
    public function generate_next_number()
    { 
        $this->db->select('KdTipePembayaran');
        $this->db->order_by('KdTipePembayaran', 'DESC');
        $this->db->limit(1);
        $query = $this->db->get('TipePembayaran');
        $last_number = $query->row(); 
        if (!$last_number) {
            return 'TYP-000001';
        } 
        $last_number = $last_number->KdTipePembayaran; 
        $last_number = explode('-', $last_number)[1];
        $last_number = (int)$last_number; 
        $next_number = $last_number + 1; 
        $next_number = sprintf('%06d', $next_number); 
        return 'TYP-' . $next_number;
    }
}