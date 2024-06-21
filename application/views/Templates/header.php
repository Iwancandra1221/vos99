<!DOCTYPE html>
<html lang="en">
<head>  
    <meta charset="UTF-8">
    <title><?php echo $title; ?></title>    
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css"> 
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script> 
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css"> 
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script> 
    <link rel="stylesheet" href="https://cdn.datatables.net/2.0.8/css/dataTables.dataTables.min.css"> 
    <script src="https://cdn.datatables.net/2.0.8/js/dataTables.min.js"></script> 
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-default ">
    <a class="navbar-brand" href="<?php echo site_url('MainController'); ?>">
        <!-- <img src="<?php echo base_url('images/vos99.jpg'); ?>" alt="Logo" style="height: 30px; margin-right: 10px;"> -->
       <?= GlobCompany ?>
    </a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav mr-auto">
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    Master
                </a>
                <div class="dropdown-menu" aria-labelledby="navbarDropdown"> 
                    <a class="dropdown-item" href="<?php echo site_url('MsUser'); ?>">Master User</a>
                    <a class="dropdown-item" href="<?php echo site_url('MsPelanggan'); ?>">Master Pelanggan</a>
                    <a class="dropdown-item" href="<?php echo site_url('MsTipePembayaran'); ?>">Master Tipe Pembayaran</a>
                    <a class="dropdown-item" href="<?php echo site_url('MsWarna'); ?>">Master Warna</a>
                    <a class="dropdown-item" href="<?php echo site_url('MsTipe'); ?>">Master Tipe</a>
                    <a class="dropdown-item" href="<?php echo site_url('MsMerk'); ?>">Master Merk</a>
                    <a class="dropdown-item" href="<?php echo site_url('MsBarang'); ?>">Master Barang</a>
                    <a class="dropdown-item" href="<?php echo site_url('MsStockBarang'); ?>">Master Stock Barang</a>
                    <a class="dropdown-item" href="<?php echo site_url('SignatureController'); ?>">Tanda Tangan Electronic</a> 
                </div>
            </li>

            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    Transaction
                </a>
                <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                    <a class="dropdown-item" href="<?php echo site_url('Penjualan'); ?>">Penjualan</a> 
                </div>
            </li>


            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    Report
                </a>
                <div class="dropdown-menu" aria-labelledby="navbarDropdown" >
                    <a class="dropdown-item" href="<?php echo site_url('ReportPenjualan'); ?>">Laporan Penjualan</a> 
                </div>
            </li> 
            <li class="nav-item">
                <a class="nav-link" href="<?php echo site_url('about'); ?>">About</a>
            </li> 
        </ul>
        <ul class="navbar-nav">
            <li class="nav-item">
                <a class="nav-link" href="<?php echo site_url('Logout'); ?>">Logout</a>
            </li>
        </ul>
    </div>
</nav> 
</body>
</html>
 
    <style>  

        body {  
            background-image: url('<?php echo base_url('images/background.jpg'); ?>'); 
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            height: 100vh; 
            margin: 0; 
            padding: 0; 
            color: white;
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f8f9fa; 
        }   
        .center-title { 
            color: Black; 
            text-align: center;
            font-size: 2.5em; 
            margin-top: 20px; 
        }

        @media only screen and (max-width: 600px) { 
            body {
                font-size: 12px;
            }
            .container {
                width: 90%;
            }
        } 
        @media only screen and (min-width: 1024px) { 
            body {
                font-size: 12px;
            }
            .container {
                width: 80%;
            }
        }
        .dropdown-submenu {
              position: relative;
          }

        .dropdown-submenu .dropdown-menu {
            top: 0;
            left: 100%;
            margin-top: -1px;
        }

        :root {
              --main-color: #2b2d42;
              --hover-color: #ffba08;
              --nav-color: #ffffff; 
              --judul-color: #2b2d42; 
              --th-color: #0077b6;
              --ganjil-color: #f8f9fa;
              --genap-color: #dee2e6;
              --tr-hover-color: #000000;
              --tr-hover-backgroundcolor: #caf0f8; 
            }

        nav, ul, li {
            background-color: var(--main-color)!important;
            color: var(--nav-color)!important;
        }
        nav a {
            background-color: var(--main-color)!important;
            color: var(--nav-color)!important;
        }
        nav a:hover {
            background-color: var(--main-color)!important;
            color: var(--hover-color)!important;
        }
        .navbar-default .navbar-nav > li > a:focus, .navbar-default .navbar-nav > li > a:hover {
            background-color: var(--main-color)!important;
        }

        .dropdown-menu > li > a:focus, .dropdown-menu > li > a:hover { 
            background-color:  var(--main-color)!important;
        }

        .container {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            height: 95vh;
        }

        .title {
            text-align: center;
            font-size: 24px;
            margin-bottom: 20px;
        }

        .formjudul {
            display: flex;
            justify-content: center;
            align-items: flex-start;
            width: 100%;
            background-color: var(--judul-color);
            color: white;
            border: 1px solid #ccc; 
            padding: 5px;
            border-top-left-radius: 20px; /* lengkungan bawah kiri */
            border-top-right-radius: 20px; /* lengkungan bawah kanan */
        }

        .formisi {
            display: flex;
            justify-content: center;
            align-items: flex-start;
            width: 100%;
            background-color: #fff;
            border: 1px solid #ccc; 
            padding: 10px;
            border-bottom-left-radius: 20px; /* lengkungan bawah kiri */
            border-bottom-right-radius: 20px; /* lengkungan bawah kanan */
        }

        .left {
            flex: 1;
            padding-right: 20px;
        }

        .right {
            flex: 1;
            padding-left: 20px;
        }

        table {
            border-collapse: collapse;
            width: 100%;
        }

        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
            white-space: nowrap;
        } 
        #myTable tr {
            background-color: var(--ganjil-color);
            color: black;
        }

        #myTable th {
            background-color: var(--th-color);
            color: white;
        }

        #myTable tr:nth-child(even) {
            background-color:  var(--genap-color);
        }

        #myTable tr:hover { 
            background-color:  var(--tr-hover-backgroundcolor);
            color: var(--tr-hover-color);
        }

        .form-group {
            margin-bottom: 15px;
        }

        .form-group label {
            font-weight: bold;
        }

        .form-group input,
        .form-group select {
            width: calc(100% - 12px);
            padding: 5px;
            border: 1px solid #ccc;
            border-radius: 3px;
        }

        .buttons {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
            margin-top: 10px;
            margin-button: 10px;
        }

        .buttons button {
            padding: 8px 20px;
            background-color: var(--main-color);
            color: #fff;
            border: none;
            border-radius: 3px;
            cursor: pointer;
            flex: 1 1 calc(33.33% - 10px);
            text-align: center;
        }

        .buttons button:hover {
            background-color: var(--th-color);
        }

        .buttons button:disabled {
            background-color: #d3d3d3; /* Disabled button color */
            color: #808080; /* Disabled button text color */
            cursor: not-allowed;
        }

        .error-message {
            color: red;
            font-size: 12px;
        }
    </style>
