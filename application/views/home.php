<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home</title>
    <style>
        .bottom-left {
            position: fixed;
            bottom: 0;
            left: 0;
            padding: 10px;
            background-color: transparent;
            color: white;
            overflow: hidden; /* Menghindari teks yang terlalu panjang keluar dari kotak */
            white-space: nowrap; /* Mencegah teks berjatuhan ke bawah */
            width: 100%; /* Mengisi lebar layar */
        }

        .bottom-left h2 {
            animation: moveLeftToRight 10s linear infinite; /* Animasi bergerak dari kiri ke kanan selama 10 detik */
        }

        @keyframes moveLeftToRight {
            0% {
                transform: translateX(-100%); /* Memulai dari luar kotak sebelah kiri */
            }
            100% {
                transform: translateX(100%); /* Bergerak hingga keluar kotak sebelah kanan */
            }
        }
    </style>
    <style>
        .top-right {
            position: fixed;
            top: 100;
            right: 0;
            padding: 10px;
            background-color: transparent;
            color: white;
        } 

        .top-left {
            position: fixed;
            top: 60px; /* tambahkan satuan 'px' untuk top */
            left: 0;
            padding: 20px;
            background-color: white;
            color: red;
            margin: 10px; /* tambahkan margin 10px di semua sisi */
        }

        .top-left ul {
            max-height: 200px; /* atur tinggi maksimum */
            overflow-y: auto; /* tambahkan scroll vertikal jika diperlukan */
            padding-left: 20px; /* tambahkan padding untuk list */
        }


        .top-left2 {
            position: fixed;
            top: 380px; /* tambahkan satuan 'px' untuk top */
            left: 0;
            padding: 20px;
            background-color: white;
            color: red;
            margin: 10px; /* tambahkan margin 10px di semua sisi */
        }

        .top-left2 ul {
            max-height: 200px; /* atur tinggi maksimum */
            overflow-y: auto; /* tambahkan scroll vertikal jika diperlukan */
            padding-left: 20px; /* tambahkan padding untuk list */
        }

        .overdue-link {
            color: white; /* warna teks default */
            text-decoration: none; /* hapus garis bawah */
        }

        .overdue-link:hover {
            color: greenyellow; /* warna teks saat di-hover */
        }
    </style>
</head>
<body>
 
<div class="top-right">
    <h2>Version : <?php echo Version; ?></h2>
</div> 

<div class="top-left">
        <h3>Transaksi yang sudah Lewat Jatuh Temponya</h3>
        <?php if (empty($overdue_penjualan)): ?>
            <p>Tidak ada transaksi yang sudah lewat jatuh temponya.</p>
        <?php else: ?>
            <ul>
                <?php foreach ($overdue_penjualan as $penjualan): ?>

                    <?php  
                    $date = new DateTime($penjualan->Tanggal_Tempo); 
                    $formattedDate = $date->format('d/m/Y');
                    ?>

                    <li style="padding: 5px; margin: 10px;">
                        <a class="overdue-link" href="<?php echo base_url('Penjualan/View?KdPenjualan=' . $penjualan->KdPenjualan); ?>">
                            <?php echo $penjualan->KdPenjualan . ' - Tanggal JT : ' . $formattedDate .' ( ' . $penjualan->NamaPelanggan .' ) '; ?>
                        </a>
                    </li>
                <?php endforeach; ?>
            </ul>
        <?php endif; ?>
 </div>


<div class="top-left2">
        <h3>List Qty Barang sudah mau habis</h3>
        <?php if (empty($overdue_barang)): ?>
            <p >Tidak ada info.</p>
        <?php else: ?>
            <ul>
                <?php foreach ($overdue_barang as $barang): ?> 
                    <li style="padding: 5px; margin: 10px;">
                        <a class="overdue-link" href="<?php echo base_url('MsBarang'); ?>">
                            <?php echo $barang->KdBarang . ' -  '. $barang->NamaBarang . ' ( Sisa : ' . $barang->Qty. ' )'; ?>
                        </a>
                    </li>
                <?php endforeach; ?>
            </ul>
        <?php endif; ?>
 </div>

<div class="bottom-left">
    <h2><?= GlobCompany . " - " .GlobAlamat . " (".GlobNoHP.") - " .GlobNamaBCA. " (".GLobNoRek.") " ?> </h2>
</div>

</body>
</html>
