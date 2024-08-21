<?php
// session_start();
include '../config.php';  // Adjusted path to config.php

// Check if the user is logged in and has the admin role
if (!isset($_SESSION['user_id']) || $_SESSION['role_name'] !== 'admin') {
    header("Location: ../login.php");
    exit();
}

// Check if user ID is provided in the URL
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    // header("Location: ../users.php");
    echo "<script>window.location.href = '?page=users';</script>";
    exit();
}

$userId = (int)$_GET['id'];

// Query to get user details including related role, division, and job
$query = "
    SELECT 
        u.id, 
        u.username, 
        u.`full_name`, 
        u.`password`, 
        u.`created_at`, 
        u.`update_at`, 
        r.name AS role_name, 
        d.name AS divisi_name, 
        j.name AS jabatan_name 
    FROM tb_users u
    LEFT JOIN tb_role r ON u.role_id = r.id
    LEFT JOIN tb_divisi d ON u.divisi_id = d.id
    LEFT JOIN tb_jabatan j ON u.jabatan_id = j.id
    WHERE u.id = $userId
";
$result = $conn->query($query);

// Check if the user exists
if ($result->num_rows === 0) {
    // header("Location: ../users.php");
    echo "<script>window.location.href = '?page=users';</script>";
    exit();
}

$user = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $web_name;?> | User Detail</title>
    <!-- Bootstrap CSS from jsDelivr -->
    <!-- <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet"> -->
</head>
<body>
    <div class="container pt-4 pb-4">
        <h3 class="mb-4">User Detail</h3>

        <!-- User Detail Table -->
        <table class="table table-striped table-bordered">
            <tbody>
                <tr>
                    <th>ID</th>
                    <td><?php echo htmlspecialchars($user['id']); ?></td>
                </tr>
                <tr>
                    <th>Username</th>
                    <td><?php echo htmlspecialchars($user['username']); ?></td>
                </tr>
                <tr>
                    <th>full_name</th>
                    <td><?php echo htmlspecialchars($user['full_name']); ?></td>
                </tr>
                <tr>
                    <th>Password</th>
                    <td><?php echo htmlspecialchars($user['password']); ?></td>
                </tr>
                <tr>
                    <th>Role</th>
                    <td><?php echo htmlspecialchars($user['role_name']); ?></td>
                </tr>
                <tr>
                    <th>Division</th>
                    <td><?php echo htmlspecialchars($user['divisi_name']); ?></td>
                </tr>
                <tr>
                    <th>Job</th>
                    <td><?php echo htmlspecialchars($user['jabatan_name']); ?></td>
                </tr>
                <tr>
                    <th>Created At</th>
                    <td><?php echo htmlspecialchars($user['created_at']); ?></td>
                </tr>
                <tr>
                    <th>Updated At</th>
                    <td><?php echo htmlspecialchars($user['update_at']); ?></td>
                </tr>
            </tbody>
        </table>

        <!-- Back Button -->
        <a href="?page=users" class="btn btn-secondary">Back to Users List</a>
    </div>

    <!-- Bootstrap JS Bundle with Popper from jsDelivr -->
    <!-- <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script> -->
</body>
</html>

<?php
// Close the database connection
$conn->close();
?>
