<?php
class ReportTaxModel extends CI_Model{

	function __construct(){
		parent::__construct();
	}
	function loadConnection($config){
		$this->config->load('custom_database');

		$custom_db = $this->config->item('custom');
		$custom_db['hostname'] = $config['hostname'];
		$custom_db['database'] = $config['database'];
		$custom_db['username'] = $config['username'];
		$custom_db['password'] = $config['password'];

		$this->bhakti = $this->load->database($custom_db ,true);
	}
	function getTblSiHeader($config,$data){
	
		$query = "";
		$query = "select No_FakturP as FP, Tgl_FakturP as TglFP from tblsiheader ";
		$query = $query . "where No_FakturP = '" .$data['No_FakturP_Jual']. "' ";
		
		$result = $this->bhakti->query($query)->row();
		return $result;
	}

	function getDataPajakKeluaran($config, $kriteria, $kategori, $data){
		
		switch ($kriteria) {
			case 'RETUR':
				if($kategori=='PRODUK' || $kategori=='SPAREPART'){
					if($kategori == 'PRODUK' || $kategori == 'SPAREPART'){
						$query = " Select DISTINCT case when (nr.No_FakturP<>null or nr.No_FakturP<>'') then nr.No_FakturP  else vg.No_FakturP end as No_FakturP,   ";
						$query = $query . "     case when (nr.No_FakturP<>null or nr.No_FakturP<>'') then nr.Tgl_FakturP  else vg.Tgl_FakturP end as Tgl_FakturP, ";
						$query = $query . "     case when (nr.No_FakturP<>null or nr.No_FakturP<>'') then nr.Nm_Pajak else vg.Nm_Pajak end  as Nm_Pajak,  ";
						$query = $query . "     case when (nr.No_FakturP<>null or nr.No_FakturP<>'') then nr.Alm_Pajak else vg.Alm_Pajak end  as Alm_Pajak, ";
						$query = $query . "     case when (nr.No_FakturP<>null or nr.No_FakturP<>'') then nr.TarifPPN else vg.PPN end  as TarifPPN, ";
						$query = $query . "     (CASE WHEN isnull(vg.NPWP, '')= '' THEN (CASE WHEN isnull(wk.NPWP, '')='' THEN md.NPWP ELSE WK.NPWP END) ELSE vg.NPWP END) as NPWP, vg.Kd_Trn,  ";
						$query = $query . "     case when (nr.No_FakturP<>null or nr.No_FakturP<>'') then nr.Tipe_PPN else vg.Tipe_PPN end  as Tipe_PPN, ";
						$query = $query . "     case when (nr.No_FakturP<>null or nr.No_FakturP<>'') then nr.DPP else vg.DPP end as DPP,  ";
						$query = $query . "     case when (nr.No_FakturP<>null or nr.No_FakturP<>'') then nr.PPn else vg.TotalPPN end  as PPn,  ";
						$query = $query . "     case when (nr.No_FakturP<>null or nr.No_FakturP<>'') then nr.No_FakturP_Jual else vg.No_FakturP_Jual end  as No_FakturP_Jual ";
						$query = $query . " From ViewGrandtotal vg WITH (NOLOCK) inner join tblmsdealer md on vg.kd_plg=md.kd_plg ";
						$query = $query . "     INNER Join TblWilayahKhusus wk on md.kd_plg = wk.kd_plg and vg.kd_wil = wk.kd_wil ";
						$query = $query . "     left join (select distinct a.No_FakturP, Tgl_FakturP, b.No_Faktur, a.Nm_Pajak, a.Alm_Pajak, a.Tipe_PPN, a.DPP, a.PPN, a.No_FakturP_Jual, a.TaxUpdate_Time, a.TarifPPN  ";
						$query = $query . "      From TblNRPHeader a inner join TblNRPDetail b on a.No_FakturP=b.No_FakturP) nr on nr.No_Faktur=vg.No_Faktur ";
						$query = $query . " Where (vg.Tgl_FakturP between '"  . date('Y-m-d',strtotime($data['tgl_awal'])) .  "' and '" . date('Y-m-d',strtotime($data['tgl_akhir'])) . "' ";
						$query = $query . " or nr.Tgl_FakturP between '" . date('Y-m-d',strtotime($data['tgl_awal'])) . "' and '" . date('Y-m-d',strtotime($data['tgl_akhir'])) . "') ";
						if($data['tgl_edit_fp']==1){
							$query = $query . " AND (vg.TaxUpdate_Time between '" . date('Y-m-d',strtotime($data['tgl_awal2'])) . "' and '" . date('Y-m-d',strtotime($data['tgl_akhir2'])) . "' ";
							$query = $query. " ornr.TaxUpdate_Time between '" . date('Y-m-d',strtotime($data['tgl_awal2'])) . "' and '" . date('Y-m-d',strtotime($data['tgl_akhir2'])) . "') ";
						}
						
						$query = $query . " and vg.Kd_Trn = 'R' ";
						if($data['tipe_faktur'] !=''){
							$query = $query & " AND vg.tipe_faktur = '" .$data['tipe_faktur']. "' ";
						}
						if($data['wilayah'] !=''){
							$query = $query . " And md.wilayah='" .$data['wilayah']. "' ";
						}
						$query = $query . " and vg.kategori_Brg='" . ($kategori=='PRODUK' ? 'P':'S') . "' ";
						$query = $query . " Order By case when (nr.No_FakturP<>null or nr.No_FakturP<>'') then nr.No_FakturP  else vg.No_FakturP end ";
					}
				}
			break;
			case 'FAKTUR':
				if($kategori=='PRODUK' || $kategori=='SPAREPART'){
					$query = " Select vg.No_Faktur, substring(vg.No_FakturP,12,8) as No_SeriP, vg.No_FakturP, vg.Tgl_FakturP, vg.Nm_Pajak, vg.Alm_Pajak, ";
					$query = $query . "     (CASE WHEN isnull(vg.NPWP, '')= '' THEN (CASE WHEN isnull(wk.NPWP, '')='' THEN md.NPWP ELSE WK.NPWP END) ELSE vg.NPWP END) as NPWP,  ";
					$query = $query . "     vg.Kd_Trn, vg.No_FakturP_Jual, vg.Tipe_PPN, vg.Disc_Tambahan as DiscTambahan, si.PPN as TarifPPN,  ";
					$query = $query . "     vg.DPP as DPP, vg.TotalPPN as PPn, md.wilayah as Wilayah, RTRIM(RTRIM(vg.No_Faktur) + ' ' + ISNULL(si.NIK, ISNULL(si.No_Passport, ''))) as no_ref  ";
					$query = $query . " From ViewGrandtotal vg  WITH (NOLOCK)  inner join tblmsdealer md on vg.kd_plg=md.kd_plg ";
					$query = $query . "     inner join TblSiHeader si on si.No_Faktur=vg.No_Faktur  ";
					$query = $query . "     INNER Join TblWilayahKhusus wk on md.kd_plg = wk.kd_plg and vg.kd_wil = wk.kd_wil ";
					$query = $query . " Where vg.Tgl_FakturP>dateadd(day,-1,'" .( date('Y-m-d',strtotime($data['tgl_awal'])) ). "') and vg.Tgl_FakturP<dateadd(day,1,'" .( date('Y-m-d',strtotime($data['tgl_akhir'])) ). "') ";
				   
					if($data['tgl_edit_fp']==1){
						$query = $query . " AND vg.TaxUpdate_Time>dateadd(day,-1,'" .( date('Y-m-d',strtotime($data['tgl_awal2'])) ). "') and vg.TaxUpdate_Time<dateadd(day,-1,'" .( date('Y-m-d',strtotime($data['tgl_akhir2'])) ).  "') ";
					}

					$query = $query . " and vg.Kd_Trn = 'J'  ";
				   
					if($data['tipe_faktur'] !=''){
						$query = $query . " AND vg.tipe_faktur = '" .$data['tipe_faktur']. "' ";
					}

					if($data['wilayah'] !=''){
						$query = $query . " And md.wilayah='" .$data['wilayah']. "' ";
					}

					if($data['kode_cabang']!=''){
						$query = $query . " And substring(vg.No_FakturP,5,3)='" .$data['kode_cabang']. "'  ";
					}
					
					$query = $query . " and vg.kategori_Brg='" .($kategori=='PRODUK' ? 'P' : 'S').  "' ";
					$query = $query . " and md.Kelompok_PKP like '" . ($data['tipe_pkp'] == '' ? '%' : $data['tipe_pkp']) . "' ";
					$query = $query . " Order By substring(vg.No_FakturP,12,8) ";
					
				}
				else if($kategori=='SERVICE' && $data['tipe_pkp'] != "PKP"){
					
				}
				break;
			default:
				// code...
				break;
		}
		$result = $this->bhakti->query($query)->result_array();
		//log_message('error','ReportTaxModel '.$query.' data '.print_r($data,true));
		return $result;
	}

