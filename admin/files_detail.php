<?php
// session_start();
include '../config.php';  // Adjusted path to config.php

// Check if the user is logged in and has the admin role
if (!isset($_SESSION['user_id']) || $_SESSION['role_name'] !== 'admin') {
    header("Location: ../login.php");
    exit();
}

// Get file ID from URL
$fileId = isset($_GET['id']) ? (int)$_GET['id'] : 0;

// Fetch file details from the database
if ($fileId > 0) {
    $fileQuery = "
        SELECT 
            f.id, 
            f.uuid, 
            f.user_id, 
            f.file_name, 
            f.file, 
            f.file_hash, 
            f.divisi_id, 
            f.jabatan_id, 
            f.status, 
            f.created_at, 
            f.update_at,
            u.username, 
            u.`full_name` AS full_name,
            d.name AS divisi_name,
            j.name AS jabatan_name
        FROM tb_file f
        LEFT JOIN tb_users u ON f.user_id = u.id
        LEFT JOIN tb_divisi d ON f.divisi_id = d.id
        LEFT JOIN tb_jabatan j ON f.jabatan_id = j.id
        WHERE f.id = $fileId
    ";
    $fileResult = mysqli_query($conn, $fileQuery);

    if ($fileResult && mysqli_num_rows($fileResult) > 0) {
        $fileData = mysqli_fetch_assoc($fileResult);
    } else {
        die("File not found.");
    }
} else {
    die("Invalid file ID.");
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $web_name;?> | File Details</title>
    <!-- Bootstrap CSS from jsDelivr -->
    <!-- <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet"> -->
</head>
<body>
    <div class="container pt-4 pb-4">
        <h3 class="mb-4">File Details</h3>

        <a href="?page=files" class="btn btn-secondary mb-3">Back to List</a>

        <table class="table table-striped table-bordered">
            <tbody>
                <tr>
                    <th>ID</th>
                    <td><?php echo htmlspecialchars($fileData['id']); ?></td>
                </tr>
                <tr>
                    <th>UUID</th>
                    <td><?php echo htmlspecialchars($fileData['uuid']); ?></td>
                </tr>
                <tr>
                    <th>User ID</th>
                    <td><?php echo htmlspecialchars($fileData['user_id']); ?></td>
                </tr>
                <tr>
                    <th>Username</th>
                    <td><?php echo htmlspecialchars($fileData['username']); ?></td>
                </tr>
                <tr>
                    <th>full_name</th>
                    <td><?php echo htmlspecialchars($fileData['full_name']); ?></td>
                </tr>
                <tr>
                    <th>File Name</th>
                    <td><?php echo htmlspecialchars($fileData['file_name']); ?></td>
                </tr>
                <tr>
                    <th>File</th>
                    <td><?php echo htmlspecialchars($fileData['file']); ?></td>
                </tr>
                <tr>
                    <th>File Hash</th>
                    <td><?php echo htmlspecialchars($fileData['file_hash']); ?></td>
                </tr>
                <tr>
                    <th>Divisi ID</th>
                    <td><?php echo htmlspecialchars($fileData['divisi_id']); ?></td>
                </tr>
                <tr>
                    <th>Divisi Name</th>
                    <td><?php echo htmlspecialchars($fileData['divisi_name']); ?></td>
                </tr>
                <tr>
                    <th>Jabatan ID</th>
                    <td><?php echo htmlspecialchars($fileData['jabatan_id']); ?></td>
                </tr>
                <tr>
                    <th>Jabatan Name</th>
                    <td><?php echo htmlspecialchars($fileData['jabatan_name']); ?></td>
                </tr>
                <tr>
                    <th>Status</th>
                    <td><?php echo htmlspecialchars($fileData['status']); ?></td>
                </tr>
                <tr>
                    <th>Created At</th>
                    <td><?php echo htmlspecialchars($fileData['created_at']); ?></td>
                </tr>
                <tr>
                    <th>Updated At</th>
                    <td><?php echo htmlspecialchars($fileData['update_at']); ?></td>
                </tr>
            </tbody>
        </table>
    </div>

    <!-- Bootstrap JS Bundle with Popper from jsDelivr -->
    <!-- <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script> -->
</body>
</html>

<?php
// Close the database connection
$conn->close();
?>
