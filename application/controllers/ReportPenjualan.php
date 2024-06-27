<?php
defined('BASEPATH') OR exit('No direct script access allowed');
 
    require 'vendor/autoload.php';
    use PhpOffice\PhpSpreadsheet\Spreadsheet;
    use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class ReportPenjualan extends CI_Controller	 {

	function __construct()
	{
		parent::__construct();  
        $this->load->model('ReportModel');
	}

	public function index()
	{  
        $data['title'] = ucfirst('Laporan Penjualan');  
		$this->load->view('templates/header', $data);
        $this->load->view('LaporanPenjualanView', $data);
        $this->load->view('templates/footer', $data);
	} 

	public function CetakReport()
	{ 
		$data = array();
		$page_title = 'EXPORT BRP NRP';
			   
		$dp1 = $_POST['dp1']; 
		$dp2 = $_POST['dp2'];   
 
		$reportType = $_POST['reportType']; 
 
		if(isset($_POST["btnPreview"])){
			$this->preview_flag = 1;
		}
		else{
			$this->preview_flag = 0;
		} 

		if(isset($_POST["btnExcel"])){
			$this->excel_flag = 1;
		}
		else{
			$this->excel_flag = 0;
		} 

		if (isset($_POST["btnPdf"])) {
			$this->pdf_flag = 1;
		} 
		else {
			$this->pdf_flag = 0;
		}

 		if ($reportType === "Penjualan")
 		{ 
	        $Data  = $this->ReportModel->GetFilteredSalesReport($dp1,$dp2);

			if ($this->pdf_flag === 1 || $this->preview_flag === 1)
			{
				$this->CreateHtmlPenjualan($dp1,$dp2,$Data);
			} 
			else
			{
				$this->CreateExcel();
			}
 		}
 		else
 		{
 
	        $Data  = $this->ReportModel->GetReportBarang($dp1,$dp2);

			if ($this->pdf_flag === 1 || $this->preview_flag === 1)
			{
				$this->CreateHtmlBarang($dp1,$dp2,$Data);
			} 
			else
			{
				$this->CreateExcel();
			}
 		}

	}

	public function CreateHtmlPenjualan($periode_dari,$periode_sampai,$Data)
	{
		date_default_timezone_set('Asia/Jakarta'); 
        $tanggal_print = date('d-m-Y H:i:s');

        $titleReport = 'LAPORAN PENJUALAN';	  

			if (count($Data) < 1) { 
				exit('Tidak ada data');
			} 

			$header = '<table border="0" style="width:297mm; font-size:15px;">
					<tr>
						<td>
							<b>
								'.$titleReport.'
							</b>
						</td>
						<td align="right" style="font-size:12px;">
							Print Date : '.$tanggal_print.'
						</td>
					</tr>
					<tr>
						<td >
							<b>
								TOKO BERKAH JAYA
							</b>
						</td>
						<td align="right" style="font-size:12px;">
							PERIODE : '.$periode_dari.' - '.$periode_sampai.'
						</td>
					</tr>
					<tr>
						<td colspan="2">
							<hr>
						</td>
					</tr>  
				</table>';			

				$cust = '';
				$custname = '';
				$no = 0;
 
				$sumtotalawal= 0; 
				$sumtotaljual= 0; 
				$sumtotallaba= 0; 

				$final_sumtotalawal= 0;
				$final_sumtotaljual= 0;
				$final_sumtotallaba= 0;
				$content = '';
				foreach ($Data as $key => $c) {

					if($c->KdPelanggan!=$cust && $no!=0){

						$content .='<tr>
							<td colspan="8">
								<hr>
							</td>
						</tr> ';
						$content .= '<tr>
										<td ></td>  
										<td ><b>Total '.$custname.'</b></td>   
										<td ><b>'.number_format($sumtotalawal,2,",",".").'</b></td> 
										<td ><b>'.number_format($sumtotaljual,2,",",".").'</b></td>  
									    <td style="color: '.($sumtotallaba < 0 ? 'red' : 'inherit').';">
									        '.number_format($sumtotallaba, 2, ",", ".").'
									    </td>
										<td colspan="3"></td>   
									</tr>';
						$content .= '</table>';
						$no = 0; 
						$sumtotalawal= 0; 
						$sumtotaljual= 0; 
						$sumtotallaba= 0; 
 
					}

					if($no==0){
						$cust = $c->KdPelanggan;
						$custname = $c->NamaPelanggan;
						$content .= '<table style="width:297mm">
									<tr>
										<td colspan="6">
											<br><b>'.$c->NamaPelanggan.'</b>
										</td>
									</tr>
									<tr>
										<th style="text-align: left; width: 15%; font-size: 13px;">Kode Penjualan</th> 
										<th style="text-align: left; width: 25%; font-size: 13px;">Tipe Pembayaran</th> 
										<th style="text-align: left; width: 10%; font-size: 13px;">Total Omzet</th>
										<th style="text-align: left; width: 10%; font-size: 13px;">Total Jual</th>
										<th style="text-align: left; width: 10%; font-size: 13px;">Laba Rugi</th>
										<th style="text-align: left; width: 10%; font-size: 13px;">Tgl Transaksi</th>
										<th style="text-align: left; width: 10%; font-size: 13px;">Tgl Jatuh Tempo</th>
										<th style="text-align: left; width: 10%; font-size: 13px;">Status</th>
									</tr>
									<tr>
										<td colspan="8">
											<hr>
										</td>
									</tr>  ';
					}  
  
						$sumtotalawal += $c->TotalModal; 
						$sumtotaljual += $c->TotalJual;  
						$sumtotallaba += $c->LabaRugi; 

						$final_sumtotalawal += $c->TotalModal; 
						$final_sumtotaljual += $c->TotalJual; 
						$final_sumtotallaba += $c->LabaRugi;  

						$content .= '<tr>
									    <td>'.trim($c->KdPenjualan).'</td>
									    <td>'.trim($c->NamaTipePembayaran).'</td>
									    <td>'.number_format($c->TotalModal, 2, ",", ".").'</td>
									    <td>'.number_format($c->TotalJual, 2, ",", ".").'</td>
									    <td style="color: '.($c->LabaRugi < 0 ? 'red' : 'inherit').';">
									        '.number_format($c->LabaRugi, 2, ",", ".").'
									    </td>
									    <td>'.date_format(date_create($c->TglTrans), "d-m-Y").'</td>
									    <td>'.date_format(date_create($c->Tanggal_Tempo), "d-m-Y").'</td>
									    <td style="color: '.($c->Lunas == 1 ? 'green' : 'red').';">
									        '.($c->Lunas == 1 ? 'Lunas' : 'Belum Lunas').'
									    </td>
									</tr>';


					$no++;

				}

				if(count($Data)){


					$content .='<tr>
						<td colspan="8">
							<hr>
						</td>
					</tr> ';

					$content .= '<tr>
										<td ></td>  
										<td ><b>Total '.$custname.'</b></td>   
										<td ><b>'.number_format($sumtotalawal,2,",",".").'</b></td> 
										<td ><b>'.number_format($sumtotaljual,2,",",".").'</b></td> 
									    <td style="color: '.($sumtotallaba < 0 ? 'red' : 'inherit').';">
									        '.number_format($sumtotallaba, 2, ",", ".").'
									    </td>
										<td colspan="3"></td>   
								</tr>';

					$content .='<tr>
						<td colspan="8"> 
						</td>
					</tr> ';
					$content .= '<tr>
										<td  colspan="5"></td>  
										<td colspan="2">Grand Total Omzet</b></td>    
									    <td style="color: '.($final_sumtotalawal < 0 ? 'red' : 'inherit').';">
									        '.number_format($final_sumtotalawal, 2, ",", ".").'
									    </td> 
									</tr>';
					$content .= '<tr>
										<td  colspan="5"></td>  
										<td colspan="2">Grand Total Jual</b></td>    
									    <td style="color: '.($final_sumtotaljual < 0 ? 'red' : 'inherit').';">
									        ('.number_format($final_sumtotaljual, 2, ",", ".").')
									    </td>   
									</tr>';
					$content .='<tr>
						<td  colspan="5"></td>  
						<td colspan="3">
							<hr>
						</td> 
					</tr> ';

					$content .= '<tr>
										<td  colspan="5"></td>   
										<td colspan="2"><b>GRAND TOTAL Final</b></td>   
									    <td style="color: '.($final_sumtotallaba < 0 ? 'red' : 'inherit').';">
									        '.number_format($final_sumtotallaba, 2, ",", ".").'
									    </td>   
									</tr>';
					$content .= '</table>';
				}
				$footer = "";
         

        if ($this->preview_flag === 1)
		{
			echo $header.$content.$footer;
		} 
        if ($this->pdf_flag === 1)
		{
			$this->CreatePDF($header,$content,$footer);
		}  

	}

	public function CreateHtmlBarang($periode_dari,$periode_sampai,$Data)
	{
		date_default_timezone_set('Asia/Jakarta'); 
        $tanggal_print = date('d-m-Y H:i:s');

        $titleReport = 'LAPORAN BARANG';	  

			if (count($Data) < 1) { 
				exit('Tidak ada data');
			} 

			$header = '<table border="0" style="width:297mm; font-size:15px;">
					<tr>
						<td>
							<b>
								'.$titleReport.'
							</b>
						</td>
						<td align="right" style="font-size:12px;">
							Print Date : '.$tanggal_print.'
						</td>
					</tr>
					<tr>
						<td >
							<b>
								TOKO BERKAH JAYA
							</b>
						</td>
						<td align="right" style="font-size:12px;">
							PERIODE : '.$periode_dari.' - '.$periode_sampai.'
						</td>
					</tr>
					<tr>
						<td colspan="2">
							<hr>
						</td>
					</tr>  
				</table>';			
 
				$no = 0;
 
				$GrandTotal= 0; 
				$GrandTotalAsli= 0; 
				$content = '<br><table style="width:297mm"> 
									<tr>
										<th style="text-align: left; width: 30%; font-size: 13px;">Nama Barang</th> 
										<th style="text-align: left; width: 10%; font-size: 13px;">Qty</th> 
										<th style="text-align: left; width: 15%; font-size: 13px;">Harga Omzet</th> 
										<th style="text-align: left; width: 15%; font-size: 13px;">Total Omzet</th> 
										<th style="text-align: left; width: 15%; font-size: 13px;">Harga Jual</th> 
										<th style="text-align: left; width: 15%; font-size: 13px;">Total Jual</th> 
									</tr> 
									<tr>
										<td colspan="6">
											<hr>
										</td>
									</tr>  
									';

				foreach ($Data as $key => $c) {  

						$GrandTotal += $c->GrandTotal;  
						$GrandTotalAsli += $c->GrandTotalAsli;  
						$content .= '<tr>
									    <td>'.trim($c->NamaBarang).' '.trim($c->NamaWarna).'</td> 
									    <td>'.trim($c->Total).'</td> 
									    <td>'.number_format($c->HargaAsli, 2, ",", ".").'</td>
									    <td>'.number_format($c->GrandTotalAsli, 2, ",", ".").'</td>
									    <td>'.number_format($c->Harga, 2, ",", ".").'</td>
									    <td>'.number_format($c->GrandTotal, 2, ",", ".").'</td>
									</tr>'; 
					$no++;

				}

			
				$GrandTotalFinal = $GrandTotal- $GrandTotalAsli;
					$content .='<tr>
						<td colspan="8">
							<hr>
						</td>
					</tr> '; 
					$content .= '	
									<tr>
										<td colspan="3"></td>    
										<td colspan="2"><b>Grand Total Jual</b></td>   
										<td ><b>'.number_format($GrandTotal,2,",",".").'</b></td>   
									</tr>
									<tr>
										<td colspan="3"></td>    
										<td colspan="2"><b>Grand Total Modal</b></td>      
										<td ><b>('.number_format($GrandTotalAsli,2,",",".").')</b></td>   
									</tr>
									<tr>
										<td colspan="3"></td>  
										<td colspan="3">
											<hr>
										</td>
									</tr>  
									<tr>
										<td colspan="3"></td>    
										<td colspan="2"><b>GRAND TOTAL FINAL</b></td>         
										<td ><b>'.number_format($GrandTotalFinal,2,",",".").'</b></td>   
									</tr>
								';
					$content .= '</table>';
				$footer = "";
         

        if ($this->preview_flag === 1)
		{
			echo $header.$content.$footer;
		} 
        if ($this->pdf_flag === 1)
		{
			$this->CreatePDF($header,$content,$footer);
		}  

	}
  
	public function CreatePDF($header, $html, $footer)
	{
	    try {
	        
			$mpdf = new \Mpdf\Mpdf(array(
				'mode' => '',
				'format' => 'A4',
				'default_font_size' => 8,
				'default_font' => 'arial',
				'margin_left' => 10,
				'margin_right' => 10,
				'margin_top' => 20,
				'margin_bottom' => 10,
				'margin_header' => 10,
				'margin_footer' => 0,
				'orientation' => 'P'
			)); 
 
	        $mpdf->SetHTMLHeader($header);
	        $mpdf->SetHTMLFooter($footer); 
	        $mpdf->WriteHTML($html); 
			date_default_timezone_set('Asia/Jakarta'); 
	        $tanggal_print = date('d-m-Y-H-i-s');

	        $output_dir = FolderReport;
	        $namaFile = str_replace('/', '-', $tanggal_print);
	        $folder_path = $output_dir;
	        $file_path = $folder_path . '/' . $namaFile . '.pdf';
 
	        if (!file_exists($folder_path)) {
	            mkdir($folder_path, 0777, true); 
	        } 
	        $mpdf->Output($file_path, \Mpdf\Output\Destination::FILE);  
			$mpdf->Output($tanggal_print.'.pdf', \Mpdf\Output\Destination::INLINE);
	        
	    } catch (\Mpdf\MpdfException $e) { 
	        echo 'Error creating PDF: ' . $e->getMessage();
	    }
	}


	public function CreateExcel()
	{
		echo "Create Excel";
	}

 
}
