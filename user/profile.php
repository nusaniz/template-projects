<?php
session_start();
include '../config.php'; // Adjust path to config.php

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: ../login/");
    exit();
}

// Get the logged-in user's ID
$user_id = $_SESSION['user_id'];

// Query to get user details
$query = "
    SELECT 
        u.id, 
        u.username, 
        u.full_name, 
        u.role_id, 
        r.name AS role_name, 
        u.divisi_id, 
        d.name AS divisi_name, 
        u.jabatan_id, 
        j.name AS jabatan_name 
    FROM 
        tb_users u
    LEFT JOIN 
        tb_role r ON u.role_id = r.id
    LEFT JOIN 
        tb_divisi d ON u.divisi_id = d.id
    LEFT JOIN 
        tb_jabatan j ON u.jabatan_id = j.id
    WHERE 
        u.id = ?
";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 1) {
    $user = $result->fetch_assoc();
} else {
    echo "User not found.";
    exit();
}

// Close the statement
$stmt->close();

// Close the database connection
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $web_name;?> | Profile</title>
    <!-- Bootstrap CSS from jsDelivr -->
    <!-- <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-rbsA2VBKQDUULqAq8gF1M5h1p1XgMjFpg6ftJGdgvP7P9xEZOcMzzZfH3zT2bnh8" crossorigin="anonymous"> -->
</head>
<body>
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="card shadow-sm">
                    <div class="card-header bg-primary text-white">
                        <h4 class="mb-0">Profile</h4>
                    </div>
                    <div class="card-body">
                        <div class="d-flex align-items-center mb-4">
                            <div class="flex-shrink-0">
                                <img src="https://via.placeholder.com/100" alt="User Avatar" class="rounded-circle" width="100" height="100">
                            </div>
                            <div class="ms-3">
                                <h5 class="card-title mb-0"><?php echo htmlspecialchars($user['full_name']); ?></h5>
                                <p class="text-muted mb-0"><?php echo htmlspecialchars($user['username']); ?></p>
                            </div>
                        </div>

                        <ul class="list-group">
                            <li class="list-group-item"><strong>Role:</strong> <?php echo htmlspecialchars($user['role_name']); ?></li>
                            <li class="list-group-item"><strong>Divisi:</strong> <?php echo htmlspecialchars($user['divisi_name']); ?></li>
                            <li class="list-group-item"><strong>Jabatan:</strong> <?php echo htmlspecialchars($user['jabatan_name']); ?></li>
                        </ul>

                        <div class="text-end mt-4">
                            <a href="?page=change_password" class="btn btn-primary">Change Password</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS and dependencies -->
    <!-- <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js" integrity="sha384-oBqDVmMz4fnFO9shlJ3H8C0G3IH6S3p3Rk5lbzXZcB5N4Z6kD5xZXKZR7ZlM1J0g" crossorigin="anonymous"></script> -->
    <!-- <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.min.js" integrity="sha384-QFf8n7W5y3ni6YlU09HbO3NLS3pE+/rgwMjX4PM9lYfAzrYltjMQaDdzg9KY3Cs9" crossorigin="anonymous"></script> -->
</body>
</html>
