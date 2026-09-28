<?php
session_start();
include '../koneksi.php';

if (!isset($_SESSION['status']) || $_SESSION['role'] != 'admin') {
    header("Location: ../index.php"); 
    exit;
}

$edit_mode = false;
$id_edit = ""; $nama_edit = ""; $deskripsi_edit = ""; $img_edit = "";
$pos_top_edit = "50%"; $pos_left_edit = "50%";

if (isset($_GET['edit'])) {
    $edit_mode = true;
    $id = mysqli_real_escape_string($conn, $_GET['edit']);
    $d = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM realms WHERE id='$id'"));
    if ($d) {
        $id_edit = $d['id'];
        $nama_edit = $d['nama_realm'];
        $deskripsi_edit = $d['deskripsi'];
        $img_edit = $d['gambar'];
        $pos_top_edit = $d['posisi_top'];
        $pos_left_edit = $d['posisi_left'];
    }
}

if (isset($_POST['simpan'])) {
    $nama = mysqli_real_escape_string($conn, htmlspecialchars($_POST['nama_realm']));
    $deskripsi = mysqli_real_escape_string($conn, htmlspecialchars($_POST['deskripsi']));
    $pos_top = mysqli_real_escape_string($conn, htmlspecialchars($_POST['posisi_top']));
    $pos_left = mysqli_real_escape_string($conn, htmlspecialchars($_POST['posisi_left']));

    if (!empty($_POST['id_edit'])) {
        $id = mysqli_real_escape_string($conn, $_POST['id_edit']);
        $query = "UPDATE realms SET 
                  nama_realm='$nama', deskripsi='$deskripsi', 
                  posisi_top='$pos_top', posisi_left='$pos_left' 
                  WHERE id='$id'";
        mysqli_query($conn, $query);
        
        if (!empty($_FILES['gambar']['name'])) {
            $gambar = $_FILES['gambar']['name'];
            move_uploaded_file($_FILES['gambar']['tmp_name'], "../asset/" . $gambar);
            mysqli_query($conn, "UPDATE realms SET gambar='$gambar' WHERE id='$id'");
        }
    } else {
        $gambar = $_FILES['gambar']['name'] ?? 'default.jpg';
        if (!empty($_FILES['gambar']['name'])) {
            move_uploaded_file($_FILES['gambar']['tmp_name'], "../asset/" . $gambar);
        }
        $query = "INSERT INTO realms (nama_realm, deskripsi, gambar, posisi_top, posisi_left) 
                  VALUES ('$nama', '$deskripsi', '$gambar', '$pos_top', '$pos_left')";
        mysqli_query($conn, $query);
    }
    header("Location: manage_realms.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <title><?= $edit_mode ? 'Edit Realm' : 'Add Realm'; ?> - Admin</title>
    <link rel="stylesheet" href="admin_style.css">
</head>
<body>
    <div class="sidebar">
        <div class="brand"><img src="../asset/logo.png"><h2>ADMIN PANEL</h2></div>
        <div class="menu">
            <a href="admin_dashboard.php" class="menu-link">DASHBOARD</a>
            <a href="manage_series.php" class="menu-link">MANAGE SERIES</a>
            <a href="manage_characters.php" class="menu-link">MANAGE CHARACTERS</a>
            <a href="manage_story.php" class="menu-link">MANAGE STORY</a>
            <a href="manage_realms.php" class="menu-link active">MANAGE REALMS</a>
            <a href="manage_weapons.php" class="menu-link">MANAGE WEAPONS</a>
            <a href="../logout.php" class="menu-link logout">LOGOUT</a>
        </div>
    </div>
    <div class="content">
        <div class="page-header">
            <h1 class="page-title"><?= $edit_mode ? 'EDIT REALM' : 'ADD NEW REALM'; ?></h1>
        </div>
        <div class="form-card" style="background:#fff; padding:30px; border-radius:8px; max-width:600px; box-shadow:0 2px 10px rgba(0,0,0,0.1);">
            <form method="POST" enctype="multipart/form-data">
                <input type="hidden" name="id_edit" value="<?= $id_edit; ?>">

                <div class="form-group" style="margin-bottom:15px;">
                    <label style="display:block; font-weight:bold; margin-bottom:5px;">REALM NAME</label>
                    <input type="text" name="nama_realm" value="<?= $nama_edit; ?>" required style="width:100%; padding:10px; border:1px solid #ccc; border-radius:4px;">
                </div>

                <div class="form-group" style="margin-bottom:15px;">
                    <label style="display:block; font-weight:bold; margin-bottom:5px;">DESCRIPTION</label>
                    <textarea name="deskripsi" rows="4" required style="width:100%; padding:10px; border:1px solid #ccc; border-radius:4px;"><?= $deskripsi_edit; ?></textarea>
                </div>

                <div style="display:flex; gap:15px; margin-bottom:15px;">
                    <div style="flex:1;">
                        <label style="display:block; font-weight:bold; margin-bottom:5px;">POSITION TOP (e.g. 50%)</label>
                        <input type="text" name="posisi_top" value="<?= $pos_top_edit; ?>" required style="width:100%; padding:10px; border:1px solid #ccc; border-radius:4px;">
                    </div>
                    <div style="flex:1;">
                        <label style="display:block; font-weight:bold; margin-bottom:5px;">POSITION LEFT (e.g. 50%)</label>
                        <input type="text" name="posisi_left" value="<?= $pos_left_edit; ?>" required style="width:100%; padding:10px; border:1px solid #ccc; border-radius:4px;">
                    </div>
                </div>

                <div class="form-group" style="margin-bottom:20px;">
                    <label style="display:block; font-weight:bold; margin-bottom:5px;">IMAGE</label>
                    <?php if(!empty($img_edit)): ?>
                        <div style="margin-bottom:10px;"><img src="../asset/<?= $img_edit; ?>" width="80" style="border:1px solid #ccc;"></div>
                    <?php endif; ?>
                    <input type="file" name="gambar" <?= $edit_mode ? '' : 'required'; ?>>
                </div>

                <div style="display:flex; gap:10px;">
                    <button type="submit" name="simpan" class="btn-add" style="border:none; cursor:pointer;">SAVE REALM</button>
                    <a href="manage_realms.php" class="btn-action" style="padding:10px 20px; background:#666; color:white; text-decoration:none; border-radius:4px;">CANCEL</a>
                </div>
            </form>
        </div>
    </div>
</body>
</html>
