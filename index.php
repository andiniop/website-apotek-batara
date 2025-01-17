<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Halaman Utama</title>
  <style>
    body {
      font-family: Arial, sans-serif;
      background-color: #e3f2fd; /* Warna latar belakang biru muda */
      height: 100vh;
      margin: 0;
      display: flex;
      justify-content: center;
      align-items: center;
      flex-direction: column;
      position: relative;
      overflow: hidden;
    }

    .overlay {
      position: absolute;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      background-color: rgba(0, 0, 0, 0.1); /* Overlay transparan ringan */
      z-index: 1;
    }

    .welcome-container, .options {
      position: relative;
      z-index: 2; /* Konten di atas overlay */
      text-align: center;
      opacity: 0;
      transform: translateY(30px); /* Mulai dengan posisi sedikit lebih rendah */
      animation: fadeInFromTop 2s ease-out forwards; /* Animasi masuk dengan durasi lebih lama */
    }

    /* Animasi Teks */
    .welcome-text {
      font-size: 3rem;
      color: #0d47a1; /* Warna biru gelap */
      opacity: 0;
      transform: translateY(-20px); /* Posisi awal sedikit di atas */
      animation: fadeInFromTop 2s ease-out forwards;
      text-shadow: 3px 3px 6px rgba(0, 0, 0, 0.3); /* Bayangan lembut pada teks */
      animation-delay: 0.5s;
    }

    .instructions {
      font-size: 1.4rem;
      color: #0d47a1; /* Warna teks biru */
      opacity: 0;
      transform: translateY(-20px); /* Posisi awal sedikit di atas */
      animation: fadeInFromTop 2s ease-out forwards;
      animation-delay: 1.5s;
    }

    /* Efek Zoom + Fade untuk Teks */
    @keyframes fadeInFromTop {
      to {
        opacity: 1;
        transform: translateY(0); /* Bergerak kembali ke posisi normal */
      }
    }

    /* Animasi Tombol */
    .options {
      display: flex;
      gap: 20px;
      margin-top: 30px;
      opacity: 0;
      transform: translateY(30px); /* Tombol dimulai lebih rendah */
      animation: fadeInOptions 2s ease-out forwards;
      animation-delay: 2s; /* Tombol muncul setelah teks */
    }

    /* Desain dan Animasi Tombol */
    .button {
      padding: 14px 30px;
      font-size: 1.2rem;
      color: #fff;
      background-color: #0d47a1; /* Warna biru gelap */
      border: 2px solid transparent; /* Border transparan */
      border-radius: 5px;
      cursor: pointer;
      text-decoration: none;
      text-align: center;
      transition: background-color 0.4s ease, transform 0.3s ease, box-shadow 0.4s ease, border-color 0.4s ease;
    }

    /* Efek Hover Tombol */
    .button:hover {
      background-color: #1565c0; /* Biru lebih gelap saat hover */
      transform: scale(1.1); /* Membesarkan tombol sedikit */
      box-shadow: 0px 6px 15px rgba(0, 0, 0, 0.2); /* Bayangan saat hover */
    }

    .button:active {
      background-color: #0d47a1; /* Biru lebih gelap saat tombol diklik */
      transform: scale(1.05); /* Efek saat tombol diklik */
      box-shadow: 0px 5px 12px rgba(0, 0, 0, 0.1);
    }

    /* Animasi untuk Tombol */
    @keyframes fadeInOptions {
      to {
        opacity: 1;
        transform: translateY(0); /* Tombol bergerak ke posisi normal */
      }
    }
  </style>
</head>
<body>
  <div class="overlay"></div>
  <div class="welcome-container">
    <h1 class="welcome-text">Selamat datang di Aplikasi Apotek Online</h1>
    <p class="instructions">Pilih opsi di bawah ini untuk melanjutkan:</p>
  </div>
  <div class="options">
    <a href="daftar.php" class="button">Daftar</a>
    <a href="login.php" class="button">Login</a>
  </div>
</body>
</html>
