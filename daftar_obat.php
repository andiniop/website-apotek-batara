<?php
session_start();
include 'includes/db.php';

// Redirect jika user tidak terautentikasi
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

// Menangani pencarian obat
$search_query = "";
if (isset($_GET['search'])) {
    $search_query = $conn->real_escape_string($_GET['search']);
}

// Ambil data obat dari database dengan pencarian (jika ada)
$sql = "SELECT * FROM obat WHERE nama_obat LIKE '%$search_query%'";
$result = $conn->query($sql);

// Menangani penghapusan obat jika ada (misalnya melalui GET request)
if (isset($_GET['delete_id'])) {
    $delete_id = (int) $_GET['delete_id'];
    $sql_delete = "DELETE FROM obat WHERE id = $delete_id";
    if ($conn->query($sql_delete) === TRUE) {
        header('Location: hapus_obat.php'); // Redirect ke halaman index setelah penghapusan
        exit();
    } else {
        $error = "Terjadi kesalahan saat menghapus obat: " . $conn->error;
    }
}

$title = "Daftar Obat";
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
            background-color: #2196F3; /* Warna biru */
            font-family: 'Poppins', Arial, sans-serif;
            margin: 0;
            padding: 0;
            animation: backgroundFade 5s ease-in-out infinite; /* Slowmotion background fade */
        }

        @keyframes backgroundFade {
            0% { background-color: #2196F3; }
            50% { background-color: #1976D2; }
            100% { background-color: #2196F3; }
        }

        .container {
            margin-top: 50px;
            background-color: #ffffff;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            opacity: 0;
            animation: fadeIn 3s ease-in-out forwards; /* Slowmotion fade-in */
        }

        @keyframes fadeIn {
            0% { opacity: 0; }
            100% { opacity: 1; }
        }

        h2 {
            font-weight: bold;
            color: #0d47a1; /* Warna biru gelap */
            text-align: center;
            margin-bottom: 20px;
            opacity: 0;
            animation: fadeInFromTop 1.5s ease-out forwards;
            animation-delay: 0.3s;
        }

        @keyframes fadeInFromTop {
            0% {
                opacity: 0;
                transform: translateY(-20px);
            }
            100% {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .table th, .table td {
            text-align: center;
        }

        .btn {
            margin-right: 5px;
        }

        .btn-danger {
            background-color: #dc3545;
        }

        .btn-danger:hover {
            background-color: #c82333;
        }

        .form-control {
            border-radius: 25px;
            font-size: 14px;
            animation: fadeInFromTop 1.5s ease-out forwards;
            animation-delay: 0.5s;
            opacity: 0;
        }

        .btn-primary {
            background-color: #0d47a1; /* Warna biru */
            border: none;
            border-radius: 8px;
            padding: 12px;
            font-size: 16px;
            transition: background-color 0.3s ease, transform 0.3s ease;
        }

        .btn-primary:hover {
            background-color: #1565c0;
            transform: scale(1.05);
        }

        .btn-warning {
            background-color: #ffc107;
            border: none;
            border-radius: 8px;
            padding: 12px;
            font-size: 16px;
            transition: background-color 0.3s ease, transform 0.3s ease;
        }

        .btn-warning:hover {
            background-color: #e0a800;
            transform: scale(1.05);
        }

        .alert-danger {
            font-size: 14px;
            text-align: center;
            margin-bottom: 20px;
            border-radius: 8px;
            padding: 10px;
        }

        .text-center {
            text-align: center;
        }

        .mb-3 {
            margin-bottom: 20px;
        }

        /* Efek hover pada link */
        a {
            text-decoration: none;
        }

        a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>

<div class="container">
    <h2>Daftar Obat</h2>
    
    <?php if (isset($error)): ?>
        <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <!-- Form Pencarian -->
    <form action="daftar_obat.php" method="GET" class="mb-3">
        <div class="input-group">
            <input type="text" class="form-control" name="search" value="<?= htmlspecialchars($search_query) ?>" placeholder="Cari Obat..." />
            <button class="btn btn-outline-secondary" type="submit">Cari</button>
        </div>
    </form>
    
    <a href="tambah_obat.php" class="btn btn-primary mb-3">Tambah Obat Baru</a>

    <table class="table table-bordered table-responsive">
        <thead>
            <tr>
                <th>ID</th>
                <th>Nama Obat</th>
                <th>Stok</th>
                <th>Harga</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php if ($result->num_rows > 0): ?>
                <?php while($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?= $row['id'] ?></td>
                        <td><?= htmlspecialchars($row['nama_obat']) ?></td>
                        <td><?= $row['stok'] ?></td>
                        <td>Rp. <?= $row['harga'] == (int) $row['harga'] ? number_format($row['harga'], 0, ',', '.') : number_format($row['harga'], 2, ',', '.') ?></td>

                        <td>
                            <a href="edit_obat.php?id=<?= $row['id'] ?>" class="btn btn-warning btn-sm">Edit</a>
                            <a href="?delete_id=<?= $row['id'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Yakin ingin menghapus obat ini?');">Hapus</a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            <?php else: ?>
                <tr>
                    <td colspan="5" class="text-center">Tidak ada data obat yang ditemukan.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

