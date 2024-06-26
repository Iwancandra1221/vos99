<?php
class ReportModel extends CI_Model
{
	public function __construct()
	{
		parent::__construct();
	} 
    public function GetReportPenjualan($dp1,$dp2)
    {    
        $this->db->select('HD.KdPelanggan, P.NamaPelanggan, HD.KdPenjualan, HD.GrandTotal, HD.Lunas, HD.LunasDate, HD.CreatedDate as TglTrans, HD.Tanggal_Tempo, HD.KdTipePembayaran, TP.NamaTipePembayaran');
        $this->db->from('PenjualanHD HD');
        $this->db->join('Pelanggan P', 'HD.KdPelanggan = P.KdPelanggan');
        $this->db->join('TipePembayaran TP', 'HD.KdTipePembayaran = TP.KdTipePembayaran');
        
        if (!empty($dp1) && !empty($dp2)) {
            $this->db->where('HD.CreatedDate >=', $dp1);
            $this->db->where('HD.CreatedDate <=', $dp2);
        }

        $this->db->order_by('P.NamaPelanggan', 'ASC');
        $this->db->order_by('HD.KdPenjualan', 'ASC');
        $query = $this->db->get();
        return $query->result();
    } 

    public function GetReportBarang($dp1, $dp2)
    {
        $this->db->select('DT.KdBarang, B.NamaBarang, DT.Harga, SUM(DT.Qty) as Total, (SUM(DT.Qty) * DT.Harga) as GrandTotal');
        $this->db->from('PenjualanHD HD');
        $this->db->join('PenjualanDT DT', 'HD.KdPenjualan = DT.KdPenjualan');
        $this->db->join('Barang B', 'DT.KdBarang = B.KdBarang');
        
        if (!empty($dp1) && !empty($dp2)) {
            $this->db->where('HD.CreatedDate >=', $dp1);
            $this->db->where('HD.CreatedDate <=', $dp2);
        }

        $this->db->group_by('DT.KdBarang, B.NamaBarang, DT.Harga');
        $this->db->order_by('B.NamaBarang', 'ASC');
        $query = $this->db->get();
        return $query->result();
    }

    public function GetFilteredSalesReport($startDate, $endDate)
    {
        $this->db->select('
            HD.KdPelanggan, 
            P.NamaPelanggan, 
            HD.KdPenjualan,  
            HD.Lunas,
            HD.LunasDate,
            HD.CreatedDate AS TglTrans,
            HD.Tanggal_Tempo, 
            HD.KdTIpePembayaran, 
            TP.NamaTipePembayaran,
            HD.GrandTotal as TotalJual,
            (SUM(DT.Qty) * B.Harga) AS TotalModal,
            (HD.GrandTotal - (SUM(DT.Qty) * B.Harga)) as LabaRugi
        ');
        $this->db->from('PenjualanHD HD');
        $this->db->join('PenjualanDT DT', 'HD.KdPenjualan = DT.KdPenjualan');
        $this->db->join('Barang B', 'DT.KdBarang = B.KdBarang');
        $this->db->join('Pelanggan P', 'HD.KdPelanggan = P.KdPelanggan');
        $this->db->join('TipePembayaran TP', 'HD.KdTipePembayaran = TP.KdTipePembayaran');
        $this->db->where('HD.CreatedDate >=', $startDate);
        $this->db->where('HD.CreatedDate <=', $endDate);
        $this->db->group_by('
            HD.KdPelanggan, 
            P.NamaPelanggan, 
            HD.KdPenjualan, 
            HD.Lunas,
            HD.LunasDate,
            HD.CreatedDate,
            HD.Tanggal_Tempo, 
            HD.KdTIpePembayaran, 
            TP.NamaTipePembayaran,
            B.Harga,
            HD.GrandTotal
        ');
        $this->db->order_by('P.NamaPelanggan, HD.KdPenjualan');
        
        $query = $this->db->get();
        return $query->result();
    }

}