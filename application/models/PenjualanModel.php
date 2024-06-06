<?php
class PenjualanModel extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
    }

    public function insert_hd($data)
    {
        $this->db->insert('PenjualanHD', $data);
    }

    public function insert_dt($data)
    {
        $this->db->insert('PenjualanDT', $data);
    }

    public function get_hd_by_KdPenjualan($KdPenjualan)
    {
        $this->db->where('HD.KdPenjualan', $KdPenjualan); 
        $this->db->select('HD.KdPenjualan, HD.KdPelanggan, HD.KdTipePembayaran, TYP.NamaTipePembayaran, PLG.NamaPelanggan, PLG.NoHp, HD.GrandTotal');
        $this->db->from('PenjualanHD HD');
        $this->db->join('Pelanggan PLG', 'HD.KdPelanggan = PLG.KdPelanggan', 'left');
        $this->db->join('TipePembayaran TYP', 'HD.KdTipePembayaran = TYP.KdTipePembayaran', 'left');
        return $this->db->get()->row(); 
    }

    public function get_hd_by_KdPenjualans($KdPenjualan)
    {
        $this->db->where('DT.KdPenjualan', $KdPenjualan); 
        $this->db->select('DT.KdPenjualan, DT.Qty, DT.Harga, DT.Total, BRG.NamaBarang, BRG.Warna');
        $this->db->from('PenjualanDT DT');
        $this->db->join('Barang BRG', 'DT.KdBarang = BRG.KdBarang', 'left');
        $query = $this->db->get();
        return $query->result();
    }

    public function get_dt_by_KdPenjualan($KdPenjualan)
    {
        $this->db->where('KdPenjualan', $KdPenjualan);
        $query = $this->db->get('PenjualanDT');
        return $query->result(); 
    }

    public function get_list_data() {
        $this->db->select('HD.KdPenjualan, HD.KdPelanggan, TYP.NamaTipePembayaran, PLG.NamaPelanggan, PLG.NoHp, HD.GrandTotal');
        $this->db->from('PenjualanHD HD');
        $this->db->join('Pelanggan PLG', 'HD.KdPelanggan = PLG.KdPelanggan', 'left');
        $this->db->join('TipePembayaran TYP', 'HD.KdTipePembayaran = TYP.KdTipePembayaran', 'left');
        return $this->db->get()->result();
    }


    public function get_data() {
        return $this->db->get('PenjualanHD')->result();
    }

    public function update_data($KdPenjualan, $data)
    {
        $this->db->where('KdPenjualan', $KdPenjualan);
        $this->db->update('PenjualanHD', $data);
    }

    public function delete_data_dt($KdPenjualan)
    { 
        $this->db->where('KdPenjualan', $KdPenjualan);
        $this->db->delete('PenjualanDT');
    }

    public function delete_data($KdPenjualan)
    {
        $this->db->where('KdPenjualan', $KdPenjualan);
        $this->db->delete('PenjualanHD');
        $this->db->where('KdPenjualan', $KdPenjualan);
        $this->db->delete('PenjualanDT');
    }

    public function get_data_by_KdPenjualan($KdPenjualan)
    {
        $this->db->where('KdPenjualan', $KdPenjualan);
        $query = $this->db->get('PenjualanHD');
        return $query->row();
    }

    public function generate_next_number()
    {
        $this->db->select('KdPenjualan');
        $this->db->order_by('KdPenjualan', 'DESC');
        $this->db->limit(1);
        $query = $this->db->get('PenjualanHD');
        $last_number = $query->row();
        if (!$last_number) {
            return 'PNJ-000001';
        }
        $last_number = $last_number->KdPenjualan;
        $last_number = explode('-', $last_number)[1];
        $last_number = (int)$last_number;
        $next_number = $last_number + 1;
        $next_number = sprintf('%06d', $next_number);
        return 'PNJ-' . $next_number;
    }
}
?>
