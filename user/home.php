<?php
session_start();

// Check if the user is logged in and has a 'user' role
if (!isset($_SESSION['user_id']) || $_SESSION['role_name'] !== 'user') {
    // Redirect to login page if not authenticated or not a user
    header('Location: ../login/');
    exit();
}

// Include the database connection file from the parent directory
require_once '../config.php';

// Get the user_id from the session
$user_id = $_SESSION['user_id'];

// Prepare and execute the query to count all files for the logged-in user
$stmt = $conn->prepare("SELECT COUNT(*) AS total_files FROM tb_file WHERE user_id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();
$total_files = $row['total_files'];

// Prepare and execute the query to count active files for the logged-in user
$stmt_active = $conn->prepare("SELECT COUNT(*) AS active_files FROM tb_file WHERE user_id = ? AND status = 'active'");
$stmt_active->bind_param("i", $user_id);
$stmt_active->execute();
$result_active = $stmt_active->get_result();
$row_active = $result_active->fetch_assoc();
$active_files = $row_active['active_files'];

// Prepare and execute the query to count inactive files for the logged-in user
$stmt_inactive = $conn->prepare("SELECT COUNT(*) AS inactive_files FROM tb_file WHERE user_id = ? AND status = 'inactive'");
$stmt_inactive->bind_param("i", $user_id);
$stmt_inactive->execute();
$result_inactive = $stmt_inactive->get_result();
$row_inactive = $result_inactive->fetch_assoc();
$inactive_files = $row_inactive['inactive_files'];

// Close the statements and connection
$stmt->close();
$stmt_active->close();
$stmt_inactive->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($web_name) ? $web_name : 'My Application'; ?> | Home</title>
    <!-- Bootstrap CSS -->
    <!-- <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css"> -->
    <!-- AdminLTE CSS -->
    <!-- <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/css/adminlte.min.css"> -->
    <!-- Font Awesome -->
    <!-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css"> -->
</head>
<body>
    <div class="container pt-4 pb-4">
        <!-- Bootstrap Row for Info Boxes -->
        <div class="row">
            <!-- Total Files Info Box -->
            <div class="col-md-4">
                <div class="small-box bg-info">
                    <div class="inner">
                        <h3><?php echo $total_files; ?></h3>
                        <p>Total Files</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-file"></i>
                    </div>
                    <a href="#" class="small-box-footer">
                        More info <i class="fas fa-arrow-circle-right"></i>
                    </a>
                </div>
            </div>

            <!-- Active Files Info Box -->
            <div class="col-md-4">
                <div class="small-box bg-success">
                    <div class="inner">
                        <h3><?php echo $active_files; ?></h3>
                        <p>Active Files</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-check-circle"></i>
                    </div>
                    <a href="#" class="small-box-footer">
                        More info <i class="fas fa-arrow-circle-right"></i>
                    </a>
                </div>
            </div>

            <!-- Inactive Files Info Box -->
            <div class="col-md-4">
                <div class="small-box bg-warning">
                    <div class="inner">
                        <h3><?php echo $inactive_files; ?></h3>
                        <p>Inactive Files</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-times-circle"></i>
                    </div>
                    <a href="#" class="small-box-footer">
                        More info <i class="fas fa-arrow-circle-right"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS and dependencies -->
    <!-- <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script> -->
    <!-- AdminLTE JS -->
    <!-- <script src="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/js/adminlte.min.js"></script> -->
    <!-- jQuery (if needed) -->
    <!-- <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script> -->
</body>
</html>
