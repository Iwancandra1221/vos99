<?php
class BarangModel  extends CI_Model
{
	public function __construct()
	{
		parent::__construct();
	}

    public function getListBarang()
    {
        return $this->db->get('Barang')->result();
    }

	public function insert_data($data) { 
		$this->db->insert('Barang', $data);
	}

	public function get_data() {
        return $this->db->get('Barang')->result();
    }


    public function get_datas()
    {   
        $this->db->select('BRG.*, 
            WRN.NamaWarna as Warna, BRG.Warna as KdWarna,
            TPE.NamaTipe as Tipe, BRG.Tipe as KdTipe, 
            MRK.NamaMerk as Merk, BRG.Merk as KdMerk');
        $this->db->from('Barang BRG');
        $this->db->join('Warna WRN', 'BRG.Warna = WRN.KdWarna', 'left');
        $this->db->join('Tipe TPE', 'BRG.Tipe = TPE.KdTipe', 'left');
        $this->db->join('Merk MRK', 'BRG.Merk = MRK.KdMerk', 'left');
        $query = $this->db->get();
        return $query->result();
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

    public function get_qty($KdBarang) {
        $this->db->select('Qty');
        $this->db->from('Barang');
        $this->db->where('KdBarang', $KdBarang);
        $query = $this->db->get();
        return $query->row()->Qty;
    }
 
    public function get_qty_jual($KdBarang) {
        $this->db->select('Qty_Jual');
        $this->db->from('Barang');
        $this->db->where('KdBarang', $KdBarang);
        $query = $this->db->get();
        return $query->row()->Qty_Jual;
    }
    public function update_qty_after_sale($KdBarang, $qty_sold) { 
        $current_qty = $this->get_qty($KdBarang);  
        $current_qty_jual = $this->get_qty_jual($KdBarang);  
        $new_qty = $current_qty - $qty_sold; 
        $new_qty_jual = $current_qty_jual + $qty_sold;    
        $data = array(
            'Qty' => $new_qty,
            'Qty_Jual' => $new_qty_jual
        );

        $this->db->where('KdBarang', $KdBarang);
        return $this->db->update('Barang', $data);
    }
 

    public function update_qty_edit($KdBarang, $qty_sold) { 
        $current_qty = $this->get_qty($KdBarang);  
        $current_qty_jual = $this->get_qty_jual($KdBarang);  
        $new_qty = $current_qty + $qty_sold; 
        $new_qty_jual = $current_qty_jual - $qty_sold;   
        $data = array(
            'Qty' => $new_qty,
            'Qty_Jual' => $new_qty_jual
        );

        $this->db->where('KdBarang', $KdBarang);
        return $this->db->update('Barang', $data);
    }

}