<?php
require 'vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

// Data yang akan diekspor
$jsonData = '{
    "result": "sukses",
    "data": [
        {
            "USERID": 5282,
            "KTP": "7308265012020001",
            "APPLICANTID": "APLC2022120070",
            "SALESMANID": "MKS-092",
            "REFID": "",
            "USEREMAIL": "andiastifitria@gmail.com",
            "ISACTIVE": 1,
            "NIK": null,
            "NAME": "A . ASTI FITRIA",
            "ALIASNAME": null,
            "BADGENUMBER": "",
            "HIREDDATE": "2023-01-01",
            "PENGANGKATAN": "2023-07-01",
            "PROMOTIONDATE": null,
            "ENDDATE": "2023-11-30",
            "GROUPID": "CMKS5",
            "DIVISIONID": "2.3.13.02.04",
            "EMPTYPEID": "EL03",
            "EMPLEVELID": "EML12",
            "EMPPOSITIONID": "JT0148",
            "BIRTHDATE": "1990-01-01",
            "GENDER": "FEMALE",
            "EMAIL": "andiastifitria@gmail.com",
            "MOBILE": "082196960857",
            "WHATSAPP": "082196960857",
            "DATABASEID": null,
            "BRANCHID": "MKS",
            "BRANCHNAME": "MAKASSAR",
            "EMPLEVEL": "SPG/M",
            "CITY": "MAKASSAR",
            "GROUPNAME": "Makassar SPG",
            "EMPTYPE": "KONTRAK",
            "DIVISIONNAME": "MKS MO",
            "EMPPOSITIONNAME": "SPG/M"
        }
    ]
}';

$data = json_decode($jsonData, true);

// Inisialisasi objek PHPExcel
$spreadsheet = new Spreadsheet();

// Atur properti dokumen
$spreadsheet->getProperties()
    ->setCreator("Your Name")
    ->setTitle("Data Export");

// Atur lembar kerja aktif
$sheet = $spreadsheet->getActiveSheet();

// Menulis judul kolom
$column = 'A';
foreach ($data['data'][0] as $key => $value) {
    $sheet->setCellValue($column . '1', $key);
    $column++;
}

// Menulis data
$row = 2;
foreach ($data['data'] as $item) {
    $column = 'A';
    foreach ($item as $key => $value) {
        $sheet->setCellValue($column . $row, $value);
        $column++;
    }
    $row++;
}

// Menetapkan lebar kolom otomatis
foreach(range('A', $sheet->getHighestDataColumn()) as $col) {
    $sheet->getColumnDimension($col)->setAutoSize(true);
}

// Mengatur header dan tipe konten file
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="data_export.xlsx"');
header('Cache-Control: max-age=0');

// Menulis file Excel
$writer = new Xlsx
