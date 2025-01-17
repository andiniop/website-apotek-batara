<?php
session_start();
include 'includes/db.php';  // Menghubungkan ke database

// Cek apakah form di-submit
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nama = $conn->real_escape_string($_POST['nama']);
    $email = $conn->real_escape_string($_POST['email']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    // Validasi: apakah password dan konfirmasi password cocok
    if ($password !== $confirm_password) {
        $error = "Password dan konfirmasi password tidak cocok.";
    } else {
        // Cek apakah email sudah terdaftar
        $sql = "SELECT * FROM user WHERE email = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('s', $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $error = "Email sudah terdaftar.";
        } else {
            // Enkripsi password
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);

            // Simpan data user ke database
            $sql = "INSERT INTO user (nama, email, password) VALUES (?, ?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param('sss', $nama, $email, $hashed_password);

            if ($stmt->execute()) {
                $_SESSION['user_id'] = $conn->insert_id;  // ID user yang baru dibuat
                $_SESSION['user_name'] = $nama;
                header('Location: login.php');  // Redirect ke halaman login setelah sukses
                exit();
            } else {
                $error = "Terjadi kesalahan saat registrasi.";
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
    <title>Daftar Akun</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #2196F3; /* Warna biru */
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
            0% { background-color: #2196F3; }
            50% { background-color: #1976D2; }
            100% { background-color: #2196F3; }
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
            color: #0d47a1; /* Warna biru gelap */
            text-align: center;
            margin-bottom: 20px;
            animation: slideIn 2s ease-in-out; /* Animasi masuk untuk judul */
        }

        @keyframes slideIn {
            0% { transform: translateY(-50px); opacity: 0; }
            100% { transform: translateY(0); opacity: 1; }
        }

        .btn-primary {
            background-color: #0d47a1; /* Warna biru gelap */
            border: none;
            transition: background-color 0.3s ease-in-out, transform 0.2s ease-in-out;
            animation: buttonPop 1s ease-in-out infinite alternate; /* Slowmotion animation untuk tombol */
        }

        @keyframes buttonPop {
            0% { transform: scale(1); }
            100% { transform: scale(1.05); }
        }

        .btn-primary:hover {
            background-color: #1565c0;
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
    <h2>Daftar Akun Baru</h2>

    <!-- Menampilkan pesan error jika ada -->
    <?php if (isset($error)): ?>
        <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <!-- Form untuk pendaftaran akun -->
    <form method="POST" action="">
        <div class="mb-3">
            <label for="nama" class="form-label">Nama</label>
            <input type="text" id="nama" name="nama" class="form-control" required>
        </div>

        <div class="mb-3">
            <label for="email" class="form-label">Email</label>
            <input type="email" id="email" name="email" class="form-control" required>
        </div>

        <div class="mb-3">
            <label for="password" class="form-label">Password</label>
            <input type="password" id="password" name="password" class="form-control" required>
        </div>

        <div class="mb-3">
            <label for="confirm_password" class="form-label">Konfirmasi Password</label>
            <input type="password" id="confirm_password" name="confirm_password" class="form-control" required>
        </div>

        <button type="submit" class="btn btn-primary w-100 mb-2">Daftar</button>
        <a href="login.php" class="btn btn-secondary w-100">Sudah punya akun? Login</a>
    </form>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
