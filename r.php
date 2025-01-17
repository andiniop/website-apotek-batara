<?php
session_start();
include 'includes/db.php';

// Redirect jika user tidak terautentikasi
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

$id_user = $_SESSION['user_id'];

// Ambil riwayat transaksi
$sql = "SELECT t.*, o.nama_obat FROM transaksi t JOIN obat o ON t.id_obat = o.id WHERE t.id_user = $id_user ORDER BY t.tanggal DESC";
$result = $conn->query($sql);

$title = "Riwayat Transaksi";
include 'includes/header.php';
?>

<div class="container mt-5">
    <h2 class="text-center mb-4">Riwayat Transaksi</h2>
    
    <table class="table table-bordered table-hover">
        <thead class="table-dark">
            <tr>
                <th>ID Transaksi</th>
                <th>Nama Obat</th>
                <th>Jumlah</th>
                <th>Total Harga</th>
                <th>Tanggal</th>
            </tr>
        </thead>
        <tbody>
            <?php if ($result->num_rows > 0): ?>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?= htmlspecialchars($row['id']) ?></td>
                        <td><?= htmlspecialchars($row['nama_obat']) ?></td>
                        <td><?= htmlspecialchars($row['jumlah']) ?></td>
                        <td>Rp <?= number_format($row['total_harga'], 0, ',', '.') ?></td>
                        <td><?= htmlspecialchars($row['tanggal']) ?></td>
                    </tr>
                <?php endwhile; ?>
            <?php else: ?>
                <tr>
                    <td colspan="5" class="text-center">Tidak ada transaksi.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
    
    <div class="text-center">
        <a href="transaksi.php" class="btn btn-custom w-100 mb-2">Buat Transaksi Baru</a>
        <a href="dashboard.php" class="btn btn-secondary w-100">Kembali ke Dashboard</a>
    </div>
</div>

<?php include 'includes/footer.php'; ?>

<!-- CSS tambahan untuk tampilan -->
<style>
    body {
        background-color: #2196F3; /* Warna biru */
        font-family: 'Poppins', Arial, sans-serif;
        margin: 0;
        padding: 0;
        color: #333;
        animation: backgroundFade 5s ease-in-out infinite; /* Slowmotion background fade */
    }

    @keyframes backgroundFade {
        0% { background-color: #2196F3; }
        50% { background-color: #1976D2; }
        100% { background-color: #2196F3; }
    }

    .container {
        background-color: rgba(0, 123, 255, 0.8); /* Latar biru transparan */
        padding: 40px;
        border-radius: 12px;
        box-shadow: 0 10px 15px rgba(0, 0, 0, 0.1);
        max-width: 1100px;
        width: 100%;
        opacity: 0;
        animation: fadeIn 3s ease-in-out forwards; /* Slowmotion fade-in */
    }

    @keyframes fadeIn {
        0% { opacity: 0; }
        100% { opacity: 1; }
    }

    h2 {
        font-weight: bold;
        color: white;
        font-size: 32px;
        text-transform: uppercase;
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

    .table {
        margin-top: 20px;
        border-collapse: collapse;
        width: 100%;
        background-color: white;
    }

    .table th, .table td {
        padding: 12px;
        text-align: center;
        font-size: 16px;
    }

    .table-dark {
        background-color: #007bff; /* Warna biru untuk header tabel */
        color: white;
    }

    .table-bordered td, .table-bordered th {
        border: 1px solid #007bff; /* Border biru */
    }

    .table-hover tbody tr:hover {
        background-color: #cce5ff; /* Efek hover dengan warna biru muda */
        cursor: pointer;
    }

    .btn-custom {
        background-color: #007bff; /* Biru untuk tombol */
        border: none;
        font-weight: bold;
        padding: 12px 20px;
        border-radius: 8px;
        font-size: 16px;
        transition: all 0.3s ease; /* Slow-motion hover */
    }

    .btn-custom:hover {
        background-color: #0056b3; /* Biru lebih gelap saat hover */
        transform: scale(1.05); /* Efek zoom sedikit saat hover */
    }

    .btn-secondary {
        background-color: #6c757d;
        border: none;
        font-weight: bold;
        padding: 12px 20px;
        border-radius: 8px;
        font-size: 16px;
        transition: all 0.3s ease; /* Slow-motion hover */
    }

    .btn-secondary:hover {
        background-color: #5a6268; /* Warna gelap saat hover */
        transform: scale(1.05); /* Efek zoom sedikit saat hover */
    }
</style>
