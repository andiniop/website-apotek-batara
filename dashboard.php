<?php
session_start();
include 'includes/db.php';

// Redirect jika user tidak terautentikasi
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

$sql = "SELECT * FROM obat ORDER BY id DESC";
$result = $conn->query($sql);

$title = "Dashboard";
include 'includes/header.php';
?>
<div class="container mt-5">
    <h2 class="text-center mb-4">Selamat Datang <?= htmlspecialchars($_SESSION['user_name'] ?? 'Pengguna') ?>!</h2>
    <p class="text-center">Di Apotek <?= htmlspecialchars($_SESSION['Online'] ?? 'ONLINE') ?>!</p>
    <div class="text-center mb-4">
        <a href="tambah_obat.php" class="btn btn-custom me-2">Tambah Obat</a>
        <a href="transaksi.php" class="btn btn-logout">Transaksi</a>
    </div>
    <div class="table-responsive">
        <table class="table table-bordered table-hover">
            <thead class="table-dark">
                <tr>
                    <th>ID</th>
                    <th>Nama Obat</th>
                    <th>Stok</th>
                    <th>Harga (Rp)</th>
                </tr>
            </thead>
            <tbody>
    <?php if ($result->num_rows > 0): ?>
        <?php while ($row = $result->fetch_assoc()): ?>
            <tr>
                <td><?= htmlspecialchars($row['id']) ?></td>
                <td><?= htmlspecialchars($row['nama_obat']) ?></td>
                <td><?= htmlspecialchars($row['stok']) ?></td>
                <td>
    <?= htmlspecialchars(
        $row['harga'] == (int) $row['harga'] 
            ? number_format($row['harga'], 0, ',', '.') 
            : number_format($row['harga'], 2, ',', '.')
    ) ?>
</td>

            </tr>
        <?php endwhile; ?>
    <?php else: ?>
        <tr>
            <td colspan="4" class="text-center">Tidak ada data obat.</td>
        </tr>
    <?php endif; ?>
</tbody>
        </table>
    </div>
</div>

<!-- Style CSS -->
<style>
    body {
        background: #1E90FF no-repeat center center fixed; /* Background biru */
        background-size: cover;
        font-family: 'Poppins', Arial, sans-serif;
        margin: 0;
        padding: 0;
        color: #fff;
    }

    h2 {
        font-weight: bold;
        color: #003366; /* Biru lebih gelap untuk teks */
        text-transform: uppercase;
        font-size: 32px;
        margin-bottom: 20px;
    }

    .container {
        background: rgba(30, 144, 255, 0.8); /* Biru dengan transparansi */
        padding: 40px 50px;
        border-radius: 12px;
        box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
        color: #333;
        margin-top: 50px;
        max-width: 1100px;
        width: 100%;
    }

    .btn-custom, .btn-logout {
        font-size: 16px;
        border-radius: 8px;
        padding: 12px 20px;
        transition: all 0.5s ease; /* Slow-motion effect */
        font-weight: bold;
    }

    .btn-custom {
        background-color: #4682B4; /* Biru gelap */
        border: none;
    }

    .btn-custom:hover {
        background-color: #5A9BD5; /* Biru lebih terang saat hover */
        transform: scale(1.1); /* Efek sedikit lebih besar saat hover */
    }

    .btn-logout {
        background-color: #f44336; /* Merah untuk logout */
        border: none;
    }

    .btn-logout:hover {
        background-color: #d32f2f;
        transform: scale(1.1); /* Efek sedikit lebih besar saat hover */
    }

    .table-responsive {
        margin-top: 20px;
    }

    .table {
        border-collapse: collapse;
        width: 100%;
        background-color: #fff;
    }

    .table th, .table td {
        padding: 12px;
        text-align: center;
        font-size: 16px;
        vertical-align: middle;
        color: #333;
    }

    .table th {
        background-color: #4682B4; /* Header tabel biru */
        color: #fff;
        text-transform: uppercase;
        letter-spacing: 1px;
    }

    .table-bordered th, .table-bordered td {
        border: 1px solid #ddd;
    }

    .table-hover tbody tr:hover {
        background-color: #A9C9E3; /* Warna biru terang saat hover */
        cursor: pointer;
    }

    .text-center {
        text-align: center;
    }

    /* Responsive Design */
    @media (max-width: 768px) {
        .container {
            padding: 20px;
            margin-top: 30px;
        }

        h2 {
            font-size: 24px;
        }

        .table th, .table td {
            font-size: 14px;
            padding: 10px;
        }

        .btn-custom, .btn-logout {
            padding: 10px 15px;
            font-size: 14px;
        }
    }
</style>

<?php include 'includes/footer.php'; ?>
