<?php
// Informasi login Jenkins
$jenkinsUrl = 'http://192.168.100.7:8080';
$username = 'ican';
$password = 'ican123';

// Data login
$data = array(
    'j_username' => $username,
    'j_password' => $password
);

// URL endpoint login Jenkins
$loginUrl = $jenkinsUrl . '/j_acegi_security_check';

// Inisialisasi curl
$curl = curl_init();
// Set opsi curl
curl_setopt_array($curl, array(
    CURLOPT_URL => $loginUrl,
    CURLOPT_POST => true,
    CURLOPT_POSTFIELDS => http_build_query($data),
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_FOLLOWLOCATION => true, // Mengikuti pengalihan
    CURLOPT_COOKIEJAR => 'cookies.txt', // Menyimpan cookie untuk sesi
    CURLOPT_COOKIEFILE => 'cookies.txt', // Menggunakan cookie untuk sesi
));

// Eksekusi permintaan curl
$response = curl_exec($curl);

// Cek jika login berhasil
if (strpos($response, 'Sign Out') !== false) {
    echo 'Login berhasil!';
} else {
    echo 'Login gagal!';
}

// Tutup curl
curl_close($curl);
?>