	function getDataPajakKeluaranNew($config, $kriteria, $kategori, $data){
		
		$query = "";
		switch ($kriteria) {
			case 'RETUR':
				if($kategori=='PRODUK' || $kategori=='SPAREPART'){
					if($kategori == 'PRODUK' || $kategori == 'SPAREPART'){
						$query = " Select DISTINCT case when (nr.No_FakturP<>null or nr.No_FakturP<>'') then nr.No_FakturP  else vg.No_FakturP end as No_FakturP,   ";
						$query = $query . "     case when (nr.No_FakturP<>null or nr.No_FakturP<>'') then nr.Tgl_FakturP  else vg.Tgl_FakturP end as Tgl_FakturP, ";
						$query = $query . "     case when (nr.No_FakturP<>null or nr.No_FakturP<>'') then nr.Nm_Pajak else vg.Nm_Pajak end  as Nm_Pajak,  ";
						$query = $query . "     case when (nr.No_FakturP<>null or nr.No_FakturP<>'') then nr.Alm_Pajak else vg.Alm_Pajak end  as Alm_Pajak, ";
						$query = $query . "     case when (nr.No_FakturP<>null or nr.No_FakturP<>'') then nr.TarifPPN else vg.PPN end  as TarifPPN, ";
						$query = $query . "     (CASE WHEN isnull(vg.NPWP, '')= '' THEN (CASE WHEN isnull(wk.NPWP, '')='' THEN md.NPWP ELSE WK.NPWP END) ELSE vg.NPWP END) as NPWP, vg.Kd_Trn,  ";
						$query = $query . "     case when (nr.No_FakturP<>null or nr.No_FakturP<>'') then nr.Tipe_PPN else vg.Tipe_PPN end  as Tipe_PPN, ";
						$query = $query . "     case when (nr.No_FakturP<>null or nr.No_FakturP<>'') then nr.DPP else vg.DPP end as DPP,  ";
						$query = $query . "     case when (nr.No_FakturP<>null or nr.No_FakturP<>'') then nr.PPn else vg.TotalPPN end  as PPn,  ";
						$query = $query . "     case when (nr.No_FakturP<>null or nr.No_FakturP<>'') then nr.No_FakturP_Jual else vg.No_FakturP_Jual end  as No_FakturP_Jual ";
						$query = $query . " From ViewGrandtotal vg  WITH (NOLOCK)  inner join tblmsdealer md on vg.kd_plg=md.kd_plg ";
						$query = $query . "     INNER Join TblWilayahKhusus wk on md.kd_plg = wk.kd_plg and vg.kd_wil = wk.kd_wil ";
						$query = $query . "     left join (select distinct a.No_FakturP, Tgl_FakturP, b.No_Faktur, a.Nm_Pajak, a.Alm_Pajak, a.Tipe_PPN, a.DPP, a.PPN, a.No_FakturP_Jual, a.TaxUpdate_Time, a.TarifPPN  ";
						$query = $query . "      From TblNRPHeader a inner join TblNRPDetail b on a.No_FakturP=b.No_FakturP) nr on nr.No_Faktur=vg.No_Faktur ";
						$query = $query . " Where (vg.Tgl_FakturP between '"  . date('Y-m-d',strtotime($data['tgl_awal'])) .  "' and '" . date('Y-m-d',strtotime($data['tgl_akhir'])) . "' ";
						$query = $query . " or nr.Tgl_FakturP between '" . date('Y-m-d',strtotime($data['tgl_awal'])) . "' and '" . date('Y-m-d',strtotime($data['tgl_akhir'])) . "') ";
						if($data['tgl_edit_fp']==1){
							$query = $query . " AND (vg.TaxUpdate_Time between '" . date('Y-m-d',strtotime($data['tgl_awal2'])) . "' and '" . date('Y-m-d',strtotime($data['tgl_akhir2'])) . "' ";
							$query = $query. " ornr.TaxUpdate_Time between '" . date('Y-m-d',strtotime($data['tgl_awal2'])) . "' and '" . date('Y-m-d',strtotime($data['tgl_akhir2'])) . "') ";
						}
						
						$query = $query . " and vg.Kd_Trn = 'R' ";
						if($data['tipe_faktur'] !=''){
							$query = $query & " AND vg.tipe_faktur = '" .$data['tipe_faktur']. "' ";
						}
						if($data['wilayah'] !=''){
							$query = $query . " And md.wilayah='" .$data['wilayah']. "' ";
						}
						$query = $query . " and vg.kategori_Brg='" . ($kategori=='PRODUK' ? 'P':'S') . "' ";
						$query = $query . " Order By case when (nr.No_FakturP<>null or nr.No_FakturP<>'') then nr.No_FakturP  else vg.No_FakturP end ";
					}
					
				}
				break;
			case 'FAKTUR':
				if($kategori=='PRODUK' || $kategori=='SPAREPART'){
					$query = "";
					$query = " Select vg.No_Faktur, substring(vg.No_FakturP,12,8) as No_SeriP, vg.No_FakturP, vg.Tgl_FakturP, vg.Nm_Pajak, vg.Alm_Pajak, ";
					$query = $query . "     (CASE WHEN isnull(vg.NPWP, '')= '' THEN (CASE WHEN isnull(wk.NPWP, '')='' THEN md.NPWP ELSE WK.NPWP END) ELSE vg.NPWP END) as NPWP, ";
					$query = $query . "     vg.Kd_Trn, vg.No_FakturP_Jual, vg.Tipe_PPN, vg.Disc_Tambahan as DiscTambahan, si.PPN as TarifPPN, ";
					$query = $query . "     vg.DPP as DPP, vg.TotalPPN as PPn, md.wilayah as Wilayah, RTRIM(RTRIM(vg.No_Faktur) + ' ' + ISNULL(si.NIK, ISNULL(si.No_Passport, ''))) as no_ref, ISNULL(si.NIK, '') as NIK ";
					$query = $query . " From ViewGrandtotal vg  WITH (NOLOCK)  inner join tblmsdealer md on vg.kd_plg=md.kd_plg";
					$query = $query . "     inner join TblSiHeader si on si.No_Faktur=vg.No_Faktur ";
					$query = $query . "     INNER Join TblWilayahKhusus wk on md.kd_plg = wk.kd_plg and vg.kd_wil = wk.kd_wil";

					if($data['tgl_awal'] !='' && $data['tgl_akhir'] !='' ){
						$query = $query . " Where vg.Tgl_FakturP>dateadd(day,-1,'" .  date('Y-m-d',strtotime($data['tgl_awal'])) . "') and vg.Tgl_FakturP<dateadd(day,1,'" .  date('Y-m-d',strtotime($data['tgl_akhir'])) . "')";
					}
					if($data['tgl_edit_fp']==0 && $data['tgl_awal2'] !='' && $data['tgl_akhir2'] !='' ){
						$query =  $query . " AND vg.TaxUpdate_Time>dateadd(day,-1,'" . date('Y-m-d',strtotime($data['tgl_awal2'])) . "') and vg.TaxUpdate_Time<dateadd(day,-1,'" .  date('Y-m-d',strtotime($data['tgl_akhir2']))  . "')";
					}
					$query =  $query . " and vg.Kd_Trn = 'J' ";
					if( $data['tipe_faktur'] != ""){
						$query =  $query . " AND vg.tipe_faktur = '" . $data['tipe_faktur'] . "'";
					}
					if($data['wilayah'] !='' ){
						$query =  $query . " And md.wilayah='" .$data['wilayah']. "'";
					}
					if($data['kode_cabang'] !='' ){
						$query =  $query . " And substring(vg.No_FakturP,5,3)='" . $data['kode_cabang'] . "' ";
					}

					$query =  $query . " and vg.kategori_Brg='" . ( $kategori = "PRODUK" ?  "P" : "S" ) . "'";
					$query =  $query . " and md.Kelompok_PKP like '" . ($data['tipe_pkp'] == '' ? '%' : $data['tipe_pkp']) . "'";
					$query =  $query . " Order By substring(vg.No_FakturP,12,8)";
				}
				else if($kategori=='SERVICE' && $data['tipe_pkp'] != "PKP"){
					$query = "";
					$query = "select No_Svc as No_Faktur, substring(No_SvcP,12,8) as No_SeriP, No_SvcP as No_FakturP, Tgl_SvcP as Tgl_FakturP, Nm_Plg as Nm_Pajak, Alm_Pajak,'J' as Kd_Trn, '' as No_FakturP_Jual, ";
					$query = $query . "'F' as Tipe_PPN, 0 as DiscTambahan, '' as Wilayah, isnull(Rate_PPN, 10) as TarifPPN, ";
					$query = $query . "ISNULL(NPWP, '000000000000000') as NPWP, Ongkos_Svc as DPP, (isnull(Rate_PPN,10) * ongkos_Svc) as PPn, ";
					$query = $query . "RTRIM( RTRIM(No_Svc) + ' ' + ISNULL(NIK, ISNULL(No_Passport, '')) ) as no_ref ";
					$query = $query . "From TblSvcHeader  WITH (NOLOCK)  ";
					$query = $query . " Where Tgl_SvcP>dateadd(day,-1,'" .( date('Y-m-d',strtotime($data['tgl_awal'])) ). "') and Tgl_SvcP<dateadd(day,1,'" .( date('Y-m-d',strtotime($data['tgl_akhir'])) ). "')";
					if($data['wilayah'] == 'DMI'){
						$query = $query . "and ISNULL(kd_lokasi, '" .$data['kd_lokasi']. "') IN ('" .$data['kd_lokasi']. "','MND','BAT') ";
					}
					if($data['kode_cabang'] !=''){
						$query = $query . "And substring(No_SvcP,5,3)='" . $data['kode_cabang'] . "' ";
					}
					$query = $query . "Order By substring(No_SvcP,12,8) ";
				}
				break;
			default:
				// code...
				break;
		}
		$result = $this->bhakti->query($query)->result_array();
		//log_message('error','ReportTaxModel '.$query.' data '.print_r($data,true));
		return $result;
	}

