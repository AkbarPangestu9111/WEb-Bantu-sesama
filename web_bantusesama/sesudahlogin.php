<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Bantu Sesama</title>
  <style>
    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
    }
 
    body {
      font-family: 'Arial', sans-serif;
      line-height: 1.6;
      color: #333;
    }

    .container {
      width: 90%;
      max-width: 1200px;
      margin: 0 auto;
    }

    .navbar {
      background: #333;
      border-bottom: 1px solid #ddd;
      padding: 10px 0;
    }

    .logo {
      height: 40px;
      float: left;
    }

    .nav-menu {
      list-style: none;
      display: flex;
      justify-content: flex-end;
      gap: 20px;
    }

    .btn-logout {
      background: #e60000;
      color: #fff;
      padding: 10px 20px;
      text-decoration: none;
      font-weight: bold;
      border-radius: 5px;
    }

    .btn-logout:hover {
      background: #a80202;
    }

    .btn-profil {
      background: #e60000;
      color: #fff;
      padding: 10px 20px;
      text-decoration: none;
      font-weight: bold;
      border-radius: 5px;
    }

    .btn-profil:hover {
      background: #a80202;
    }

    .nav-menu li a {
      text-decoration: none;
      color: #333;
      font-weight: bold;
    }

    .hero {
      background: url('https://assets-a1.kompasiana.com/items/album/2019/03/04/103830982-gettyimages-1046032522-5c7c964b43322f127d61530a.jpg?t=o&v=1200') no-repeat center center/cover;
      color: #fff;
      height: 120vh;
      display: top;
      align-items: center;
      text-align: center;
    }

    .hero-content {
      width: 100%;
      background: rgba(0,0,0,0.5);
      padding: 60px 20px;
    }

    .hero h1 {
      font-size: 3rem;
      margin-bottom: 20px;
    }

    .hero p {
      font-size: 1.2rem;
      margin-bottom: 30px;
    }

    .btn-donate {
      background: #e60000;
      color: #fff;
      padding: 15px 30px;
      text-decoration: none;
      font-weight: bold;
      border-radius: 5px;
    }

    .btn-donate:hover {
      background: #a80202;
    }

    .btn-opendonate {
      background:rgb(67, 4, 254);
      color: #fff;
      padding: 15px 30px;
      text-decoration: none;
      font-weight: bold;
      border-radius: 5px;
    }

    .btn-opendonate:hover {
      background: #a80202;
    }

    .info {
      height: 20vh;
      color: #fff;
      padding: 60px 20px;
      background: #8a8888;
      text-align: center;
    }

    footer {
      background: #333;
      color: #fff;
      text-align: center;
      padding: 20px 0;
    }

    @media (max-width: 768px) {
      .nav-menu {
        flex-direction: column;
        align-items: center;
      }

      .hero h1 {
        font-size: 2rem;
      }

      .hero p {
        font-size: 1rem;
      }
    }
  </style>
</head>
<body>
  <header class="navbar">
    <div class="container">
      <img src="https://png.pngtree.com/png-clipart/20201208/original/pngtree-unity-and-mutual-aid-icon-png-image_5609698.jpg" alt="Bantu Sesama" class="logo">
      <nav>
        <ul class="nav-menu">
          <a href="sebelumlogin.php" class="btn-logout">Logout</a>
          <a href="profil.php" class="btn-profil">profil</a>
        </ul>
      </nav>
    </div>
  </header>

  <section class="hero">
    <div class="hero-content">
      <h1>Selamat Datang Di Aplikasi Bantu Sesama</h1>
      <p>Sekecil apa pun bantuan Anda, sangat berarti bagi mereka</p>
      <a href="#" class="btn-donate">Donasi Sekarang</a>
      <a href="insert.php" class="btn-opendonate">Formulir Donasi</a>
    </div>
  </section>

  <section class="info">
    <div class="container">
      <h2>Mengapa Donasi Penting?</h2>
      <p>"Menurut data BNPB tahun 2025, setiap jam lebih dari 600 warga Indonesia terdampak bencana alam. Ini adalah pengingat bahwa kesiapsiagaan dan solidaritas sangat penting dalam menghadapi risiko yang terus mengintai."</p>
    </div>
  </section>

  <section class="info">
    <div class="container">
      <p>"Menurut data SIMFONI-PPA KemenPPPA tahun 2025, setiap jam lebih dari dua kasus kekerasan terhadap perempuan dan anak terjadi di Indonesia. Ini adalah pengingat bahwa perlindungan dan pemberdayaan korban sangat penting dalam menghadapi tantangan ini"</p>
    </div>
  </section>

  <section class="info">
    <div class="container">
      <p>"Menurut data JPPI tahun 2024, setiap hari setidaknya satu anak di Indonesia menjadi korban perundungan di lingkungan pendidikan. Ini adalah pengingat bahwa menciptakan lingkungan belajar yang aman untuk masa depan anak-anak kita."</p>
    </div>
  </section>

  <footer>
    <div class="container">
      <p>&copy; 2025 Bantu Sesama. All rights reserved.</p>
    </div>
  </footer>
</body>
</html>