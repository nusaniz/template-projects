<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="author" content="Nizar">
    <meta name="email" content="publi@gmail.com">
    <title><?php echo $web_name;?> | Tentang Kami</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" integrity="sha384-0nI51iVf+S7TcM/RV5K6FJwUlq6CgD8KoIuivnEbR2bBDFc1WwCwSOhY+Njf9i1w" crossorigin="anonymous">
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <header class="bg-primary text-white p-3 mt-4">
        <div class="container">
            <h1 class="h3">Tentang <?php echo $web_name;?></h1>
        </div>
    </header>

    <main class="container mt-3">
        <section>
            <h2>Selamat Datang di <?php echo $web_name;?></h2>
            <p>
                <?php echo $web_name;?> adalah sistem web aplikasi yang dirancang untuk memverifikasi keaslian dokumen digital. Kami menyediakan solusi yang cepat dan terpercaya untuk memeriksa dan memastikan keaslian dokumen file yang Anda terima atau kirimkan.
            </p>

            <h3>Fitur Utama:</h3>
            <ul>
                <li>Verifikasi tanda tangan digital untuk memastikan integritas dokumen.</li>
                <li>Validasi metadata untuk memeriksa keaslian sumber dokumen.</li>
                <li>Penilaian dan pelaporan status keaslian dokumen secara real-time.</li>
                <li>Integrasi dengan berbagai format dokumen seperti PDF, DOCX, dan lainnya.</li>
            </ul>

            <h3>Tujuan Kami:</h3>
            <p>
                Tujuan utama <?php echo $web_name;?> adalah untuk menyediakan alat yang efektif dan mudah digunakan dalam memverifikasi keaslian dokumen digital. Kami berkomitmen untuk meningkatkan keamanan dan kepercayaan dalam pertukaran dokumen melalui teknologi verifikasi mutakhir.
            </p>

            <h3>Teknologi dan Layanan Kami:</h3>
            <ul>
                <li>Platform berbasis web yang mudah diakses dari berbagai perangkat.</li>
                <li>Algoritma verifikasi yang canggih untuk analisis dokumen yang mendalam.</li>
                <li>Dukungan teknis dan pembaruan sistem reguler untuk menjaga keakuratan dan keandalan.</li>
            </ul>

            <h3>Kontak:</h3>
            <p>
                Untuk informasi lebih lanjut atau pertanyaan, silakan hubungi tim kami di <a href="mailto:support@verifikasi-dokumen.com">support@verifikasi-dokumen.com</a> atau kunjungi kantor kami di alamat berikut:
            </p>
            <address>
                Jl. Teknologi No.789, Kota Inovasi, Provinsi Digital<br>
                Telepon: (021) 123-4567
            </address>
        </section>
    </main>

    <footer class="bg-dark text-white text-center p-3 mb-4">
        <p>&copy; 2024 <?php echo $web_name;?>. Semua hak dilindungi.</p>
        <p>Author: M Nizar N | Email: <a href="mailto:publik.nizar@gmail.com">publik.nizar@gmail.com</a></p>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-nmSg6wbz6z7sMHTllcUkFRequI+q2SaFDd9y4LgPFLx3E/NwIAOQF4D+exDFzE8T1" crossorigin="anonymous"></script>
</body>
</html>
