<?php
class StockBarangModel  extends CI_Model
{
	public function __construct()
	{
		parent::__construct();
	}

    public function getListStockBarang()
    {
        return $this->db->get('StockBarang')->result();
    }

    public function recordTransaction($data)
    { 
        $this->db->insert('TransaksiBarang', $data); 
        if ($data['transaksi_type'] == 'in') { 
            $this->db->set('Qty_Real', 'Qty_Real + ' . $data['jumlah'], FALSE);
            $this->db->set('Qty_In', 'Qty_In + ' . $data['jumlah'], FALSE);
        } elseif ($data['transaksi_type'] == 'out') { 
            $this->db->set('Qty_Real', 'Qty_Real - ' . $data['jumlah'], FALSE);
            $this->db->set('Qty_Out', 'Qty_Out + ' . $data['jumlah'], FALSE);
        }
        $this->db->where('KdStockBarang', $data['KdStockBarang']);
        $this->db->update('StockBarang');
    }

	public function insert_data($data) { 
		$this->db->insert('StockBarang', $data);
	}

	public function get_data() {
        return $this->db->get('StockBarang')->result();
    }

	public function update_data($KdStockBarang, $data)
    {
        $this->db->where('KdStockBarang', $KdStockBarang);
        $this->db->update('StockBarang', $data);
    }
 
    public function delete_data($KdStockBarang)
    {
        $this->db->where('KdStockBarang', $KdStockBarang);
        $this->db->delete('StockBarang');
    }

	public function get_data_by_KdStockBarang($KdStockBarang) { 
        $this->db->where('KdStockBarang', $KdStockBarang);
        $query = $this->db->get('StockBarang'); 
        return $query->row();  
    }
    public function generate_next_number()
    { 
        $this->db->select('KdStockBarang');
        $this->db->order_by('KdStockBarang', 'DESC');
        $this->db->limit(1);
        $query = $this->db->get('StockBarang');
        $last_number = $query->row(); 
        if (!$last_number) {
            return 'STC-000001';
        } 
        $last_number = $last_number->KdStockBarang; 
        $last_number = explode('-', $last_number)[1];
        $last_number = (int)$last_number; 
        $next_number = $last_number + 1; 
        $next_number = sprintf('%06d', $next_number); 
        return 'STC-' . $next_number;
    }

    public function generate_next_number_trans()
    { 
        $this->db->select('IdTransaksi');
        $this->db->order_by('IdTransaksi', 'DESC');
        $this->db->limit(1);
        $query = $this->db->get('TransaksiBarang');
        $last_number = $query->row(); 
        if (!$last_number) {
            return 'TRS-000001';
        } 
        $last_number = $last_number->IdTransaksi; 
        $last_number = explode('-', $last_number)[1];
        $last_number = (int)$last_number; 
        $next_number = $last_number + 1; 
        $next_number = sprintf('%06d', $next_number); 
        return 'TRS-' . $next_number;
    }
}