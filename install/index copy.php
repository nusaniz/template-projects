<?php

include '../setup.php';

// Aktifkan error reporting
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$messages = []; // Array to store messages
$install_success = false; // Variable to check if installation is successful

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Ambil data dari form
    $servername = $_POST['servername'];
    $username = $_POST['username'];
    $password = $_POST['password'];
    $dbname = $_POST['dbname'];
    $adminUsername = $_POST['admin_username'];
    $adminPassword = $_POST['admin_password'];

    // Coba membuat koneksi
    $conn = new mysqli($servername, $username, $password);

    // Cek koneksi
    if ($conn->connect_error) {
        $messages[] = "<div class='alert alert-danger'>Koneksi gagal: " . $conn->connect_error . "</div>";
    } else {
        // Buat database jika belum ada
        $sql = "CREATE DATABASE IF NOT EXISTS `$dbname` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;";
        if ($conn->query($sql) === TRUE) {
            $messages[] = "<div class='alert alert-success'>Database `$dbname` berhasil dibuat atau sudah ada.</div>";
        } else {
            $messages[] = "<div class='alert alert-danger'>Error membuat database: " . $conn->error . "</div>";
            $conn->close();
            exit();
        }

        // Pilih database yang akan digunakan
        $conn->select_db($dbname);

        // Buat tabel tb_cuti jika belum ada
        $sql = "CREATE TABLE IF NOT EXISTS tb_cuti (
            id INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
            user_id INT(11) NOT NULL,
            jenis_cuti_id INT(11) NOT NULL,
            alasan TEXT NOT NULL,
            tanggal_mulai DATE NOT NULL,
            tanggal_akhir DATE NOT NULL,
            status ENUM('pending','accepted','rejected') NOT NULL,
            created_date DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;";

        if ($conn->query($sql) === TRUE) {
            $messages[] = "<div class='alert alert-success'>Tabel tb_cuti berhasil dibuat atau sudah ada.</div>";
        } else {
            $messages[] = "<div class='alert alert-danger'>Error membuat tabel tb_cuti: " . $conn->error . "</div>";
        }

        // Buat tabel tb_jenis_cuti jika belum ada
        $sql = "CREATE TABLE IF NOT EXISTS tb_jenis_cuti (
            id INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
            name VARCHAR(100) NOT NULL
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;";

        if ($conn->query($sql) === TRUE) {
            $messages[] = "<div class='alert alert-success'>Tabel tb_jenis_cuti berhasil dibuat atau sudah ada.</div>";
        } else {
            $messages[] = "<div class='alert alert-danger'>Error membuat tabel tb_jenis_cuti: " . $conn->error . "</div>";
        }

        // Buat tabel tb_users jika belum ada
        $sql = "CREATE TABLE IF NOT EXISTS tb_users (
            id INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
            username VARCHAR(50) NOT NULL,
            nama VARCHAR(100) NOT NULL,
            password VARCHAR(255) NOT NULL,
            role ENUM('admin','manager','staf','pegawai') NOT NULL,
            status ENUM('active','inactive') NOT NULL
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;";

        if ($conn->query($sql) === TRUE) {
            $messages[] = "<div class='alert alert-success'>Tabel tb_users berhasil dibuat atau sudah ada.</div>";
        } else {
            $messages[] = "<div class='alert alert-danger'>Error membuat tabel tb_users: " . $conn->error . "</div>";
        }

        // Cek apakah user admin sudah ada
        $stmt = $conn->prepare("SELECT * FROM tb_users WHERE username = ?");
        $stmt->bind_param("s", $adminUsername);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows == 0) {
            // Prepare the statement to insert user
            $stmt = $conn->prepare("INSERT INTO tb_users (username, nama, password, role, status) VALUES (?, 'Admin', ?, 'admin', 'active')");
            $stmt->bind_param("ss", $adminUsername, $adminPassword); // No password hash

            if ($stmt->execute()) {
                $messages[] = "<div class='alert alert-success'>User admin berhasil ditambahkan.</div>";

                // Create config.ini file with database settings
                $configContent = <<<EOT
[database]
servername = "$servername"
username = "$username"
password = "$password"
dbname = "$dbname"
EOT;

                file_put_contents("../config.ini", $configContent);
                $install_success = true; // Set install_success to true if all steps are successful
            } else {
                $messages[] = "<div class='alert alert-danger'>Error menambahkan user admin: " . $stmt->error . "</div>";
            }
        } else {
            $messages[] = "<div class='alert alert-info'>User admin sudah ada.</div>";
            $install_success = true; // Set install_success to true if user already exists
        }

        // Tutup koneksi
        $stmt->close();
        $conn->close();
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $web_name; ?> | Installation</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-5 mb-5">

        <h1 class="text-center mb-4">Installation</h1>

        <!-- Database and User Setup Card -->
        <div class="card mb-4" style="width: 300px; margin: 0 auto;">
            <div class="card-header">
                <h5 class="card-title">Database and User Setup</h5>
            </div>
            <div class="card-body">
                <?php
                // Display messages
                foreach ($messages as $message) {
                    echo $message;
                }
                ?>

                <?php if ($install_success): ?>
                    <a href="../login/" class="btn btn-success">Go to Login</a>
                <?php else: ?>
                    <form method="post" action="" class="needs-validation" novalidate>
                        <div class="mb-3">
                            <label for="servername" class="form-label">Servername:</label>
                            <input type="text" id="servername" name="servername" class="form-control" value='localhost' required>
                        </div>
                        <div class="mb-3">
                            <label for="username" class="form-label">Username:</label>
                            <input type="text" id="username" name="username" class="form-control" value='root' required>
                        </div>
                        <div class="mb-3">
                            <label for="password" class="form-label">Password:</label>
                            <input type="password" id="password" name="password" class="form-control">
                        </div>
                        <div class="mb-3">
                            <label for="dbname" class="form-label">Database Name:</label>
                            <input type="text" id="dbname" name="dbname" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label for="admin_username" class="form-label">Admin Username:</label>
                            <input type="text" id="admin_username" name="admin_username" class="form-control" value='admin' required>
                        </div>
                        <div class="mb-3">
                            <label for="admin_password" class="form-label">Admin Password:</label>
                            <input type="password" id="admin_password" name="admin_password" class="form-control" required>
                        </div>
                        <button type="submit" class="btn btn-primary">Install</button>
                    </form>
                <?php endif; ?>
            </div>
        </div>

        <!-- Information Card -->
        <div class="card" style="width: 300px; margin: 0 auto;">
            <div class="card-header">
                <h5 class="card-title">About This Application</h5>
            </div>
            <div class="card-body">
                <p class="card-text">This application is developed by <?php echo $web_name; ?>.</p>
                <p class="card-text">For support, please contact us at <a href="mailto:<?php echo $web_email; ?>"><?php echo $web_email; ?></a>.</p>
            </div>
        </div>
        
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
