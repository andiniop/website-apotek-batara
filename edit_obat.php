<?php
session_start();
include 'includes/db.php'; // Pastikan koneksi database sudah benar

// Redirect jika user tidak terautentikasi
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

// Memastikan parameter 'id' ada di URL dan valid
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $id_obat = $_GET['id'];

    // Ambil data obat berdasarkan ID
    $sql_select = "SELECT * FROM obat WHERE id = ?";
    if ($stmt = $conn->prepare($sql_select)) {
        $stmt->bind_param("i", $id_obat);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            // Ambil data obat
            $row = $result->fetch_assoc();
            $nama_obat = $row['nama_obat'];
            $stok = $row['stok'];
            $harga = $row['harga'];
        } else {
            $error = "Obat dengan ID tersebut tidak ditemukan.";
        }
    } else {
        $error = "Terjadi kesalahan saat mengambil data obat: " . $conn->error;
    }

    // Proses jika form di-submit
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $nama_obat = $conn->real_escape_string($_POST['nama_obat']);
        $stok = (int) $_POST['stok'];
        $harga = (float) $_POST['harga'];

        // Query untuk update data obat
        $sql_update = "UPDATE obat SET nama_obat = ?, stok = ?, harga = ? WHERE id = ?";
        if ($stmt = $conn->prepare($sql_update)) {
            $stmt->bind_param("sidi", $nama_obat, $stok, $harga, $id_obat);  // "s" untuk string, "i" untuk integer, "d" untuk decimal
            if ($stmt->execute()) {
                // Redirect ke halaman daftar obat setelah update
                header('Location: daftar_obat.php');
                exit();
            } else {
                $error = "Terjadi kesalahan saat memperbarui data obat: " . $stmt->error;
            }
        } else {
            $error = "Terjadi kesalahan dalam menyiapkan query: " . $conn->error;
        }
    }
} else {
    $error = "ID obat tidak valid atau tidak ditemukan.";
}

$title = "Edit Obat";
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
            background-color: #f4f4f9;
            font-family: 'Arial', sans-serif;
        }

        /* Slowmotion effect */
        .container {
            opacity: 0;
            transform: translateY(50px);
            animation: fadeInSlow 1s ease-out forwards;
        }

        @keyframes fadeInSlow {
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Warna Biru yang Konsisten */
        .btn-primary, .btn-secondary {
            background-color: #0d47a1; /* Biru gelap */
            border: none;
            border-radius: 8px;
            transition: all 0.3s ease;
        }

        .btn-primary:hover, .btn-secondary:hover {
            transform: scale(1.05);
            background-color: #1565c0; /* Biru lebih terang saat hover */
        }

        .form-control {
            transition: all 0.3s ease;
            border-radius: 25px;
        }

        .form-control:focus {
            border-color: #0d47a1;
            box-shadow: 0 0 5px rgba(0, 123, 255, 0.5);
        }

        .alert {
            margin-top: 15px;
            padding: 15px;
            border-radius: 8px;
            font-weight: bold;
            background-color: #f8d7da;
            color: #721c24;
        }

        .mb-3 {
            margin-bottom: 20px;
        }

        .w-100 {
            width: 100%;
        }
    </style>
</head>
<body>
<div class="container mt-5">
    <h2 class="text-center mb-4">Edit Obat</h2>

    <?php if (isset($error)): ?>
        <div class="alert"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <!-- Form untuk mengedit obat -->
    <form action="edit_obat.php?id=<?= $id_obat ?>" method="POST">
        <div class="mb-3">
            <label for="nama_obat" class="form-label">Nama Obat</label>
            <input type="text" id="nama_obat" name="nama_obat" class="form-control" value="<?= htmlspecialchars($nama_obat) ?>" required>
        </div>
        <div class="mb-3">
            <label for="stok" class="form-label">Stok</label>
            <input type="number" id="stok" name="stok" class="form-control" value="<?= $stok ?>" required>
        </div>
        <div class="mb-3">
            <label for="harga" class="form-label">Harga</label>
            <input type="number" id="harga" name="harga" class="form-control" value="<?= $harga ?>" required step="0.01">
        </div>
        <button type="submit" class="btn btn-primary w-100 mb-2">Simpan Perubahan</button>
    </form>

    <a href="daftar_obat.php" class="btn btn-secondary w-100">Kembali ke Daftar Obat</a>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
