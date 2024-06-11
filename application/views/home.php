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
            color: black;
            overflow: hidden; /* Menghindari teks yang terlalu panjang keluar dari kotak */
            white-space: nowrap; /* Mencegah teks berjatuhan ke bawah */
            width: 100%; /* Mengisi lebar layar */
        }

        .bottom-left h1 {
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
        .bottom-right {
            position: fixed;
            bottom: 0;
            right: 0;
            padding: 10px;
            background-color: transparent;
            color: black;
        }
    </style>
</head>
<body>
 
<div class="bottom-left">
    <h1><?= GlobCompany ?> - Version : <?php echo $version; ?></h1>
</div>
<!-- <div class="bottom-right">
    Version : <?php echo $version; ?>
</div> -->

</body>
</html>
