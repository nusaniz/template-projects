<?php
session_start();

// Check if the user is logged in and has an 'admin' role
if (!isset($_SESSION['user_id']) || $_SESSION['role_name'] !== 'admin') {
    // Redirect to login page if not authenticated or not an admin
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

// Queries for overall data
// Prepare and execute the query to count all files across all users
$stmt_total_all = $conn->prepare("SELECT COUNT(*) AS total_files_all FROM tb_file");
$stmt_total_all->execute();
$result_total_all = $stmt_total_all->get_result();
$row_total_all = $result_total_all->fetch_assoc();
$total_files_all = $row_total_all['total_files_all'];

// Prepare and execute the query to count active files across all users
$stmt_active_all = $conn->prepare("SELECT COUNT(*) AS active_files_all FROM tb_file WHERE status = 'active'");
$stmt_active_all->execute();
$result_active_all = $stmt_active_all->get_result();
$row_active_all = $result_active_all->fetch_assoc();
$active_files_all = $row_active_all['active_files_all'];

// Prepare and execute the query to count inactive files across all users
$stmt_inactive_all = $conn->prepare("SELECT COUNT(*) AS inactive_files_all FROM tb_file WHERE status = 'inactive'");
$stmt_inactive_all->execute();
$result_inactive_all = $stmt_inactive_all->get_result();
$row_inactive_all = $result_inactive_all->fetch_assoc();
$inactive_files_all = $row_inactive_all['inactive_files_all'];

// Prepare and execute the query to count all file verifications
$stmt_total_verifications = $conn->prepare("SELECT COUNT(*) AS total_verifications FROM tb_file_verification");
$stmt_total_verifications->execute();
$result_total_verifications = $stmt_total_verifications->get_result();
$row_total_verifications = $result_total_verifications->fetch_assoc();
$total_verifications = $row_total_verifications['total_verifications'];

// Prepare and execute the query to count total users
$stmt_total_users = $conn->prepare("SELECT COUNT(*) AS total_users FROM tb_users");
$stmt_total_users->execute();
$result_total_users = $stmt_total_users->get_result();
$row_total_users = $result_total_users->fetch_assoc();
$total_users = $row_total_users['total_users'];

// Prepare and execute the query to count total roles
$stmt_total_roles = $conn->prepare("SELECT COUNT(*) AS total_roles FROM tb_role");
$stmt_total_roles->execute();
$result_total_roles = $stmt_total_roles->get_result();
$row_total_roles = $result_total_roles->fetch_assoc();
$total_roles = $row_total_roles['total_roles'];

// Prepare and execute the query to count total divisions
$stmt_total_divisions = $conn->prepare("SELECT COUNT(*) AS total_divisions FROM tb_divisi");
$stmt_total_divisions->execute();
$result_total_divisions = $stmt_total_divisions->get_result();
$row_total_divisions = $result_total_divisions->fetch_assoc();
$total_divisions = $row_total_divisions['total_divisions'];

// Prepare and execute the query to count total positions
$stmt_total_positions = $conn->prepare("SELECT COUNT(*) AS total_positions FROM tb_jabatan");
$stmt_total_positions->execute();
$result_total_positions = $stmt_total_positions->get_result();
$row_total_positions = $result_total_positions->fetch_assoc();
$total_positions = $row_total_positions['total_positions'];

// Close the statements and connection
$stmt->close();
$stmt_active->close();
$stmt_inactive->close();
$stmt_total_all->close();
$stmt_active_all->close();
$stmt_inactive_all->close();
$stmt_total_verifications->close();
$stmt_total_users->close();
$stmt_total_roles->close();
$stmt_total_divisions->close();
$stmt_total_positions->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $web_name; ?> | Home</title>
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
            <!-- User Specific Info Boxes -->
            <div class="col-md-4">
                <div class="small-box bg-info">
                    <div class="inner">
                        <h3><?php echo $total_files; ?></h3>
                        <p>Total Files (User)</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-file"></i>
                    </div>
                    <a href="#" class="small-box-footer">
                        More info <i class="fas fa-arrow-circle-right"></i>
                    </a>
                </div>
            </div>

            <div class="col-md-4">
                <div class="small-box bg-success">
                    <div class="inner">
                        <h3><?php echo $active_files; ?></h3>
                        <p>Active Files (User)</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-check-circle"></i>
                    </div>
                    <a href="#" class="small-box-footer">
                        More info <i class="fas fa-arrow-circle-right"></i>
                    </a>
                </div>
            </div>

            <div class="col-md-4">
                <div class="small-box bg-warning">
                    <div class="inner">
                        <h3><?php echo $inactive_files; ?></h3>
                        <p>Inactive Files (User)</p>
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

        <!-- Overall Info Boxes -->
        <div class="row mt-4">
            <div class="col-md-4">
                <div class="small-box bg-info">
                    <div class="inner">
                        <h3><?php echo $total_files_all; ?></h3>
                        <p>Total Files (All Users)</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-file"></i>
                    </div>
                    <a href="#" class="small-box-footer">
                        More info <i class="fas fa-arrow-circle-right"></i>
                    </a>
                </div>
            </div>

            <div class="col-md-4">
                <div class="small-box bg-success">
                    <div class="inner">
                        <h3><?php echo $active_files_all; ?></h3>
                        <p>Active Files (All Users)</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-check-circle"></i>
                    </div>
                    <a href="#" class="small-box-footer">
                        More info <i class="fas fa-arrow-circle-right"></i>
                    </a>
                </div>
            </div>

            <div class="col-md-4">
                <div class="small-box bg-warning">
                    <div class="inner">
                        <h3><?php echo $inactive_files_all; ?></h3>
                        <p>Inactive Files (All Users)</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-times-circle"></i>
                    </div>
                    <a href="#" class="small-box-footer">
                        More info <i class="fas fa-arrow-circle-right"></i>
                    </a>
                </div>
            </div>

            <!-- File Verifications Info Box -->
            <div class="col-md-4 mt-4">
                <div class="small-box bg-primary">
                    <div class="inner">
                        <h3><?php echo $total_verifications; ?></h3>
                        <p>Total File Verifications</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-check-double"></i>
                    </div>
                    <a href="#" class="small-box-footer">
                        More info <i class="fas fa-arrow-circle-right"></i>
                    </a>
                </div>
            </div>

            <!-- Additional Info Boxes -->
            <div class="col-md-3 mt-4">
                <div class="small-box bg-secondary">
                    <div class="inner">
                        <h3><?php echo $total_users; ?></h3>
                        <p>Total Users</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-users"></i>
                    </div>
                    <a href="#" class="small-box-footer">
                        More info <i class="fas fa-arrow-circle-right"></i>
                    </a>
                </div>
            </div>

            <div class="col-md-3 mt-4">
                <div class="small-box bg-secondary">
                    <div class="inner">
                        <h3><?php echo $total_roles; ?></h3>
                        <p>Total Roles</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-user-tag"></i>
                    </div>
                    <a href="#" class="small-box-footer">
                        More info <i class="fas fa-arrow-circle-right"></i>
                    </a>
                </div>
            </div>

            <div class="col-md-3 mt-4">
                <div class="small-box bg-secondary">
                    <div class="inner">
                        <h3><?php echo $total_divisions; ?></h3>
                        <p>Total Divisions</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-sitemap"></i>
                    </div>
                    <a href="#" class="small-box-footer">
                        More info <i class="fas fa-arrow-circle-right"></i>
                    </a>
                </div>
            </div>

            <div class="col-md-3 mt-4">
                <div class="small-box bg-secondary">
                    <div class="inner">
                        <h3><?php echo $total_positions; ?></h3>
                        <p>Total Positions</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-briefcase"></i>
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
