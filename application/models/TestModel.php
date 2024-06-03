<?php
class TestModel extends CI_Model
{
	public function __construct()
	{
		parent::__construct();
	}
	public function getUser($where){
		$this->db->select("*");
		$result = $this->db->get('USERINFO');
		return $result;
	}
	function query1(){
	
		$query = "SELECT w.Wilayah,
       a.ParentDiv,
       a.Divisi,
       a.Merk,
       d.Nm_Plg,
       W.Nm_Wil AS Alm_Plg,
       w.Kota,
       SUM(Total_Jual) AS Total_Jual,
       (-1) * (SUM(TotalRB)+SUM(TotalRC)) AS TotalRetur,
       (-1) * SUM(TotalDisc) AS TotalDisc,
       SUM(Total_Jual) - (SUM(TotalRB) + SUM(TotalRC) + SUM(TotalDisc)) AS Omzet_Netto,
       d.Partner_Type
FROM TblMsDealer d
INNER JOIN TblWilayahKhusus w ON d.Kd_Plg=w.Kd_Plg
LEFT JOIN
  (SELECT a.Wilayah,
          a.ParentDiv,
          a.Divisi,
          a.Merk,
          a.Kd_Plg,
          a.Nm_Plg,
          a.Alm_Plg,
          a.Kota,
          Total_Jual,
          0 AS TotalRB,
          0 AS TotalRC,
          0 AS TotalDisc,
          Partner_Type
   FROM OmzetNetto_Penjualan_Detail('2024-01-01', '2024-01-01', '', '', '', '') a
   UNION ALL SELECT a.Wilayah,
                    a.ParentDiv,
                    a.Divisi,
                    a.Merk,
                    a.Kd_Plg,
                    a.Nm_Plg,
                    a.Alm_Plg,
                    a.Kota,
                    0 AS Total_Jual,
                    (CASE WHEN SUBSTRING(a.No_Ref,7,1) = 'B' THEN Total_Retur ELSE 0 END) AS Total_RB,
                    (CASE WHEN SUBSTRING(a.No_Ref,7,1) <> 'B' THEN Total_Retur ELSE 0 END) AS TotalRC,
                    0 AS TotalDisc,
                    Partner_Type
   FROM TotalRetur_Penjualan_Detail('2024-01-01', '2024-01-01', '', '', '', '') a
   WHERE a.Tgl_Faktur>='2017-09-01'
   UNION ALL SELECT a.Wilayah,
                    a.ParentDiv,
                    a.Divisi,
                    a.Merk,
                    a.Kd_Plg,
                    a.Nm_Plg,
                    a.Alm_Kirim,
                    a.Kota,
                    0 AS Total_Jual,
                    0 AS Total_RB,
                    0 AS Total_RC,
                    a.Total AS Total_Disc,
                    Partner_Type
   FROM OmzetNetto_BiayaPenjualan ('2024-01-01', '2024-01-01', '', '') a
   WHERE a.Tgl_Trans>='2017-09-01') a ON d.kd_plg = a.Kd_Plg
WHERE d.Aktif='Y'
  AND w.type_shipment_code='HO'
  AND ISNULL(d.Partner_Type,'')<>''
  AND ISNULL(d.Nm_Plg,'')<>''
GROUP BY w.WILAYAH,
         a.ParentDiv,
         a.Divisi,
         a.Merk,
         d.Nm_Plg,
         w.Nm_Wil,
         w.Kota,
         d.Partner_Type
ORDER BY d.Partner_Type,
         w.WILAYAH,
         d.Nm_Plg";
		$res = $this->db->query($query);
		if ($res->num_rows() > 0)		
			return $res->result();
		else
			return array(); 
	}
	public function getReport(){
		$query = <<<SQL
		DECLARE @tglawal varchar(MAX) = '2023-06-16';
		DECLARE @tglakhir varchar(MAX) = '2023-06-30';
		SELECT Dt.divisi,
		       Dt.merk,
		       CASE
		           WHEN (Gd.Wilayah IS NULL
		                 OR Gd.Wilayah='')
		                AND (Gab.Kd_Lokasi=''
		                     OR Gab.Kd_Lokasi='') THEN 'JAKARTA'
		           WHEN (Gd.Wilayah IS NULL
		                 OR Gd.Wilayah='') THEN Cb.Kota
		           ELSE Gd.Wilayah
		       END AS Kd_Lokasi,
		       Gab.Kd_Brg,
		       Dt.nama,
		       SUM(QTY_MAJOR) AS Qty_MAJOR,
		       SUM(QTY_BPB_MAJOR) AS QTY_BPB_MAJOR,
		       CASE
		           WHEN SUM(Gab.QTY_MAJOR) > 0 THEN ROUND(SUM(Gab.QTY_BPB_MAJOR) * 100 / SUM(Gab.QTY_MAJOR) , 2)
		           ELSE 0
		       END AS PemenuhanPO_MAJOR,
		       SUM(QTY_RO) AS Qty_RO,
		       SUM(QTY_BPB_RO) AS QTY_BPB_RO,
		       CASE
		           WHEN SUM(Gab.QTY_RO) > 0 THEN ROUND(SUM(Gab.QTY_BPB_RO) * 100 / SUM(Gab.QTY_RO) , 2)
		           ELSE 0
		       END AS PemenuhanPO_RO,
		       SUM(QTY_RO_CAMPAIGN) AS Qty_RO_CAMPAIGN,
		       SUM(QTY_BPB_RO_CAMPAIGN) AS QTY_BPB_RO_CAMPAIGN,
		       CASE
		           WHEN SUM(Gab.QTY_RO_CAMPAIGN) > 0 THEN ROUND(SUM(Gab.QTY_BPB_RO_CAMPAIGN) * 100 / SUM(Gab.QTY_RO_CAMPAIGN) , 2)
		           ELSE 0
		       END AS PemenuhanPO_RO_CAMPAIGN,
		       SUM(QTY_OTHER) AS Qty_OTHER,
		       SUM(QTY_BPB_OTHER) AS QTY_BPB_OTHER,
		       CASE
		           WHEN SUM(Gab.QTY_OTHER) > 0 THEN ROUND(SUM(Gab.QTY_BPB_OTHER) * 100 / SUM(Gab.QTY_OTHER) , 2)
		           ELSE 0
		       END AS PemenuhanPO_OTHER,
		       SUM(QTY_PO) AS Qty_PO,
		       SUM(Qty_BPB) AS Qty_BPB,
		       CASE
		           WHEN SUM(Gab.QTY_PO) > 0 THEN ROUND(SUM(Gab.Qty_BPB) * 100 / SUM(Gab.QTY_PO) , 2)
		           ELSE 0
		       END AS PemenuhanPO
		FROM
		  (SELECT a.No_PrePO,
		          a.Kd_Lokasi,
		          a.No_PO,
		          b.Kd_Brg,
		          CASE
		              WHEN a.PO_Type IN ('PO MAJOR') THEN b.Qty
		              ELSE 0
		          END AS QTY_MAJOR,
		          CASE
		              WHEN a.PO_Type IN ('PO RO')
		                   AND isnull(x.CampaignPlanID, '') = '' THEN b.Qty
		              ELSE 0
		          END AS QTY_RO,
		          CASE
		              WHEN a.PO_Type IN ('PO RO')
		                   AND isnull(x.CampaignPlanID, '') <> '' THEN b.Qty
		              ELSE 0
		          END AS QTY_RO_CAMPAIGN,
		          CASE
		              WHEN a.PO_Type IS NULL
		                   OR a.PO_Type NOT IN ('PO MAJOR',
		                                        'PO RO') THEN b.Qty
		              ELSE 0
		          END AS QTY_OTHER,
		          b.Qty AS QTY_PO,
		          0 AS QTY_BPB_MAJOR,
		          0 AS QTY_BPB_RO,
		          0 AS QTY_BPB_RO_CAMPAIGN,
		          0 AS QTY_BPB_OTHER,
		          0 AS QTY_BPB
		   FROM Trx_PrePoDetail AS x with(nolock)
		   RIGHT OUTER JOIN TblPOHeader AS a with(nolock) ON x.No_Order=a.No_PO
		   RIGHT OUTER JOIN TblPODetail AS b with(nolock) ON a.No_PO = b.No_PO
		   AND x.Kd_Brg = b.Kd_Brg
		   WHERE (a.Tgl_PO >= @tglawal
		          AND a.Tgl_PO <= @tglakhir)
		     AND (a.Kategori_Brg = 'P')
		     AND (a.Kd_Supl IN ('JKTR001'))
		     AND ((CASE WHEN a.IsClosed IS NULL THEN 0 ELSE a.IsClosed END) = 0)
		   UNION ALL SELECT c.No_PrePO,
		                    c.Kd_Lokasi,
		                    a.No_PO,
		                    b.Kd_Brg,
		                    0 AS QTY_MAJOR,
		                    0 AS QTY_RO,
		                    0 AS QTY_RO_CAMPAIGN,
		                    0 AS QTY_OTHER,
		                    0 AS Qty_PO,
		                    CASE
		                        WHEN c.PO_Type IN ('PO MAJOR') THEN b.Qty
		                        ELSE 0
		                    END AS QTY_BPB_MAJOR,
		                    CASE
		                        WHEN c.PO_Type IN ('PO RO')
		                             AND isnull(x.CampaignPlanID, '') = '' THEN b.Qty
		                        ELSE 0
		                    END AS QTY_BPB_RO,
		                    CASE
		                        WHEN c.PO_Type IN ('PO RO')
		                             AND isnull(x.CampaignPlanID, '') <> '' THEN b.Qty
		                        ELSE 0
		                    END AS QTY_BPB_RO_CAMPAIGN,
		                    CASE
		                        WHEN c.PO_Type IS NULL
		                             OR c.PO_Type NOT IN ('PO MAJOR',
		                                                  'PO RO') THEN b.Qty
		                        ELSE 0
		                    END AS QTY_BPB_OTHER,
		                    b.Qty AS Qty_BPB
		   FROM Trx_PrePoDetail AS x with(nolock)
		   RIGHT OUTER JOIN TblPreBPBHeader AS a with(nolock) ON x.No_Order=a.No_PO
		   RIGHT OUTER JOIN TblPreBPBDetail AS b with(nolock) ON a.No_DO = b.No_DO
		   AND x.Kd_Brg=b.Kd_Brg
		   INNER JOIN TblPOHeader AS c with(nolock) ON a.No_PO = c.No_PO
		   WHERE (c.Tgl_PO >= @tglawal
		          AND c.Tgl_PO <= @tglakhir)
		     AND c.Kategori_Brg='P'
		     AND a.Kd_Supl IN ('JKTR001')
		     AND a.No_DO NOT IN
		       (SELECT No_SJ
		        FROM TblPUHeader with(nolock))
		   UNION ALL SELECT c.No_PrePO,
		                    c.Kd_Lokasi,
		                    a.No_PO,
		                    b.Kd_Brg,
		                    0 AS QTY_MAJOR,
		                    0 AS QTY_RO,
		                    0 AS QTY_RO_CAMPAIGN,
		                    0 AS QTY_OTHER,
		                    0 AS Qty_PO,
		                    CASE
		                        WHEN c.PO_Type IN ('PO MAJOR') THEN b.Qty
		                        ELSE 0
		                    END AS QTY_BPB_MAJOR,
		                    CASE
		                        WHEN c.PO_Type IN ('PO RO')
		                             AND isnull(x.CampaignPlanID, '') = '' THEN b.Qty
		                        ELSE 0
		                    END AS QTY_BPB_RO,
		                    CASE
		                        WHEN c.PO_Type IN ('PO RO')
		                             AND isnull(x.CampaignPlanID, '') <> '' THEN b.Qty
		                        ELSE 0
		                    END AS QTY_BPB_RO_CAMPAIGN,
		                    CASE
		                        WHEN c.PO_Type IS NULL
		                             OR c.PO_Type NOT IN ('PO MAJOR',
		                                                  'PO RO') THEN b.Qty
		                        ELSE 0
		                    END AS QTY_BPB_OTHER,
		                    b.Qty AS Qty_BPB
		   FROM Trx_PrePoDetail AS x with(nolock)
		   RIGHT OUTER JOIN TblPUHeader AS a with(nolock) ON x.No_Order=a.No_PO
		   RIGHT OUTER JOIN TblPUDetail AS b with(nolock) ON a.No_PU = b.No_PU
		   AND x.Kd_Brg=b.Kd_Brg
		   INNER JOIN TblPOHeader AS c with(nolock) ON a.No_PO = c.No_PO /*ON x.No_Order = c.No_PO AND x.Kd_Brg = b.Kd_Brg*/
		   WHERE (c.Tgl_PO >= @tglawal
		          AND c.Tgl_PO <= @tglakhir)
		     AND (c.Kategori_Brg = 'P')
		     AND (a.Kd_Supl IN ('JKTR001'))
		     AND ((CASE WHEN c.IsClosed IS NULL THEN 0 ELSE c.IsClosed END) = 0)
		   UNION ALL SELECT c.No_PrePO,
		                    c.Kd_Lokasi,
		                    a.No_PO,
		                    b.Kd_Brg,
		                    CASE
		                        WHEN c.PO_Type IN ('PO MAJOR') THEN b.Qty
		                        ELSE 0
		                    END AS QTY_MAJOR,
		                    CASE
		                        WHEN c.PO_Type IN ('PO RO')
		                             AND ISNULL(x.CampaignPlanID, '') = '' THEN b.Qty
		                        ELSE 0
		                    END AS QTY_RO,
		                    CASE
		                        WHEN c.PO_Type IN ('PO RO')
		                             AND ISNULL(x.CampaignPlanID, '') <> '' THEN b.Qty
		                        ELSE 0
		                    END AS QTY_RO_CAMPAIGN,
		                    CASE
		                        WHEN c.PO_Type IS NULL
		                             OR c.PO_Type NOT IN ('PO MAJOR',
		                                                  'PO RO') THEN b.Qty
		                        ELSE 0
		                    END AS QTY_OTHER,
		                    b.Qty AS Qty_PO,
		                    CASE
		                        WHEN c.PO_Type IN ('PO MAJOR') THEN b.Qty
		                        ELSE 0
		                    END AS QTY_BPB_MAJOR,
		                    CASE
		                        WHEN c.PO_Type IN ('PO RO')
		                             AND ISNULL(x.CampaignPlanID, '') = '' THEN b.Qty
		                        ELSE 0
		                    END AS QTY_BPB_RO,
		                    CASE
		                        WHEN c.PO_Type IN ('PO RO')
		                             AND ISNULL(x.CampaignPlanID, '') <> '' THEN b.Qty
		                        ELSE 0
		                    END AS QTY_BPB_RO_CAMPAIGN,
		                    CASE
		                        WHEN c.PO_Type IS NULL
		                             OR c.PO_Type NOT IN ('PO MAJOR',
		                                                  'PO RO') THEN b.Qty
		                        ELSE 0
		                    END AS QTY_BPB_OTHER,
		                    b.Qty AS Qty_BPB
		   FROM Trx_PrePoDetail AS x with(nolock)
		   RIGHT OUTER JOIN TblPUHeader AS a with(nolock) ON x.No_Order=a.No_PO
		   RIGHT JOIN TblPUDetail AS b with(nolock) ON a.No_PU = b.No_PU
		   AND x.Kd_Brg=b.Kd_Brg
		   INNER JOIN TblPOHeader AS c with(nolock) ON a.No_PO = c.No_PO /*ON x.No_PrePO = c.No_PrePo AND x.Kd_Brg = b.Kd_Brg*/
		   WHERE (c.Tgl_PO >= @tglawal
		          AND c.Tgl_PO <= @tglakhir)
		     AND (c.Kategori_Brg = 'P')
		     AND (a.Kd_Supl IN ('JKTR001'))
		     AND ((CASE WHEN c.IsClosed IS NULL THEN 0 ELSE c.IsClosed END) = 1)) AS Gab
		INNER JOIN Mst_Cabang Cb with(nolock) ON Gab.Kd_Lokasi=Cb.Kd_Lokasi
		LEFT OUTER JOIN
		  (SELECT KD_BRG AS kode,
		          NM_BRG AS nama,
		          MERK AS merk,
		          CASE
		              WHEN CoSan = 'Y' THEN 'CO&SANITARY'
		              ELSE divisi
		          END AS divisi,
		          'P' AS kat
		   FROM TblINHeader with(nolock)
		   UNION ALL SELECT kd_sparepart AS kode,
		                    nm_sparepart AS nama,
		                    merk,
		                    merk AS divisi,
		                    'S' AS kat
		   FROM TblHeaderInSp with(nolock)) AS Dt ON Gab.Kd_Brg = Dt.kode
		LEFT OUTER JOIN
		  (SELECT x.No_PrePO,
		          x.Kd_GroupGudang,
		          y.Wilayah
		   FROM Trx_PrePOHeader AS x with(nolock)
		   INNER JOIN TblGroupGudangHeader AS y with(nolock) ON x.Kd_GroupGudang=y.Kd_GroupGudang) AS Gd ON Gab.No_PrePO=Gd.No_PrePO
		WHERE (Dt.kat = 'P')
		GROUP BY CASE
		             WHEN (Gd.Wilayah IS NULL
		                   OR Gd.Wilayah='')
		                  AND (Gab.Kd_Lokasi=''
		                       OR Gab.Kd_Lokasi='') THEN 'JAKARTA'
		             WHEN (Gd.Wilayah IS NULL
		                   OR Gd.Wilayah='') THEN Cb.Kota
		             ELSE Gd.Wilayah
		         END ,
		         Dt.merk,
		         Gab.Kd_Brg,
		         Dt.nama,
		         Dt.divisi
		ORDER BY Dt.divisi,
		         Dt.merk,
		         Gab.Kd_Brg,
		         Dt.nama,
		         CASE
		             WHEN (Gd.Wilayah IS NULL
		                   OR Gd.Wilayah='')
		                  AND (Gab.Kd_Lokasi=''
		                       OR Gab.Kd_Lokasi='') THEN 'JAKARTA'
		             WHEN (Gd.Wilayah IS NULL
		                   OR Gd.Wilayah='') THEN Cb.Kota
		             ELSE Gd.Wilayah
		         END
SQL;
		$return = $this->db->query($query)->result_array();
		echo '<pre>';
		print_r($return);
		echo '</pre>';
	}
}