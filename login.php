<?php
session_start();
include 'includes/db.php';

// Cek apakah form login sudah disubmit
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $conn->real_escape_string($_POST['email']);  // Sanitasi input email
    $password = $_POST['password'];  // Ambil password

    // Query untuk mencari pengguna berdasarkan email
    $sql = "SELECT * FROM user WHERE email = ?";
    $stmt = $conn->prepare($sql);  // Siapkan query
    if ($stmt === false) {
        die('Statement prepare failed: ' . $conn->error);  // Menampilkan error jika prepare gagal
    }
    
    $stmt->bind_param('s', $email);  // Bind parameter untuk email
    $stmt->execute();  // Eksekusi statement
    $result = $stmt->get_result();  // Ambil hasil query

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();  // Ambil data pengguna
        if (password_verify($password, $user['password'])) {  // Verifikasi password
            // Simpan informasi pengguna ke session
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_name'] = $user['nama'];
            header('Location: daftar_obat.php');  // Redirect ke halaman utama setelah login sukses
            exit();
        } else {
            $error = "Password salah.";  // Jika password tidak cocok
        }
    } else {
        $error = "Email tidak ditemukan.";  // Jika email tidak terdaftar
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <style>
        body {
            background-color: #2196F3; /* Warna biru */
            font-family: 'Poppins', Arial, sans-serif;
            margin: 0;
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
            max-width: 400px;
            padding: 30px;
            background-color: #ffffff;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            border-radius: 12px;
            opacity: 0;
            animation: fadeIn 3s ease-in-out forwards; /* Slowmotion fade-in */
        }

        @keyframes fadeIn {
            0% { opacity: 0; }
            100% { opacity: 1; }
        }

        h2 {
            text-align: center;
            margin-bottom: 20px;
            color: #0d47a1; /* Warna biru gelap */
            font-weight: bold;
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

        .form-label {
            font-weight: bold;
            color: #495057;
        }

        .form-control {
            border-radius: 8px;
            border: 1px solid #ddd;
            padding: 12px;
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

        .btn-secondary {
            background-color: #6c757d;
            border: none;
            border-radius: 8px;
            padding: 12px;
            font-size: 16px;
            text-align: center;
            transition: background-color 0.3s ease, transform 0.3s ease;
        }

        .btn-secondary:hover {
            background-color: #5a6268;
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
    <h2>Login</h2>

    <?php if (isset($error)): ?>
        <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <form method="POST" action="">
        <div class="mb-3">
            <label for="email" class="form-label">Email</label>
            <input type="email" id="email" name="email" class="form-control" required>
        </div>
        <div class="mb-3">
            <label for="password" class="form-label">Password</label>
            <input type="password" id="password" name="password" class="form-control" required>
        </div>
        <button type="submit" class="btn btn-primary w-100">Login</button>
        <a href="register.php" class="btn btn-secondary w-100 mt-3">Belum punya akun? Daftar</a>
    </form>
</div>

</body>
</html>
