<?php
defined('BASEPATH') OR exit('No direct script access allowed');
 
    require 'vendor/autoload.php';
    use PhpOffice\PhpSpreadsheet\Spreadsheet;
    use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class ReportPenjualan extends CI_Controller	 {

	function __construct()
	{
		parent::__construct();  
	}

	public function index()
	{ 
		$data['formDest'] = "ReportBRPNRP/ProsesNRP";
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

		if ($this->pdf_flag === 1 || $this->preview_flag === 1)
		{
			$this->CreateHtml($dp1,$dp2);
		} 
		else
		{
			$this->CreateExcel();
		}
	}

	public function CreateHtml($dp1,$dp2)
	{
		date_default_timezone_set('Asia/Jakarta'); 
        $tanggal_print = date('d-m-Y H:i:s');

        // Header HTML
        $header = '
        <div style="text-align: center;  "> 
            <div style="font-size: 24px; text-align: center;"> 
                <b>'.GlobCompany.'</b>
            </div> 
            <div style="font-size: 14px; text-align: center;">  
                <b>Laporan Penjualan</b> 
                <br>  
                Periode '.$dp1.' s/d '.$dp2.'
            </div><br> 
        </div>';
        // Footer HTML
        $footer = '<br>  
        <table style="width: 100%;">
            <tr> 
                <td style="text-align: right; width: 50%;"> 
                    <div style="font-size: small; text-align: right;">
                        Dicetak pada: '.$tanggal_print.'
                    </div>
                </td>
            </tr>
        </table> '; 
        // Konten HTML
        $html = '<div ></div >';
        // $html .= ' 
        // <div > 
        //     <div class="form-section">
        //         <table style="width: 100%;">
        //             <tr>
        //                 <td style="width: 30%;">Tipe Pembayaran:</td>
        //                 <td><b>'.$DataHD->NamaTipePembayaran.'</b></td>
        //                 <td>Nama:</td>
        //                 <td><b>'.$DataHD->NamaPelanggan.'</b></td>
        //             </tr>
        //             <tr>
        //                 <td>No Transaksi:</td>
        //                 <td><b>'.$DataHD->KdPenjualan.'</b></td> 
        //                 <td>No HP:</td>
        //                 <td><b>'.$DataHD->NoHp.'</b></td>
        //             </tr>
        //         </table>
        //         <h3>Detail Barang</h3>
        //         <table id="itemsTable" style="width: 100%; border-collapse: collapse;" border="1">
        //             <thead>
        //                 <tr>
        //                     <th style="padding: 5px; width: 45%;  text-align: center;">Nama Barang</th> 
        //                     <th style="padding: 5px; width: 10%;  text-align: center;">Qty</th>
        //                     <th style="padding: 5px; width: 15%;  text-align: center;">Harga</th>
        //                     <th style="padding: 5px; width: 15%;  text-align: center;">Total</th> 
        //                 </tr>
        //             </thead>
        //             <tbody id="Detail Barang">';

        // foreach ($DataDT as $key => $value) {
        //     $html .= '
        //                 <tr class="item"> 
        //                 <td style="padding: 5px; text-align: left;">
        //                     '.$value->NamaBarang.' - '.$value->Warna.'
        //                 </td>
        //                 <td style="padding: 5px; text-align: center;">
        //                     '.$value->Qty.'
        //                 </td>
        //                 <td style="padding: 5px; text-align: right;">
        //                     '.number_format($value->Harga, 2, ',', '.').'
        //                 </td>
        //                 <td style="padding: 5px; text-align: right;">
        //                     '.number_format($value->Total, 2, ',', '.').'
        //                 </td> 
        //             </tr>';
        // }

        // $html .= '
        //             </tbody> 
        //             <tfoot> 
        //                 <tr> 
        //                     <th colspan="1" style="padding: 5px; text-align: right;">
        //                         <b>Grand Total</b>
        //                     </th>
        //                     <th colspan="3" style="padding: 5px; text-align: right;"> 
        //                         <b>'.number_format($DataHD->GrandTotal, 2, ',', '.').'</b> 
        //                     </th> 
        //                 </tr> 
        //             </tfoot>
        //         </table>  
        //     </div>
        // </div>';

        if ($this->preview_flag === 1)
		{
			echo $header.$html.$footer;
		} 
        if ($this->pdf_flag === 1)
		{
			$this->CreatePDF($header,$html,$footer);
		}  

	}

	public function CreatePDF($header, $html, $footer)
	{
	    try {
	        $mpdf = new \Mpdf\Mpdf([
	            'format' => 'A4', // Format default tidak berpengaruh karena kita akan menentukan ukuran kertas kustom
	            'default_font' => 'tahoma',
	            'margin_left' => 5,
	            'margin_right' => 5,
	            'margin_top' => 25,
	            'margin_bottom' => 45,
	            'margin_header' => 3,
	            'margin_footer' => 3,
	            'orientation' => 'P',
	            // 'format_custom' => [100, 150] // Ukuran kertas kustom dalam milimeter
	        ]);

	        // Set header dan footer
	        $mpdf->SetHTMLHeader($header);
	        $mpdf->SetHTMLFooter($footer);

	        // Write HTML content
	        $mpdf->WriteHTML($html);

	        // Define output directory and file name

			date_default_timezone_set('Asia/Jakarta'); 
	        $tanggal_print = date('d-m-Y-H-i-s');

	        $output_dir = FolderReport;
	        $namaFile = str_replace('/', '-', $tanggal_print);
	        $folder_path = $output_dir;
	        $file_path = $folder_path . '/' . $namaFile . '.pdf';

	        // Check and create directory if not exists
	        if (!file_exists($folder_path)) {
	            mkdir($folder_path, 0777, true); // Membuat folder baru dengan izin 0777
	        }

	        // Save PDF file to the specified path
	        $mpdf->Output($file_path, \Mpdf\Output\Destination::FILE);  
			$mpdf->Output($tanggal_print.'.pdf', \Mpdf\Output\Destination::INLINE);
	        
	    } catch (\Mpdf\MpdfException $e) {
	        // Handle the error
	        echo 'Error creating PDF: ' . $e->getMessage();
	    }
	}


	public function CreateExcel()
	{
		echo "Create Excel";
	}

 
}
