<?php
class ReportModel extends CI_Model
{
	public function __construct()
	{
		parent::__construct();
	} 
    public function GetReportPenjualan($dp1,$dp2)
    {    
        $startDate = date('Y-m-d', strtotime($dp1));  
        $endDate = date('Y-m-d', strtotime($dp2));
        $endDatePlusOne = date('Y-m-d', strtotime($endDate . ' +1 day'));
        $this->db->select('HD.KdPelanggan, P.NamaPelanggan, HD.KdPenjualan, HD.GrandTotal, HD.Lunas, HD.LunasDate, HD.CreatedDate as TglTrans, HD.Tanggal_Tempo, HD.KdTipePembayaran, TP.NamaTipePembayaran');
        $this->db->from('PenjualanHD HD');
        $this->db->join('Pelanggan P', 'HD.KdPelanggan = P.KdPelanggan');
        $this->db->join('TipePembayaran TP', 'HD.KdTipePembayaran = TP.KdTipePembayaran');
        
        if (!empty($startDate) && !empty($endDate)) {
            $this->db->where('HD.CreatedDate >=', $startDate);
            $this->db->where('HD.CreatedDate <', $endDatePlusOne);
        }

        $this->db->order_by('P.NamaPelanggan', 'ASC');
        $this->db->order_by('HD.KdPenjualan', 'ASC');
        $query = $this->db->get();
        return $query->result();
    } 

    public function GetReportBarang($dp1, $dp2)
    {
        $startDate = date('Y-m-d', strtotime($dp1));  
        $endDate = date('Y-m-d', strtotime($dp2));
        $endDatePlusOne = date('Y-m-d', strtotime($endDate . ' +1 day'));
        $this->db->select('DT.KdBarang, B.NamaBarang, Ti.NamaTipe,Mr.NamaMerk, Wr.NamaWarna,
            DT.Harga as Harga , B.Harga as HargaAsli , 
            SUM(DT.Qty) as Total, SUM(DT.Qty * DT.Harga) as GrandTotal, SUM(DT.Qty * B.Harga) as GrandTotalAsli ');
        $this->db->from('PenjualanHD HD');
        $this->db->join('PenjualanDT DT', 'HD.KdPenjualan = DT.KdPenjualan');
        $this->db->join('Barang B', 'DT.KdBarang = B.KdBarang');
        $this->db->join('Tipe Ti', 'B.Tipe = Ti.KdTipe', 'left');
        $this->db->join('Merk Mr', 'B.Merk = Mr.KdMerk', 'left');
        $this->db->join('Warna Wr', 'B.Warna = Wr.KdWarna', 'left');
        
        if (!empty($startDate) && !empty($endDate)) {
            $this->db->where('HD.CreatedDate >=', $startDate);
            $this->db->where('HD.CreatedDate <', $endDatePlusOne);
        }

        $this->db->group_by('DT.KdBarang, B.NamaBarang, Ti.NamaTipe,Mr.NamaMerk, Wr.NamaWarna,
            DT.Harga, B.Harga');
        $this->db->order_by('B.NamaBarang', 'ASC');
        $query = $this->db->get();
        return $query->result();
    }

    public function GetFilteredSalesReport($startDate, $endDate)
    {
        $startDate = date('Y-m-d', strtotime($startDate));  
        $endDate = date('Y-m-d', strtotime($endDate));
        $endDatePlusOne = date('Y-m-d', strtotime($endDate . ' +1 day'));

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
            SUM(DT.Qty * B.Harga) AS TotalModal,
            (HD.GrandTotal - SUM(DT.Qty * B.Harga)) AS LabaRugi
        ');
        $this->db->from('PenjualanHD HD');
        $this->db->join('PenjualanDT DT', 'HD.KdPenjualan = DT.KdPenjualan');
        $this->db->join('Barang B', 'DT.KdBarang = B.KdBarang');
        $this->db->join('Pelanggan P', 'HD.KdPelanggan = P.KdPelanggan');
        $this->db->join('TipePembayaran TP', 'HD.KdTipePembayaran = TP.KdTipePembayaran');
        $this->db->where('HD.CreatedDate >=', $startDate);
        $this->db->where('HD.CreatedDate <', $endDatePlusOne);
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
            HD.GrandTotal
        ');
        $this->db->order_by('P.NamaPelanggan, HD.KdPenjualan');
        
        $query = $this->db->get();
        return $query->result();
    }

}