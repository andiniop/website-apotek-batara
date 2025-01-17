<?php
session_start();
include 'includes/db.php';

// Jika pengguna belum login, arahkan ke halaman login
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

$error = $success = "";

// Jika formulir disubmit
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nama_produk = $_POST['nama_produk'];
    $harga = $_POST['harga'];
    $stok = $_POST['stok'];
    $deskripsi = $_POST['deskripsi'];

    // Validasi input
    if (empty($nama_produk) || empty($harga) || empty($stok)) {
        $error = "Semua field harus diisi!";
    } elseif (!is_numeric($harga) || !is_numeric($stok)) {
        $error = "Harga dan Stok harus berupa angka.";
    } else {
        // Query untuk menambah produk ke database
        $sql = "INSERT INTO produk (nama_produk, harga, stok, deskripsi) VALUES (?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('sdss', $nama_produk, $harga, $stok, $deskripsi);

        // Eksekusi query
        if ($stmt->execute()) {
            $success = "Produk berhasil ditambahkan!";
        } else {
            $error = "Terjadi kesalahan saat menambahkan produk.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Produk</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <style>
        body {
            background-color: #f4f6f9;
        }
        .container {
            max-width: 600px;
            margin-top: 50px;
        }
    </style>
</head>
<body>

<div class="container">
    <h2 class="text-center mb-4">Tambah Produk</h2>

    <?php if ($error): ?>
        <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <?php if ($success): ?>
        <div class="alert alert-success"><?= htmlspecialchars($success) ?></div>
    <?php endif; ?>

    <form method="POST" action="add_produk.php">
        <div class="mb-3">
            <label for="nama_produk" class="form-label">Nama Produk</label>
            <input type="text" id="nama_produk" name="nama_produk" class="form-control" required>
        </div>
        <div class="mb-3">
            <label for="harga" class="form-label">Harga Produk</label>
            <input type="number" id="harga" name="harga" class="form-control" step="0.01" required>
        </div>
        <div class="mb-3">
            <label for="stok" class="form-label">Jumlah Stok</label>
            <input type="number" id="stok" name="stok" class="form-control" min="1" required>
        </div>
        <div class="mb-3">
            <label for="deskripsi" class="form-label">Deskripsi Produk (Opsional)</label>
            <textarea id="deskripsi" name="deskripsi" class="form-control"></textarea>
        </div>
        <button type="submit" class="btn btn-primary w-100">Tambah Produk</button>
    </form>
</div>

</body>
</html>
