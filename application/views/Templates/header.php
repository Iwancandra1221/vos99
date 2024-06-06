<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title><?php echo $title; ?></title> 
    <script src="https://code.jquery.com/jquery-3.5.1.js"></script>   
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/2.0.8/css/dataTables.dataTables.min.css">
    <script src="https://cdn.datatables.net/2.0.8/js/dataTables.min.js"></script>

    <style>
        /* CSS untuk menambahkan background gambar full layar */
        body {
            background-image: url('<?php echo base_url('images/background.jpg'); ?>'); 
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            height: 100vh; /* Set tinggi body agar sesuai dengan tinggi layar */
            margin: 0; /* Hapus margin default */
            padding: 0; /* Hapus padding default */
            color: white; /* Set warna teks menjadi putih agar terlihat jelas di atas background gambar */
        }
        .container {
            padding: 100px; /* Tambahkan padding ke dalam kontainer untuk menjaga konten agar tidak terlalu dekat dengan tepi layar */
        }      
        .center-title { 
            color: Black; 
            text-align: center;
            font-size: 2.5em; /* Atur ukuran font sesuai kebutuhan */
            margin-top: 20px; /* Atur margin atas sesuai kebutuhan */
        }
    </style>
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-light bg-light">
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
                    <a class="dropdown-item" href="<?php echo site_url('MsBarang'); ?>">Master Barang</a>
                    <a class="dropdown-item" href="<?php echo site_url('MsUser'); ?>">Master User</a>
                    <a class="dropdown-item" href="<?php echo site_url('MsPelanggan'); ?>">Master Pelanggan</a>
                    <a class="dropdown-item" href="<?php echo site_url('MsTipePembayaran'); ?>">Master Tipe Pembayaran</a>
                </div>
            </li>

            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    Transaction
                </a>
                <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                    <a class="dropdown-item" href="<?php echo site_url('Penjualan'); ?>">Penjualan</a>
                    <a class="dropdown-item" href="<?php echo site_url('Pembelian'); ?>">Pembelian</a>
                </div>
            </li>


            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    Report
                </a>
                <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                    <a class="dropdown-item" href="<?php echo site_url('ReportPenjualan'); ?>">Laporan Penjualan</a>
                    <a class="dropdown-item" href="<?php echo site_url('ReportPembelian'); ?>">Laporan Pembelian</a>
                </div>
            </li>
 
            <li class="nav-item">
                <a class="nav-link" href="<?php echo site_url('about'); ?>">About</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="<?php echo site_url('contact'); ?>">Contact</a>
            </li> 
        </ul>
        <ul class="navbar-nav">
            <li class="nav-item">
                <a class="nav-link" href="<?php echo site_url('Logout'); ?>">Logout</a>
            </li>
        </ul>
    </div>
</nav>
 <!-- <h1 class="center-title"><?php echo $title; ?></h1> -->
</body>
</html>
