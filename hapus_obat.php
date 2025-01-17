<?php
session_start();
include 'includes/db.php'; // Pastikan koneksi database sudah benar

// Redirect jika user tidak terautentikasi
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

// Memastikan bahwa parameter 'id' ada di URL dan valid
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $id_obat = $_GET['id'];

    // Query untuk menghapus obat berdasarkan ID
    $sql_delete = "DELETE FROM obat WHERE id = ?";

    if ($stmt = $conn->prepare($sql_delete)) {
        // Mengikat parameter dan mengeksekusi query
        $stmt->bind_param("i", $id_obat);  // "i" berarti integer
        if ($stmt->execute()) {
            // Redirect ke halaman daftar obat setelah penghapusan
            header('Location: daftar_obat.php?message=Obat+berhasil+dihapus');
            exit();
        } else {
            $error = "Terjadi kesalahan saat menghapus obat: " . $stmt->error;
        }
    } else {
        $error = "Terjadi kesalahan dalam menyiapkan query: " . $conn->error;
    }
} else {
    $error = "ID obat tidak valid atau tidak ditemukan.";
}

$title = "Hapus Obat";
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($title) ?></title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body>
<div class="container mt-5">
    <h2>Hapus Obat</h2>
    
    <?php if (isset($error)): ?>
        <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <a href="daftar_obat.php" class="btn btn-secondary">Kembali ke Daftar Obat</a>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
