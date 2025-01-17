<?php
session_start();
include 'includes/db.php';

// Redirect jika user tidak terautentikasi
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

// Proses transaksi
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id_obat = (int) $_POST['id_obat'];
    $jumlah = (int) $_POST['jumlah'];
    $total_harga = 0;
    $id_user = $_SESSION['user_id'];  // Ambil ID user dari session

    // Ambil data obat berdasarkan ID
    $sql = "SELECT * FROM obat WHERE id = $id_obat";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $obat = $result->fetch_assoc();
        if ($jumlah <= $obat['stok']) {
            $total_harga = $jumlah * $obat['harga'];
            $stok_baru = $obat['stok'] - $jumlah;

            // Update stok obat
            $update_stok_sql = "UPDATE obat SET stok = $stok_baru WHERE id = $id_obat";
            $conn->query($update_stok_sql);

            // Simpan transaksi
            $insert_transaksi_sql = "INSERT INTO transaksi (id_obat, id_user, jumlah, total_harga, tanggal) 
                                      VALUES ($id_obat, $id_user, $jumlah, $total_harga, NOW())";
            if ($conn->query($insert_transaksi_sql) === TRUE) {
                $success = "Transaksi berhasil dicatat. ID Transaksi: " . $conn->insert_id;  // Menampilkan ID transaksi yang baru dibuat
            } else {
                $error = "Terjadi kesalahan saat mencatat transaksi: " . $conn->error;
            }
        } else {
            $error = "Jumlah yang diminta melebihi stok.";
        }
    } else {
        $error = "Obat tidak ditemukan.";
    }
}

// Ambil daftar obat
$obat_sql = "SELECT * FROM obat ORDER BY nama_obat ASC";
$obat_result = $conn->query($obat_sql);

$title = "Transaksi";
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($title) ?></title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <style>
        body {
            background-color: #007bff; /* Latar belakang biru */
            font-family: 'Poppins', Arial, sans-serif;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            animation: backgroundFade 5s ease-in-out infinite; /* Slowmotion background fade */
        }

        @keyframes backgroundFade {
            0% { background-color: #007bff; }
            50% { background-color: #0056b3; }
            100% { background-color: #007bff; }
        }

        .container {
            background: rgba(255, 255, 255, 0.9); /* Latar belakang putih transparan */
            padding: 40px;
            border-radius: 15px;
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.2);
            max-width: 600px;
            width: 100%;
            opacity: 0;
            animation: fadeIn 3s ease-in-out forwards; /* Slowmotion fade in */
        }

        @keyframes fadeIn {
            0% { opacity: 0; }
            100% { opacity: 1; }
        }

        h2 {
            font-weight: bold;
            color: #333;
            text-align: center;
            margin-bottom: 20px;
            animation: slideIn 2s ease-in-out; /* Animasi masuk untuk judul */
        }

        @keyframes slideIn {
            0% { transform: translateY(-50px); opacity: 0; }
            100% { transform: translateY(0); opacity: 1; }
        }

        .btn-primary {
            background-color: #007bff;
            border: none;
            transition: background-color 0.3s ease-in-out, transform 0.2s ease-in-out;
            animation: buttonPop 1s ease-in-out infinite alternate; /* Slowmotion animation untuk tombol */
        }

        @keyframes buttonPop {
            0% { transform: scale(1); }
            100% { transform: scale(1.05); }
        }

        .btn-primary:hover {
            background-color: #0056b3;
        }

        .btn-secondary {
            background-color: #6c757d;
            border: none;
            transition: background-color 0.3s ease-in-out, transform 0.2s;
        }

        .btn-secondary:hover {
            background-color: #5a6268;
            transform: scale(1.05);
        }

        .form-control {
            border-radius: 10px;
            padding: 10px;
            font-size: 16px;
            animation: formInputFade 2s ease-in-out; /* Animasi fade untuk input */
        }

        @keyframes formInputFade {
            0% { opacity: 0; }
            100% { opacity: 1; }
        }

        .alert {
            margin-top: 15px;
            padding: 15px;
            border-radius: 8px;
            font-weight: bold;
            animation: fadeInAlert 2s ease-in-out; /* Animasi alert */
        }

        @keyframes fadeInAlert {
            0% { opacity: 0; }
            100% { opacity: 1; }
        }
    </style>
</head>
<body>
<div class="container">
    <h2>Transaksi Pembelian</h2>
    
    <?php if (isset($success)): ?>
        <div class="alert alert-success"><?= htmlspecialchars($success) ?></div>
    <?php endif; ?>
    <?php if (isset($error)): ?>
        <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>
    
    <form action="transaksi.php" method="POST">
        <div class="mb-3">
            <label for="id_obat" class="form-label">Pilih Obat</label>
            <select id="id_obat" name="id_obat" class="form-control" required>
                <option value="">-- Pilih Obat --</option>
                <?php if ($obat_result->num_rows > 0): ?>
                    <?php while ($obat = $obat_result->fetch_assoc()): ?>
                        <option value="<?= htmlspecialchars($obat['id']) ?>">
                            <?= htmlspecialchars($obat['nama_obat']) ?> - Stok: <?= htmlspecialchars($obat['stok']) ?>
                        </option>
                    <?php endwhile; ?>
                <?php else: ?>
                    <option value="">Tidak ada obat tersedia</option>
                <?php endif; ?>
            </select>
        </div>
        <div class="mb-3">
            <label for="jumlah" class="form-label">Jumlah</label>
            <input type="number" id="jumlah" name="jumlah" class="form-control" required>
        </div>
        <button type="submit" class="btn btn-primary w-100 mb-2">Proses Transaksi</button>
        <a href="r.php" class="btn btn-secondary w-100">Riwayat Transaksi</a>
    </form>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
