<?php
class UserModel  extends CI_Model
{
	public function __construct()
	{
		parent::__construct();
	}
	public function insert_data($data) { 
		$this->db->insert('User', $data);
	}

	public function get_data() {
        return $this->db->get('User')->result();
    }

	public function update_data($KdUser, $data)
    {
        $this->db->where('KdUser', $KdUser);
        $this->db->update('User', $data);
    }
 
    public function delete_data($KdUser)
    {
        $this->db->where('KdUser', $KdUser);
        $this->db->delete('User');
    }

	public function get_data_by_KdUser($KdUser) { 
        $this->db->where('KdUser', $KdUser);
        $query = $this->db->get('User'); 
        return $query->row();  
    }
    public function generate_next_number()
    { 
        $this->db->select('KdUser');
        $this->db->order_by('KdUser', 'DESC');
        $this->db->limit(1);
        $query = $this->db->get('User');
        $last_number = $query->row(); 
        if (!$last_number) {
            return 'USR-000001';
        } 
        $last_number = $last_number->KdUser; 
        $last_number = explode('-', $last_number)[1];
        $last_number = (int)$last_number; 
        $next_number = $last_number + 1; 
        $next_number = sprintf('%06d', $next_number); 
        return 'USR-' . $next_number;
    }

    public function find_user($username, $password) {
        // Cari pengguna berdasarkan KdUser
        $this->db->where('KdUser', $username);
        $this->db->where('Password', $password);
        $query = $this->db->get('User');
        $user = $query->row(); 

        // Jika tidak ditemukan, cari berdasarkan NamaUser
        if (!$user) {
            $this->db->where('NamaUser', $username);
            $this->db->where('Password', $password);
            $query = $this->db->get('User');
            $user = $query->row(); 
        }

        return $user;
    }


}