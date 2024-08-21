<?php

include '../setup.php';

// Aktifkan error reporting
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Cek apakah config.ini sudah ada
if (file_exists('../config.ini')) {
    header('Location: ../login/');
    exit();
}

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

        // Define SQL queries for table creation
        $tables = [
            "CREATE TABLE IF NOT EXISTS tb_divisi (
                id BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
                name VARCHAR(255) DEFAULT NULL,
                status ENUM('active','inactive') NOT NULL DEFAULT 'active'
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;",
            "CREATE TABLE IF NOT EXISTS tb_file (
                id BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
                uuid VARCHAR(255) DEFAULT NULL,
                user_id INT(11) NOT NULL,
                file_name VARCHAR(255) NOT NULL,
                file VARCHAR(255) NOT NULL,
                file_hash VARCHAR(255) NOT NULL,
                description TEXT DEFAULT NULL,
                divisi_id VARCHAR(255) DEFAULT NULL,
                jabatan_id VARCHAR(255) DEFAULT NULL,
                status ENUM('active','inactive') NOT NULL DEFAULT 'active',
                created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
                update_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;",
            "CREATE TABLE IF NOT EXISTS tb_file_verification (
                id BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
                uuid VARCHAR(255) DEFAULT NULL,
                file VARCHAR(255) NOT NULL,
                file_hash VARCHAR(255) NOT NULL,
                status ENUM('valid','invalid') NOT NULL,
                created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;",
            "CREATE TABLE IF NOT EXISTS tb_jabatan (
                id BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
                name VARCHAR(255) NOT NULL,
                status ENUM('active','inactive') NOT NULL DEFAULT 'active'
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;",
            "CREATE TABLE IF NOT EXISTS tb_role (
                id BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
                name VARCHAR(255) NOT NULL,
                status ENUM('active','inactive') NOT NULL DEFAULT 'active'
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;",
            "CREATE TABLE IF NOT EXISTS tb_users (
                id BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
                uuid VARCHAR(255) DEFAULT NULL,
                username VARCHAR(255) NOT NULL,
                full_name VARCHAR(255) NOT NULL,
                role_id VARCHAR(255) NOT NULL,
                divisi_id VARCHAR(255) DEFAULT NULL,
                jabatan_id VARCHAR(255) DEFAULT NULL,
                password VARCHAR(255) NOT NULL,
                status ENUM('active','inactive') NOT NULL DEFAULT 'active',
                created_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
                update_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;"
        ];

        // Execute table creation queries
        foreach ($tables as $tableQuery) {
            if ($conn->query($tableQuery) !== TRUE) {
                $messages[] = "<div class='alert alert-danger'>Error membuat tabel: " . $conn->error . "</div>";
            }
        }

        // Insert data into tb_role
        $roles = [
            "INSERT INTO tb_role (name, status) VALUES ('admin', 'active');",
            "INSERT INTO tb_role (name, status) VALUES ('user', 'active');"
        ];

        foreach ($roles as $roleQuery) {
            if ($conn->query($roleQuery) !== TRUE) {
                $messages[] = "<div class='alert alert-danger'>Error memasukkan data ke tb_role: " . $conn->error . "</div>";
            }
        }

        // Cek apakah user admin sudah ada
        $stmt = $conn->prepare("SELECT * FROM tb_users WHERE username = ?");
        $stmt->bind_param("s", $adminUsername);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows == 0) {
            // Prepare the statement to insert user with plain text password
            $stmt = $conn->prepare("INSERT INTO tb_users (username, full_name, password, role_id, status) VALUES (?, ?, ?, '1', 'active')");
            $stmt->bind_param("sss", $adminUsername, $adminUsername, $adminPassword);

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
    </div>
</body>
</html>
