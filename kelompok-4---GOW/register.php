<?php
include 'koneksi.php';

$error = '';
$success = '';

if (isset($_POST['register'])) {
    $nama = mysqli_real_escape_string($conn, htmlspecialchars(trim($_POST['nama'])));
    $email = mysqli_real_escape_string($conn, htmlspecialchars(trim($_POST['email'])));
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    if (empty($nama) || empty($email) || empty($password) || empty($confirm_password)) {
        echo "<script>alert('Semua kolom wajib diisi!');</script>";
    } elseif ($password !== $confirm_password) {
        echo "<script>alert('Password dan Konfirmasi Password tidak cocok!');</script>";
    } else {
        // Cek apakah email sudah terdaftar
        $cek_email = mysqli_query($conn, "SELECT * FROM users WHERE email = '$email'");
        
        if (mysqli_num_rows($cek_email) > 0) {
            echo "<script>alert('Email sudah terdaftar! Gunakan email lain.');</script>";
        } else {
            // Gunakan password_hash untuk keamanan (didukung di index.php via password_verify)
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            $query = "INSERT INTO users (nama, email, password, role) VALUES ('$nama', '$email', '$hashed_password', 'user')";
            
            if (mysqli_query($conn, $query)) {
                echo "<script>alert('Registrasi Berhasil! Silakan Login.'); window.location='index.php';</script>";
                exit;
            } else {
                echo "<script>alert('Terjadi kesalahan saat mendaftar: " . mysqli_error($conn) . "');</script>";
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - God of War</title>
    <link href="https://fonts.googleapis.com/css2?family=Cinzel:wght@400;700&family=Lato:wght@400;700&display=swap" rel="stylesheet">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Cinzel', serif; overflow: hidden; }
        .container {
            position: relative; width: 100%; height: 100vh;
            background-image: url('asset/GOWRG_Wallpaper_Desktop_Boat_4k.jpg');
            background-size: cover; background-position: center;
            display: flex; justify-content: center; align-items: center;
        }
        .glass-panel {
            width: 450px;
            background: rgba(0, 0, 0, 0.75); backdrop-filter: blur(15px);
            border: 1px solid rgba(255, 255, 255, 0.2);
            display: flex; flex-direction: column; justify-content: center; align-items: center;
            padding: 40px; box-shadow: 0 0 30px rgba(0,0,0,0.8);
        }
        .logo { width: 240px; margin-bottom: 30px; filter: drop-shadow(0 0 10px rgba(0,0,0,0.8)); }
        .form-group { width: 100%; margin-bottom: 15px; }
        .input-field {
            width: 100%; padding: 14px; font-family: 'Cinzel', serif; font-size: 15px; color: #000;
            background: rgba(255, 255, 255, 0.9); border: none; outline: none; transition: 0.3s;
        }
        .input-field:focus { background: #fff; box-shadow: 0 0 15px #cfa35e; }
        .btn-register {
            width: 100%; padding: 14px; margin-top: 15px;
            background-color: #b30000; color: white; font-family: 'Cinzel', serif; font-size: 16px; font-weight: bold;
            border: none; cursor: pointer; transition: 0.3s; letter-spacing: 2px;
        }
        .btn-register:hover { background-color: #ff0000; box-shadow: 0 0 20px #ff0000; }
        .footer-text { margin-top: 25px; color: white; font-family: 'Lato', sans-serif; font-size: 14px; }
        .footer-text a { color: #ff3b3b; text-decoration: none; font-weight: bold; }
        .footer-text a:hover { text-decoration: underline; }
    </style>
</head>
<body>
    <div class="container">
        <div class="glass-panel">
            <img src="asset/logo.png" alt="God of War" class="logo">
            <form style="width: 100%;" method="POST">
                <div class="form-group">
                    <input type="text" name="nama" placeholder="NAMA LENGKAP" class="input-field" required>
                </div>
                <div class="form-group">
                    <input type="email" name="email" placeholder="EMAIL" class="input-field" required>
                </div>
                <div class="form-group">
                    <input type="password" name="password" placeholder="PASSWORD" class="input-field" required>
                </div>
                <div class="form-group">
                    <input type="password" name="confirm_password" placeholder="KONFIRMASI PASSWORD" class="input-field" required>
                </div>
                <button type="submit" name="register" class="btn-register">REGISTER</button>
            </form>
            <p class="footer-text">
                Sudah punya akun? <a href="index.php">Login di sini</a>
            </p>
        </div>
    </div>
</body>
</html>
