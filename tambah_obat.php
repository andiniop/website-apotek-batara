<?php
session_start();
include 'includes/db.php';

// Redirect jika user tidak terautentikasi
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nama_obat = $conn->real_escape_string($_POST['nama_obat']);
    $stok = (int) $_POST['stok'];
    $harga = (float) $_POST['harga'];

    // Insert ke database
    $sql = "INSERT INTO obat (nama_obat, stok, harga) VALUES ('$nama_obat', $stok, $harga)";
    if ($conn->query($sql) === TRUE) {
        header('Location: dashboard.php');
        exit();
    } else {
        $error = "Terjadi kesalahan: " . $conn->error;
    }
}

$title = "Tambah Obat";
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
            animation: backgroundFade 10s ease-in-out infinite; /* Slowmotion background */
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
            max-width: 500px;
            width: 100%;
            animation: fadeIn 3s ease-out; /* Slowmotion fade in effect */
        }

        @keyframes fadeIn {
            0% { opacity: 0; }
            100% { opacity: 1; }
        }

        h2 {
            font-weight: bold;
            color: #0056b3; /* Warna judul biru */
            text-align: center;
            margin-bottom: 20px;
            animation: slideIn 2s ease-in-out; /* Slowmotion slide in untuk judul */
        }

        @keyframes slideIn {
            0% { transform: translateY(-50px); opacity: 0; }
            100% { transform: translateY(0); opacity: 1; }
        }

        .btn-primary {
            background-color: #007bff;
            border: none;
            transition: background-color 0.3s ease-in-out, transform 0.3s ease-in-out;
            animation: buttonPop 1s ease-in-out infinite alternate; /* Slowmotion pop effect */
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
            transition: background-color 0.3s ease-in-out, transform 0.3s ease-in-out;
        }

        .btn-secondary:hover {
            background-color: #5a6268;
            transform: scale(1.05);
        }

        .form-control {
            border-radius: 10px;
            padding: 10px;
            font-size: 16px;
            animation: fadeInInput 2s ease-out; /* Slowmotion fade in untuk input */
        }

        @keyframes fadeInInput {
            0% { opacity: 0; }
            100% { opacity: 1; }
        }

        .alert {
            margin-top: 15px;
            padding: 15px;
            border-radius: 8px;
            font-weight: bold;
            animation: fadeInAlert 2s ease-out; /* Slowmotion fade in untuk alert */
        }

        @keyframes fadeInAlert {
            0% { opacity: 0; }
            100% { opacity: 1; }
        }
    </style>
</head>
<body>
<div class="container">
    <h2>Tambah Obat</h2>
    <?php if (isset($error)): ?>
        <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>
    <form action="tambah_obat.php" method="POST">
        <div class="mb-3">
            <label for="nama_obat" class="form-label">Nama Obat</label>
            <input type="text" id="nama_obat" name="nama_obat" class="form-control" required>
        </div>
        <div class="mb-3">
            <label for="stok" class="form-label">Stok</label>
            <input type="number" id="stok" name="stok" class="form-control" required>
        </div>
        <div class="mb-3">
            <label for="harga" class="form-label">Harga</label>
            <input type="number" id="harga" name="harga" class="form-control" required step="0.01">
        </div>
        <button type="submit" class="btn btn-primary w-100 mb-2">Tambah Obat</button>
        <a href="transaksi.php" class="btn btn-secondary w-100">Lanjut</a>
    </form>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
