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
        $this->load->model('WarnaModel');
        $this->load->model('Signature_model');
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
        $data['listBarang'] = $this->BarangModel->get_datas(); 
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
        $data['listBarang'] = $this->BarangModel->get_datas(); 
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
        $data['listBarang'] = $this->BarangModel->get_datas(); 
        $data['listPelanggan'] = $this->PelangganModel->getListPelanggan(); 
        $data['listTipePembayaran'] = $this->TipePembayaranModel->getListTipePembayaran();  
        $data['title'] = ucfirst('Add Penjualan'); 
        $data['AutoNumber'] = $this->PenjualanModel->generate_next_number();
        $this->load->view('templates/header', $data);
        $this->load->view('PenjualanAdd', $data);
        $this->load->view('templates/footer', $data);
    }

    public function Save()
    {
        $this->db->trans_begin(); // Memulai transaksi

        try {
            $KdPenjualan = $this->input->post('KdPenjualan');
            $KdPelanggan = $this->input->post('KdPelanggan');
            $KdTipePembayaran = $this->input->post('KdTipePembayaran');
            $GrandTotal = $this->input->post('GrandTotal');
            $TglJT = $this->input->post('tgljt');
            $CreatedBy = 'Admin';
            $items = $this->input->post('items');
            $existingData = $this->PenjualanModel->get_data_by_KdPenjualan($KdPenjualan);
            $existingDataDT = $this->PenjualanModel->get_dt_by_KdPenjualan($KdPenjualan);
 
            if ($items == "")
            {
                $this->session->set_flashdata('success_message', 'Detail Barang Tidak Boleh Kosong'); 
                redirect('Penjualan');
            }
 
            if ($existingData) {
                $data = array(
                    'KdPelanggan' => $KdPelanggan,
                    'KdTipePembayaran' => $KdTipePembayaran,
                    'GrandTotal' => $GrandTotal
                );
                $this->PenjualanModel->update_data($KdPenjualan, $data);
                $this->PenjualanModel->delete_data_dt($KdPenjualan);

                foreach ($existingDataDT as $item) { 
                    $this->BarangModel->update_qty_edit($item->KdBarang, $item->Qty);
                } 
                foreach ($items as $item) {
                    $itemData = array(
                        'KdPenjualan' => $KdPenjualan,
                        'KdBarang' => $item['KdBarang'],
                        'Qty' => $item['Qty'],
                        'Harga' => $item['Harga'],
                        'Total' => $item['Total']
                    );
                    $this->PenjualanModel->insert_dt($itemData);

                    $QtyCurrent = $this->BarangModel->get_qty($item['KdBarang']);

                    if ($item['Qty'] < 1)
                    {
                        $this->session->set_flashdata('success_message', 'Qty Barang Tidak Boleh Kosong / 0 '); 
                        redirect('Penjualan');
                    } 

                    if ($item['Qty'] > $QtyCurrent)
                    {
                        $this->session->set_flashdata('success_message', 'Stock Barang ' .$item['KdBarang']. ' Tidak Cukup, Sisa Stock Barang adalah '.$QtyCurrent); 
                        redirect('Penjualan');
                    } 
                    $this->BarangModel->update_qty_after_sale($item['KdBarang'], $item['Qty']);
                }
                $this->session->set_flashdata('success_message', 'Berhasil Update Transaksi');
            } else {
                $data = array(
                    'KdPenjualan' => $KdPenjualan,
                    'KdPelanggan' => $KdPelanggan,
                    'KdTipePembayaran' => $KdTipePembayaran,
                    'Tanggal_Tempo' => $TglJT,
                    'GrandTotal' => $GrandTotal,
                    'CreatedBy' => $CreatedBy,
                    'Lunas' => 0,
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
 
                    $QtyCurrent = $this->BarangModel->get_qty($item['KdBarang']); 
 
                    if ($item['Qty'] < 1)
                    {
                        $this->session->set_flashdata('success_message', 'Qty Barang Tidak Boleh Kosong / 0 '); 
                        redirect('Penjualan');
                    } 

                    if ($item['Qty'] > $QtyCurrent)
                    {
                        $this->session->set_flashdata('success_message', 'Stock Barang ' .$item['KdBarang']. ' Tidak Cukup, Sisa Stock Barang adalah '.$QtyCurrent); 
                        redirect('Penjualan');
                    }

                    $this->BarangModel->update_qty_after_sale($item['KdBarang'], $item['Qty']);
                }
                $this->session->set_flashdata('success_message', 'Berhasil Simpan Transaksi');
            }

            // Commit transaksi jika tidak ada kesalahan
            $this->db->trans_commit();
        } catch (Exception $e) {
            // Rollback transaksi jika terjadi kesalahan
            $this->db->trans_rollback();
            $this->session->set_flashdata('error_message', 'Gagal menyimpan transaksi: ' . $e->getMessage());
        }

        redirect('Penjualan');
    }
 
    public function Delete()
    {
        $this->db->trans_start(); // Mulai transaksi

        try {
            $KdPenjualan = $this->input->post('KdPenjualan');
            $existingDataDT = $this->PenjualanModel->get_dt_by_KdPenjualan($KdPenjualan); 
            // Mengembalikan kuantitas barang yang dijual sebelumnya
            foreach ($existingDataDT as $item) {
                $this->BarangModel->update_qty_edit($item->KdBarang, $item->Qty);
            }
            
            // Menghapus data penjualan
            $this->PenjualanModel->delete_data($KdPenjualan);

            // Commit transaksi
            $this->db->trans_commit();
            
            echo "Success";
        } catch (Exception $e) {
            // Rollback transaksi jika terjadi kesalahan
            $this->db->trans_rollback();
            
            echo "Failed: " . $e->getMessage();
        }
    }



    public function CetakNota() {
        $Tipe = $this->input->get('Tipe');
        $KdPenjualan = $this->input->get('KdPenjualan');
        //$KdPenjualan = 'PNJ-000001';
        $DataHD = $this->PenjualanModel->get_hd_by_KdPenjualan($KdPenjualan);
        $DataDT = $this->PenjualanModel->get_hd_by_KdPenjualans($KdPenjualan);
        $listPelanggan = $this->BarangModel->get_datas(); 
        $listBarang = $this->PelangganModel->getListPelanggan(); 
        $output_dir = FolderBelumLunas;
        $output_lunas_dir = FolderLunas;
        // if (!is_dir($output_dir)) {
        //     mkdir($output_dir, 0777, true);
        // }
 

        $TandaTangan = $this->Signature_model->get_data();
        // Tanggal print
        date_default_timezone_set('Asia/Jakarta'); 
        $tanggal_print = date('d-m-Y H:i:s');
        //echo $tanggal_print;die;
        // Header HTML
        $header = '
        <div style="text-align: center;  "> 
            <div style="font-size: 24px; text-align: center;"> 
                <b>'.GlobCompany.'</b>
            </div> 
            <div style="font-size: 14px; text-align: center;">  
                '.GlobAlamat.' 
                <br>  
                No HP : '.GlobNoHP.', No BCA : '.GLobNoRek.' ('.GlobNamaBCA.')
            </div><br> 
        </div>';
        // Footer HTML
        $footer = '<br> 
        <div style="text-align: right; font-size: small;">
            <table style="width: 100%;">
                <tr>
                    <td style="text-align: left; width: 50%;">
                        <b>Penerima</b><br><br><br><br><br> 
                        ____________________<br> 
                    </td> 
                    <td style="text-align: right; width: 50%;">
                        <b>Hormat Kami,</b>
                        <br> 
                        <img src="'.$TandaTangan.'" style="max-width: 10%; height: auto;">
                        <br>
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



        <table style="width: 100%;">
            <tr>
                <td style="text-align: left; width: 50%;">
                    <div style="text-align: center; font-size: small;">
                        &copy; ' . date('Y') .' '. GlobCompany.'. All Rights Reserved.
                    </div>
                </td>
                <td style="text-align: right; width: 50%;"> 
                    <div style="font-size: small; text-align: right;">
                        Dicetak pada: '.$tanggal_print.'
                    </div>
                </td>
            </tr>
        </table> '; 
        // Konten HTML

        $html = ' 
        <div > 
            <div class="form-section">
                <table style="width: 100%;">
                    <tr>
                        <td style="width: 30%;">Tipe Pembayaran:</td>
                        <td><b>'.$DataHD->NamaTipePembayaran.'</b></td>
                        <td>Nama:</td>
                        <td><b>'.$DataHD->NamaPelanggan.'</b></td>
                    </tr>
                    <tr>
                        <td>No Transaksi:</td>
                        <td><b>'.$DataHD->KdPenjualan.'</b></td> 
                        <td>No HP:</td>
                        <td><b>'.$DataHD->NoHp.'</b></td>
                    </tr>
                </table>
                <h3>Detail Barang</h3>
                <table id="itemsTable" style="width: 100%; border-collapse: collapse;" border="1">
                    <thead>
                        <tr>
                            <th style="padding: 5px; width: 45%;  text-align: center;">Nama Barang</th> 
                            <th style="padding: 5px; width: 10%;  text-align: center;">Qty</th>
                            <th style="padding: 5px; width: 15%;  text-align: center;">Harga</th>
                            <th style="padding: 5px; width: 15%;  text-align: center;">Total</th> 
                        </tr>
                    </thead>
                    <tbody id="Detail Barang">';

        foreach ($DataDT as $key => $value) {
            $html .= '
                        <tr class="item"> 
                        <td style="padding: 5px; text-align: left;">
                            '.$value->NamaBarang.' - '.$value->Warna.'
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
                            <th colspan="1" style="padding: 5px; text-align: right;">
                                <b>Grand Total</b>
                            </th>
                            <th colspan="3" style="padding: 5px; text-align: right;"> 
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
                'format' => 'A4', // Format default tidak berpengaruh karena kita akan menentukan ukuran kertas kustom
                'default_font' => 'tahoma',
                'margin_left' => 5,
                'margin_right' => 5,
                // 'margin_top' => 25,
                // 'margin_bottom' => 45,
                // 'margin_header' => 3,
                // 'margin_footer' => 3,
                'orientation' => 'L',
                'format_custom' => [100, 150] // Ukuran kertas kustom dalam milimeter
            ));


            // Set header dan footer
            //$mpdf->SetHTMLHeader($header);
            //$mpdf->SetHTMLFooter($footer);

            // Tulis konten ke dalam file PDF
            if ($Tipe === "Print")
            { 
                $mpdf->WriteHTML($header.$html.$footer);
            }
            else
            {  
                $relativePath = 'images/lunas.png'; 
                $Stamp = base_url() . $relativePath; 
                $x = 30; // Koordinat X (dalam mm) di halaman PDF
                $y = 30; // Koordinat Y (dalam mm) di halaman PDF
                $w = 100; // Lebar gambar latar belakang (dalam mm)
                $h = 100; // Tinggi gambar latar belakang (dalam mm)

                // Tambahkan gambar latar belakang ke MPDF dengan ukuran dan posisi yang ditentukan
                $mpdf->SetWatermarkImage($Stamp, $x, $y, $w, $h);
                $mpdf->showWatermarkImage = true; 
                $mpdf->watermarkImageAlpha = 0.1; 
                // $htmlfinal = ''; 
                // $htmlfinal .= "<style>
                //        .defaultForms {
                //         position: relative;  
                //     }

                //     .defaultForms::after {
                //         content: '';
                //         background-image: url(".$Stamp.");
                //         background-size: 100% 100%;
                //         background-position: center;
                //         background-repeat: no-repeat;
                //         opacity: 0.1; 
                //         position: absolute;  
                //         top: 0;
                //         left: 0;
                //         right: 0;
                //         bottom: 0;
                //         z-index: -1; 
                //     }
                // </style>"; 
                //$htmlfinal .= '<div class="defaultForms">'; 
                $htmlfinal = $header.$html.$footer;
                //$htmlfinal .= '</div>';
                $mpdf->WriteHTML($htmlfinal);
            }

            // Tentukan path file output

            $namaFile = str_replace('/', '-', $DataHD->KdPenjualan);
            if ($Tipe === "Print")
            { 
                $folder_path = $output_dir . '/' . $DataHD->NamaPelanggan;
            }
            else
            { 
                $folder_path = $output_lunas_dir . '/' . $DataHD->NamaPelanggan;
            }
            $file_path = $folder_path . '/' . $namaFile . '.pdf'; 
            if (!file_exists($folder_path)) {
                mkdir($folder_path, 0777, true); // Membuat folder baru dengan izin 0777
            }

            // Simpan file PDF ke path yang ditentukan
            $mpdf->Output($file_path, \Mpdf\Output\Destination::FILE);  

            if ($Tipe === "Print")
            { 
                $mpdf->Output($DataHD->KdPenjualan.'.pdf', \Mpdf\Output\Destination::INLINE);
            }
            else
            {  
                $existingData = $this->PenjualanModel->get_data_by_KdPenjualan($KdPenjualan);
                 if ($existingData) {
                    $data = array(
                        'Lunas' => 1,
                        'LunasDate' => date('Y-m-d H:i:s')
                    );
                    $this->PenjualanModel->update_data($KdPenjualan, $data);
                }

                $mpdf->Output($DataHD->KdPenjualan.'.pdf', \Mpdf\Output\Destination::INLINE);

                //$this->session->set_flashdata('success_message',  'Nota berhasil di Lunasin');
                //redirect('Penjualan');  
            }
 

        }
    }
 
}
?>
