<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); 
require FCPATH.'application/libraries/simplehtmldom_1_9_1/simple_html_dom.php';
class Test extends CI_Controller 
{
	public function __construct()
	{
		parent::__construct();
		
	}
	public function phpinfo(){
		phpinfo();
	}
	public function test1(){
		$this->load->model('TestModel');
		$this->load->library('session');

		$start_time = microtime(true);
		$res = $this->TestModel->query1();
			if (count($res)>0) {
				$hasil["result"] = "sukses";
				$hasil["data"] = $res;
				$hasil["error"] = "";
			} else {
				$hasil["result"] = "gagal";
				$hasil["data"] = array();
				$hasil["error"] = "tidak ada data";
			}
		// Waktu Selesai
		$end_time = microtime(true);

		// Menghitung waktu eksekusi
		$execution_time = $end_time - $start_time;

		// Menampilkan waktu mulai dan waktu selesai
		// echo "Waktu Mulai: " . date('Y-m-d H:i:s', $start_time) . "<br>";
		// echo "Waktu Selesai: " . date('Y-m-d H:i:s', $end_time) . "<br>";
		// echo '<pre>';
		// //print_r($res->result_array());
		// echo '</pre>';
		// // Menampilkan waktu eksekusi dalam milidetik
		// echo "Waktu Eksekusi: " . round($execution_time * 1000, 2) . " ms";


		// echo '</br>';
		$result = json_encode($hasil);
		header('HTTP/1.1: 200');
		header('Status: 200');
		header('Content-Length: '.strlen($result));
		exit($result);
	}
	public function get_report(){
		$this->load->model('TestModel');
		$this->load->library('session');

		$start_time = microtime(true);
		$this->TestModel->getReport();

		// Waktu Selesai
		$end_time = microtime(true);

		// Menghitung waktu eksekusi
		$execution_time = $end_time - $start_time;

		// Menampilkan waktu mulai dan waktu selesai
		echo "Waktu Mulai: " . date('Y-m-d H:i:s', $start_time) . "<br>";
		echo "Waktu Selesai: " . date('Y-m-d H:i:s', $end_time) . "<br>";

		// Menampilkan waktu eksekusi dalam milidetik
		echo "Waktu Eksekusi: " . round($execution_time * 1000, 2) . " ms";
	}
	public function index2(){
		
		$i = 0;
		
		$data = array();
		// if(file_exists('cache_data')){
		// 	$data = unserialize(file_get_contents("cache_data"));
			
		// }
		// else{
		// 	$result = $this->TestModel->getUser(null);
		// 	$data = $result->result_array();
		// 	file_put_contents("cache_data", serialize($data));
		// }


		if($this->session->has_userdata('cache_data')){
			$data = $this->session->userdata('cache_data');
		}
		else{
			$result = $this->TestModel->getUser(null);
			$data = $result->result_array();
			$this->session->set_userdata('cache_data', $data);
		}
		foreach($data as $key => $value){
			echo $i." ".$value['NAME']."<br>";
			$i+=1;
		}
		// while($value = $result->result_array_v2()){
		// 	echo $i." ".$value['NAME']."<br>";
		// 	$i+=1;
		// }
	}
	function test_send_email(){
		$config['protocol'] = 'smtp';
		$config['smtp_host'] = 'mail.bhakti.co.id';//'smtp.gmail.com';
		$config['smtp_port'] = '465';
		$config['smtp_user'] = 'bhaktiautoemail.noreply@bhakti.co.id';//'bitautoemail.noreply@gmail.com';
		$config['smtp_pass'] = '@9i3X5ku8';//'gnumqmnshwqavhnt';
		$config['mailtype'] = 'html';
		$config['charset'] = 'utf-8';
		$config['newline'] = "\r\n";
		$config['smtp_crypto'] = 'ssl';
		// $config['smtp_timeout'] = 30;
		//$config['smtp_verify_peer'] = false; // Mematikan verifikasi sertifikat
		//$config['smtp_verify_peer_name'] = false; // Mematikan verifikasi nama sertifikat

		$this->load->library('email');
		$this->email->initialize($config);
		$this->email->from('bitautoemail.noreply@gmail.com', 'Your Name');
		$this->email->to('reegankens@gmail.com');
		$this->email->subject('Test Subject');
		$this->email->message('Test body');
		if ($this->email->send()) {
			echo 'Email sent.';
		} else {
			echo 'Email failed to send.'.$this->email->print_debugger();;
		}
	}
	function test_send_email_2(){
		$this->load->library('phpmailer_lib');
		$to = 'reegankens@gmail.com'; // Ganti dengan alamat email penerima
		$subject = 'Contoh Email';
		$message = 'Ini adalah contoh pesan email.';

		if ($this->phpmailer_lib->sendEmail($to, $subject, $message)) {
			echo 'Email berhasil dikirim.';
		} else {
			echo 'Gagal mengirim email.';
		}
	}

	public function loginjenkins()
	{ 
	    $jenkinsUrl = 'http://192.168.100.7:8080';
		$username = 'ican';
		$password = 'ican123';   
		$curl = curl_init(); 
		curl_setopt_array($curl, array(
		    CURLOPT_URL => $jenkinsUrl . '/job/zen_production_windows/changes',
		    CURLOPT_RETURNTRANSFER => true, 
		    CURLOPT_HTTPAUTH => CURLAUTH_BASIC,
		    CURLOPT_USERPWD => "$username:$password",
		)); 
		$response = curl_exec($curl); 
		if ($response === false) { 
		    echo 'Error: ' . curl_error($curl);
		} else {  
			$html = str_get_html($response);
			$mainPanel = $html->getElementById('main-panel'); 
			$html = str_get_html($mainPanel);
			echo $html;  
		} 
		curl_close($curl);
	}

}
