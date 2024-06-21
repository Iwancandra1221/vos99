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
            overflow: hidden; 
            white-space: nowrap; 
            width: 100%; 
        }

        .bottom-left h2 {
            animation: moveLeftToRight 10s linear infinite; 
        }

        @keyframes moveLeftToRight {
            0% {
                transform: translateX(-100%);  
            }
            100% {
                transform: translateX(100%); 
            }
        } 
        
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
            top: 60px; 
            left: 0;
            padding: 20px;
            background-color: white;
            color: black;
            margin: 10px; 
        }

        .top-left ul {
            max-height: 200px; 
            overflow-y: auto; 
            padding-left: 20px; 
        }


        .top-left2 {
            position: fixed;
            top: 380px; 
            left: 0;
            padding: 20px;
            background-color: white;
            color: black;
            margin: 10px; 
        }

        .top-left2 ul {
            max-height: 200px; 
            overflow-y: auto; 
            padding-left: 20px; 
        }

        .overdue-link {
            color: white; 
            text-decoration: none;  
        }

        .overdue-link:hover {
            color: #ffba08; 
        }
         h3 { 
            padding-left: 20px;  
            padding-right: 20px; 
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
