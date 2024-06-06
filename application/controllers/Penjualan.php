<?php
defined('BASEPATH') OR exit('No direct script access allowed');
 
    require 'vendor/autoload.php';
    use PhpOffice\PhpSpreadsheet\Spreadsheet;
    use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class Penjualan extends CI_Controller {

    function __construct()
    {
        parent::__construct();
        $this->load->model('PenjualanModel');
        $this->load->model('PelangganModel');
        $this->load->model('TipePembayaranModel');
        $this->load->model('BarangModel');
        $this->load->library('session'); 
    }

    public function index()
    {  
        $data['title'] = ucfirst('Master Penjualan');
        $data['Penjualan'] = $this->PenjualanModel->get_list_data(); 
        $this->load->view('templates/header', $data);
        $this->load->view('PenjualanView', $data);
        $this->load->view('templates/footer', $data);
    }

    public function Edit()
    { 
        $KdPenjualan = $this->input->get('KdPenjualan'); 
        $dataHD = $this->PenjualanModel->get_hd_by_KdPenjualan($KdPenjualan);
        $dataDT = $this->PenjualanModel->get_dt_by_KdPenjualan($KdPenjualan);
        $data['DataHD'] = $dataHD;
        $data['DataDT'] = $dataDT; 
        $data['mode'] = 'Edit';
        $data['listBarang'] = $this->BarangModel->getListBarang(); 
        $data['listPelanggan'] = $this->PelangganModel->getListPelanggan(); 
        $data['listTipePembayaran'] = $this->TipePembayaranModel->getListTipePembayaran(); 
        $data['title'] = ucfirst('Edit Penjualan'); 
        $data['AutoNumber'] = $this->PenjualanModel->generate_next_number();
        $this->load->view('templates/header', $data);
        $this->load->view('PenjualanEditView', $data);
        $this->load->view('templates/footer', $data);
    }

    public function View()
    { 
        $KdPenjualan = $this->input->get('KdPenjualan');
        $dataHD = $this->PenjualanModel->get_hd_by_KdPenjualan($KdPenjualan);
        $dataDT = $this->PenjualanModel->get_dt_by_KdPenjualan($KdPenjualan);
        $data['DataHD'] = $dataHD;
        $data['DataDT'] = $dataDT;    
        $data['mode'] = 'View';
        $data['listBarang'] = $this->BarangModel->getListBarang(); 
        $data['listPelanggan'] = $this->PelangganModel->getListPelanggan(); 
        $data['listTipePembayaran'] = $this->TipePembayaranModel->getListTipePembayaran();  
        $data['title'] = ucfirst('View Penjualan'); 
        $data['AutoNumber'] = $this->PenjualanModel->generate_next_number();
        $this->load->view('templates/header', $data);
        $this->load->view('PenjualanEditView', $data);
        $this->load->view('templates/footer', $data);
    }
 
    public function add()
    { 
        $data['listBarang'] = $this->BarangModel->getListBarang(); 
        $data['listPelanggan'] = $this->PelangganModel->getListPelanggan(); 
        $data['title'] = ucfirst('Add Penjualan'); 
        $data['AutoNumber'] = $this->PenjualanModel->generate_next_number();
        $this->load->view('templates/header', $data);
        $this->load->view('PenjualanAdd', $data);
        $this->load->view('templates/footer', $data);
    }

    public function Save()
    { 
        $KdPenjualan = $this->input->post('KdPenjualan');
        $KdPelanggan = $this->input->post('KdPelanggan');
        $KdTipePembayaran = $this->input->post('KdTipePembayaran');
        $GrandTotal = $this->input->post('GrandTotal');
        $CreatedBy = 'Admin'; 

        $items = $this->input->post('items'); 
        $existingData = $this->PenjualanModel->get_data_by_KdPenjualan($KdPenjualan);
        if ($existingData) {
            $data = array(
                'KdPelanggan' => $KdPelanggan,
                'KdTipePembayaran' => $KdTipePembayaran,
                'GrandTotal' => $GrandTotal
            );

            $this->PenjualanModel->update_data($KdPenjualan, $data);
            $this->PenjualanModel->delete_data_dt($KdPenjualan); // Delete old details

            foreach ($items as $item) {
                $itemData = array(
                    'KdPenjualan' => $KdPenjualan,
                    'KdBarang' => $item['KdBarang'],
                    'Qty' => $item['Qty'],
                    'Harga' => $item['Harga'],
                    'Total' => $item['Total']
                );
                $this->PenjualanModel->insert_dt($itemData);
            }
            $this->session->set_flashdata('success_message', 'Berhasil Update Transaksi');
            redirect('Penjualan');
        } else {
            $data = array(
                'KdPenjualan' => $KdPenjualan,
                'KdPelanggan' => $KdPelanggan,
                'KdTipePembayaran' => $KdTipePembayaran,
                'GrandTotal' => $GrandTotal,
                'CreatedBy' => $CreatedBy,
                'CreatedDate' => date('Y-m-d H:i:s')
            );
            $this->PenjualanModel->insert_hd($data);

            foreach ($items as $item) {
                $itemData = array(
                    'KdPenjualan' => $KdPenjualan,
                    'KdBarang' => $item['KdBarang'],
                    'Qty' => $item['Qty'],
                    'Harga' => $item['Harga'],
                    'Total' => $item['Total']
                );
                $this->PenjualanModel->insert_dt($itemData);
            }  
            $this->session->set_flashdata('success_message', 'Berhasil Simpan Transaksi');
            redirect('Penjualan');
        }
    }

    public function Delete()
    {
        $KdPenjualan = $this->input->post('KdPenjualan');
        $this->PenjualanModel->delete_data($KdPenjualan);
        $this->session->set_flashdata('success_message', 'Berhasil Hapus Transaksi');
        redirect('Penjualan');
    }


    public function CetakNota() {
        $Tipe = $this->input->get('Tipe');
        $KdPenjualan = $this->input->get('KdPenjualan');
        //$KdPenjualan = 'PNJ-000001';
        $DataHD = $this->PenjualanModel->get_hd_by_KdPenjualan($KdPenjualan);
        $DataDT = $this->PenjualanModel->get_hd_by_KdPenjualans($KdPenjualan);
        $listPelanggan = $this->BarangModel->getListBarang(); 
        $listBarang = $this->PelangganModel->getListPelanggan(); 
        $output_dir = 'C://ICAN/Testing';
        if (!is_dir($output_dir)) {
            mkdir($output_dir, 0777, true);
        }
 

        // Tanggal print
        $tanggal_print = date('d-m-Y H:i:s');

        // Header HTML
        $header = '
        <div style="text-align: center;  ">
  
            <div style="font-size: small; text-align: center;"> 
                <b>'.GlobCompany.'</b>
                <br>  
                '.GlobAlamat.' 
                <br>  
                No HP : '.GlobNoHP.'
                <br>  
                No BCA : '.GLobNoRek.' ('.GlobNamaBCA.')
            </div>
            <div style="font-size: small; text-align: right;">
                Dicetak pada: '.$tanggal_print.'
            </div>
        </div>';

        // Footer HTML
        $footer = '
        <div style="text-align: right; font-size: small;">
            <table style="width: 100%;">
                <tr>
                    <td style="text-align: left; width: 50%;">
                        <b>Penerima</b><br><br><br><br><br> 
                        ____________________<br> 
                    </td>
                    <td style="text-align: right; width: 50%;">
                        <b>Hormat Kami,</b><br><br><br><br><br>
                        '.GlobCompany.'<br> 
                    </td>
                </tr>
            </table>
        </div>
        <div style="text-align: right; font-size: small;">
            <b>Keterangan:</b><br>
            Terima kasih sudah belanja di toko kami.<br>
            <i>Barang yang sudah dibeli tidak dapat ditukar kembali.</i>
        </div>

        <div style="text-align: center; font-size: small;">
            &copy; ' . date('Y') .' '. GlobCompany.'. All Rights Reserved.
        </div>'; 
        // Konten HTML
        $html = '
        <div class="defaultForm"> 
            <div class="form-section">
                <table style="width: 100%;">
                    <tr>
                        <td style="width: 30%;">Tipe Pembayaran:</td>
                        <td><b>'.$DataHD->NamaTipePembayaran.'</b></td>
                    </tr>
                    <tr>
                        <td>No Transaksi:</td>
                        <td><b>'.$DataHD->KdPenjualan.'</b></td>
                    </tr>
                    <tr>
                        <td>Nama:</td>
                        <td><b>'.$DataHD->NamaPelanggan.'</b></td>
                    </tr>
                    <tr>
                        <td>No HP:</td>
                        <td><b>'.$DataHD->NoHp.'</b></td>
                    </tr>
                </table>
                <h3>Detail Barang</h3>
                <table id="itemsTable" style="width: 100%; border-collapse: collapse;" border="1">
                    <thead>
                        <tr>
                            <th style="padding: 5px; width: 40%;  text-align: center;">Nama Barang</th>
                            <th style="padding: 5px; width: 15%;  text-align: center;">Warna</th>
                            <th style="padding: 5px; width: 15%;  text-align: center;">Qty</th>
                            <th style="padding: 5px; width: 15%;  text-align: center;">Harga</th>
                            <th style="padding: 5px; width: 15%;  text-align: center;">Total</th> 
                        </tr>
                    </thead>
                    <tbody id="Detail Barang">';

        foreach ($DataDT as $key => $value) {
            $html .= '
                        <tr class="item"> 
                        <td style="padding: 5px; text-align: left;">
                            '.$value->NamaBarang.'
                        </td>
                        <td style="padding: 5px; text-align: center;">
                            '.$value->Warna.'
                        </td>
                        <td style="padding: 5px; text-align: center;">
                            '.$value->Qty.'
                        </td>
                        <td style="padding: 5px; text-align: right;">
                            '.number_format($value->Harga, 2, ',', '.').'
                        </td>
                        <td style="padding: 5px; text-align: right;">
                            '.number_format($value->Total, 2, ',', '.').'
                        </td> 
                    </tr>';
        }

        $html .= '
                    </tbody> 
                    <tfoot> 
                        <tr> 
                            <th colspan="3" style="padding: 5px; text-align: right;">
                                <b>Grand Total</b>
                            </th>
                            <th colspan="2" style="padding: 5px; text-align: right;"> 
                                <b>'.number_format($DataHD->GrandTotal, 2, ',', '.').'</b> 
                            </th> 
                        </tr> 
                    </tfoot>
                </table>  
            </div>
        </div>';

        if ($Tipe === "View")
        {
            echo $header.$html.$footer;
        }
        else
        { 
            $mpdf = new \Mpdf\Mpdf(array(
                'mode' => '', 
                'format' => 'A4',
                /*'default_font_size' => 8,*/
                'default_font' => 'tahoma',
                'margin_left' => 10,
                'margin_right' => 10,
                'margin_top' => 40,
                'margin_bottom' => 10,
                'margin_header' => 10,
                'margin_footer' => 10,
                'orientation' => 'L'
                ));

            // Set header dan footer
            $mpdf->SetHTMLHeader($header);
            $mpdf->SetHTMLFooter($footer);

            // Tulis konten ke dalam file PDF
            $mpdf->WriteHTML($html);

            // Tentukan path file output
            $file_path = $output_dir . '/'.$DataHD->KdPenjualan.'.pdf';

            // Simpan file PDF ke path yang ditentukan
            $mpdf->Output($file_path, \Mpdf\Output\Destination::FILE); 
            
            $this->session->set_flashdata('success_message',  'Nota berhasil dibuat dan disimpan di ' . $file_path);
            redirect('Penjualan/View?KdPenjualan='.$DataHD->KdPenjualan);  
        }
    }
 
 
}
?>
