<?php
// Deteksi otomatis apakah berjalan di Localhost (Laragon) atau Hosting (InfinityFree)
$is_localhost = in_array($_SERVER['SERVER_NAME'] ?? 'localhost', ['localhost', '127.0.0.1']) || (php_sapi_name() === 'cli' && !isset($_SERVER['HTTP_HOST']));

if ($is_localhost) {
    // Konfigurasi Database Lokal (Laragon)
    $host = "localhost";
    $user = "root";
    $pass = "";
    $db   = "db_god_of_war";
} else {
    // Konfigurasi Database Hosting InfinityFree
    $host = "sql111.infinityfree.com";
    $user = "if0_43032317";
    $pass = "08092005yp";
    $db   = "if0_43032317_db_god_of_war";
}

// Proyek ini menggunakan MySQLi untuk seluruh query
$conn = mysqli_connect($host, $user, $pass, $db);

if (!$conn) {
    die("Koneksi Database Gagal: " . mysqli_connect_error());
}
?>