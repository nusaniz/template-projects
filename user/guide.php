<?php
// session_start();

// Check if the user is logged in and has an 'admin' role
if (!isset($_SESSION['user_id']) || $_SESSION['role_name'] !== 'user') {
    // Redirect to login page if not authenticated or not an admin
    header('Location: ../login/');
    exit();
}
// guide.php
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $web_name;?> | Guide</title>

    <!-- Link ke CSS Bootstrap 5.3 dari jsDelivr -->
    <!-- <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet"> -->

    <!-- Link ke Font Awesome (opsional, jika ingin ikon tambahan) -->
    <!-- <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet"> -->
</head>
<body>

    <!-- Konten Utama -->
    <div class="container pt-4 pb-4">
        <h1> Sistem Verifikasi File Dokumen</h1>
        

        <div class="row">
            <div class="col">
                
            </div>
        </div>

    </div>

    <!-- Script Bootstrap 5.3 dari jsDelivr -->
    <!-- <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script> -->
</body>
</html>