	function getDataPajakKeluaranDetail($config, $kriteria, $kategori, $data){
		
		$hasil = array();

		$totalDpp = $data['dpp'];
		$subTotalPpn = 0;
		$subTotalDiscTambahan = 0;
		$recordCount = 0;

		$hargaTotal = 0;
		$disc1 = 0;
		$disc2 = 0;
		$disc3 = 0;
		$diskon = 0;
		$hargaTotalAfterDiskon = 0;
		$dpp = 0;
		$ppn = 0;
		$tarifPpn = 0;
		$discTambahan = 0;
		$hargaSpesialSales = 0;

		$dpp_dt = 0;
		$query = '';
		if ($kategori == "PRODUK" ||  $kategori == "SPAREPART"){
			$query = " select SUM(dbo.F_HitungSubtotal('" .$data['tipe_ppn']. "', Qty, Harga, Disc1, Disc2, Disc3)) as SubTotalNett ";
			$query = $query . " , SUM(dbo.F_HitungSubtotal('" .$data['tipe_ppn']. "', Qty, Harga, Disc1, Disc2, 0)) as SubTotalNett2 ";
			$query = $query . " from TblSIDetail  WITH (NOLOCK) ";
			$query = $query . " WHERE No_Faktur = '" .$data['no_faktur']. "' ";

			//log_message('error','ReportTaxModel - getDataPajakDetaiil '.$query.' data '.print_r($data,true));
			$header2 = $this->bhakti->query($query)->row();
			
			$subTotalNett = 0;
			$subTotalNett2 = 0;
			$jumlahRecord = 0;
			if($header2!=null){
				$subTotalNett = $header2->SubTotalNett;
				$subTotalNett2 = $header2->SubTotalNett2;
			}

			$query = '';
			$query = "select count(No_Faktur) AS JUMLAHRECORD ";
			$query = $query . "from TblSIDetail  WITH (NOLOCK)  ";
			$query = $query . "WHERE No_Faktur = '" .$data['no_faktur']. "' ";
			$detail2 = $this->bhakti->query($query)->row();
			if($detail2!=null){
				$jumlahRecord = $detail2->JUMLAHRECORD;
			}

			$query = '';
			if($kategori == 'PRODUK'){
				$query = "Select sd.No_Faktur, sd.Kd_Brg, sd.Qty, sd.Harga, sd.Disc1, sd.Disc2, sd.Disc3, ";
				$query = $query . " (CASE WHEN ISNULL(RTRIM(kt.Free_PPN),'N') = 'Y' And ih.HS_Code <> '' THEN RTRIM(ih.Nm_Brg) + ' / HSCODE: ' + ih.HS_Code ELSE RTRIM(ih.Nm_Brg) END) as NM_BRG, ";
				$query = $query . " dbo.F_HitungSubtotal('" .$data['tipe_ppn']. "', sd.Qty, sd.Harga, sd.Disc1, sd.Disc2, sd.Disc3) as Subtotal, ";
				$query = $query . " dbo.F_HitungSubtotal('" .$data['tipe_ppn']. "', sd.Qty, sd.Harga, sd.Disc1, sd.Disc2, 0) as HargaSS, ";
				$query = $query . " Hargahadiah = CASE WHEN sd.Disc1= 100 THEN dbo.F_HitungSubtotal('" .$data['tipe_ppn']. "', sd.Qty, sd.Harga, 0, 0, 0) ELSE 0 END ";
				$query = $query . " FROM TblSIDetail sd  WITH (NOLOCK)  INNER JOIN TblInHeader ih ON sd.Kd_Brg = ih.Kd_Brg ";
				$query = $query . " inner join TblSIHeader hd on sd.No_Faktur = hd.No_Faktur ";
				$query = $query . " inner join TblMsDealer dl on hd.Kd_Plg = dl.kd_plg ";
				$query = $query . " left join TblWilayahKhusus wk on wk.Kd_Wil = hd.Kd_Wil and wk.Kd_Plg = hd.Kd_plg ";
				$query = $query . " left join TblKota kt on kt.Kota = (case when dl.kota = 'BATAM' then dl.kota else ISNULL(wk.Wilayah, dl.kota) end) ";
				$query = $query . " WHERE sd.No_Faktur = '" . $data['no_faktur'] . "' ";
				$query = $query . " ORDER BY sd.Disc1 DESC, sd.Kd_Brg ";
			}
			else if($kategori == 'SPAREPART'){
				$query = "Select sd.No_Faktur, sd.Kd_Brg, sd.Qty, sd.Harga, sd.Disc1, sd.Disc2, sd.Disc3, ";
				$query = $query . "(CASE WHEN ISNULL(RTRIM(kt.Free_PPN),'N') = 'Y' And ISNULL(ih.HS_Code,'') <> '' THEN RTRIM(ih.Nm_Sparepart) + ' / HSCODE: ' + ih.HS_Code ELSE RTRIM(ih.Nm_Sparepart) END) as NM_BRG, ";
				$query = $query . "dbo.F_HitungSubtotal('" .$data['tipe_ppn']. "', sd.Qty, sd.Harga, sd.Disc1, sd.Disc2, sd.Disc3) as Subtotal, ";
				$query = $query . "dbo.F_HitungSubtotal('" .$data['tipe_ppn']. "', sd.Qty, sd.Harga, sd.Disc1, sd.Disc2, 0) as HargaSS ";
				$query = $query . "FROM TblSIDetail sd  WITH (NOLOCK)  INNER JOIN TblHeaderinsp ih ON sd.Kd_Brg = ih.Kd_Sparepart ";
				$query = $query . " inner join TblSIHeader hd on sd.No_Faktur = hd.No_Faktur ";
				$query = $query . " inner join TblMsDealer dl on hd.Kd_Plg = dl.kd_plg ";
				$query = $query . " left join TblWilayahKhusus wk on wk.Kd_Wil = hd.Kd_Wil and wk.Kd_Plg = hd.Kd_plg ";
				$query = $query . " left join TblKota kt on kt.Kota = (case when dl.kota = 'BATAM' then dl.kota else ISNULL(wk.Wilayah, dl.kota) end) ";
				$query = $query . "WHERE sd.No_Faktur = '" .$data['no_faktur']. "' ";
				$query = $query . "ORDER BY sd.Kd_Brg ";
			}
			else if($kategori == 'SERVICE'){
				$query = "select no_svc, 'SVC' AS Kd_Brg, 'JASA SERVICE' as Nm_Brg, ";
				$query = $query . "1 as QTY, ongkos_svc AS Harga, ongkos_svc as HargaSS, ";
				$query = $query . "0 as Disc1, 0 as DIsc2, 0 as Disc3, ongkos_svc as Subtotal ";
				$query = $query . "from TblSvcHeader  WITH (NOLOCK)  ";
				$query = $query . "where No_Svc = '" .$data['no_faktur']. "' ";
			}
			$rsDetail = $this->bhakti->query($query)->result_array();            

			$tarifPpn = $data['tarif_ppn'] / 100;
			if($rsDetail!=null){
				//log_message('error','detail '.print_r($rsDetail,true));
				foreach ($rsDetail as $key => $value) {
					if( $data['wilayah'] != 'SPECIAL SALES' && $data['wilayah'] != 'MODERN OUTLET' ){
					
						$hargaTotal = $value['Harga'] * $value['Qty'];
						$hargaTotalAfterDiskon = $value['Subtotal'];
						$recordCount += 1;

						if($kategori == 'PRODUK' || $kategori == 'SPAREPART'){
							$discTambahan = 0;
							$discTambahan = round( ($hargaTotalAfterDiskon/ $subTotalNett) * $data['disc_tambahan'],2 );
						}
						$subTotalDiscTambahan = $subTotalDiscTambahan + $discTambahan;
						if($recordCount == $jumlahRecord){
							$discTambahan = $discTambahan + ($data['disc_tambahan'] - $subTotalDiscTambahan);
						}
						$diskon  = $hargaTotal - $hargaTotalAfterDiskon;
						$dpp = $hargaTotal - $diskon - $discTambahan;
						$ppn = $dpp * $tarifPpn;
						$subTotalPpn = $subTotalPpn + $ppn;
						
					
					}
					else if ( $data['wilayah']=='SPECIAL SALES' && $data['wilayah'] == 'MODERN OUTLET' ){
						if($value['Disc1'] !='100' ){
							$hargaSpesialSales = $value['HargaSS'] / $value['Qty'];
						}
						else{
							$hargaSpesialSales = $value['HargaHadiah'] / $value['Qty'];
						}
						$hargaTotal = $hargaSpesialSales * $value['Qty'];
						$hargaTotalAfterDiskon = $value['Subtotal'];

						$recordCount += 1;
						if($kategori == 'PRODUK' || $kategori == 'SPAREPART'){
							$discTambahan = 0;
							$discTambahan = round(($hargaTotalAfterDiskon / $subTotalNett) * $data['disc_tambahan'], 2);
						}
		  
						$subTotalDiscTambahan = $subTotalDiscTambahan + $discTambahan;
						if($recordCount == $jumlahRecord){
							$discTambahan = $discTambahan + ($data['disc_tambahan'] - $data['subTotalDiscTambahan']);
						}
		  
						$diskon  = $hargaTotal - $hargaTotalAfterDiskon;
						$dpp = $hargaTotal - $diskon - $discTambahan;
						$ppn = $dpp * $tarifPpn;
						$subTotalPpn = $subTotalPpn + $ppn;
						
					}

					$hasil['detail'][] = array(
						'of' => 'OF',
						'kode_objek' => rtrim($value['Kd_Brg'],' '),
						'nama' =>  $value['NM_BRG'],
						'harga_satuan' => $hargaSpesialSales,
						'jumlah_barang' => $value['Qty'],
						'harga_total' => $hargaTotal,
						'diskon' => $diskon + $discTambahan,
						'dpp' => $dpp,
						'ppn' => $ppn,
						'tarif_ppnbm' => 0,
						'ppnbm' => 0,
					);
				}
				if ($data['tipe_ppn'] == 'F' || $data['tipe_ppn'] == 'G'){
					$hasil['jumlah_ppn'] = floor($subTotalPpn);
				}
				else if($data['Tipe_ppn'] == 'E' || $data['Tipe_ppn'] == 'H'){
					$hasil['jumlah_ppn'] = round($value['PPN']);
				}
				
			}

		}	
		
		
		return $hasil;
	}
	
}