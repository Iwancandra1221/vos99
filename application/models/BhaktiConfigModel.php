<?php
class BhaktiConfigModel extends CI_Model
{
	function __construct()
	{
		parent::__construct();		
		$CI = &get_instance();
	}
	function GetTblConfig_2($configDB=array())
	{
		$qry = "SELECT Kd_Lokasi as KD_LOKASI, isnull(JktAPI_URL,'') as JKTAPI_URL, 
					isnull(VAAPI1_URL,'') as VAAPI1_URL, isnull(VAAPI2_URL,'') as VAAPI2_URL,
					isnull(ZenHRS_URL,'') as ZENHRS_URL, isnull(WebAPI_URL,'') as LOKAL_URL,
					isnull(myCompany_URL,'') as MYCOMPANY_URL, isnull(msgAPI_URL,'') as MSGAPI_URL,
					isnull(Environment, 'DEVELOPMENT') as ENVIRONMENT, BranchCode as BRANCHCODE
				FROM TblConfig";
		// die($qry);
		if (isset($configDB["hostname"]) && isset($configDB["database"])) {
			$this->config->load('custom_database');

			$custom_db = $this->config->item('custom');
			$custom_db['hostname'] = $configDB['hostname'];
			$custom_db['database'] = $configDB['database'];
			$custom_db['username'] = $configDB['username'];
			$custom_db['password'] = $configDB['password'];
			
			$this->bkt = $this->load->database($custom_db ,true);
			//$this->bkt = $this->load->database($configDB, TRUE);
			$res = $this->bkt->query($qry);
			if ($res->num_rows()>0) 
				return $res->row();
			else
				return null;
		} else {
			$res = $this->db->query($qry);
			if ($res->num_rows()>0) 
				return $res->row();
			else
				return null;

		}
	}
	function GetTblConfig($configDB=array())
	{
		$qry = "SELECT Kd_Lokasi as KD_LOKASI, isnull(JktAPI_URL,'') as JKTAPI_URL, 
					isnull(VAAPI1_URL,'') as VAAPI1_URL, isnull(VAAPI2_URL,'') as VAAPI2_URL,
					isnull(ZenHRS_URL,'') as ZENHRS_URL, isnull(WebAPI_URL,'') as LOKAL_URL,
					isnull(myCompany_URL,'') as MYCOMPANY_URL, isnull(msgAPI_URL,'') as MSGAPI_URL,
					isnull(Environment, 'DEVELOPMENT') as ENVIRONMENT, BranchCode as BRANCHCODE
				FROM TblConfig";
		// die($qry);
		if (isset($configDB["hostname"]) && isset($configDB["database"])) {
			$this->bkt = $this->load->database($configDB, TRUE);
			$res = $this->bkt->query($qry);
			if ($res->num_rows()>0) 
				return $res->row();
			else
				return null;
		} else {
			$res = $this->db->query($qry);
			if ($res->num_rows()>0) 
				return $res->row();
			else
				return null;

		}
	}

	function GetEmailRecipients($event="")
	{
		if ($event=="") {
			return array();
		} else {
			$str = "SELECT DISTINCT A.Tipe_Penerima as TIPE_PENERIMA, 
					A.Penerima_Email as NAMA_PENERIMA,
					(case when A.IsUser=1 then ISNULL(B.User_Email,'') else isnull(A.Email_Address,'') end) AS EMAIL_PENERIMA
				FROM Cof_Email_Job A LEFT OUTER JOIN
					TblUser B ON A.Penerima_Email = B.User_Name
				WHERE (A.Nama_Job in (".$event.") 
					AND (case when A.IsUser=1 then ISNULL(B.User_Email,'') else isnull(A.Email_Address,'') end)<>''
					AND (A.Aktif = 1))";
			//die($str);
			$res = $this->db->query($str);
			if ($res->num_rows()>0) 
				return $res->result();
			else
				return array();
		}
	}
}
?>
