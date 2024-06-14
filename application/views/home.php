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
        .bottom-right {
            position: fixed;
            top: 100;
            right: 0;
            padding: 10px;
            background-color: transparent;
            color: white;
        }
    </style>
</head>
<body>
 
<div class="bottom-left">
    <h2><?= GlobCompany . " - " .GlobAlamat . " (".GlobNoHP.") - " .GlobNamaBCA. " (".GLobNoRek.") " ?> </h2>
</div>
<div class="bottom-right">
    <h2>Version : <?php echo Version; ?></h2>
</div> 

</body>
</html>
