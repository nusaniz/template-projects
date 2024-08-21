<?php
// session_start();
include '../config.php'; // Adjust path to config.php

// Check if the user is logged in and has the "user" role
if (!isset($_SESSION['user_id']) || $_SESSION['role_name'] !== 'user') {
    header("Location: ../login/");
    exit();
}

// Check if the 'id' parameter is set in the URL
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    echo "Invalid file ID.";
    exit();
}

// Retrieve the file ID from the URL
$file_id = (int)$_GET['id'];

// Prepare and execute SQL statement to get file details
$query = "
    SELECT 
        f.id, 
        f.uuid, 
        f.file_name, 
        f.file, 
        f.file_hash, 
        f.divisi_id, 
        f.jabatan_id, 
        f.status, 
        f.created_at, 
        f.update_at,
        f.user_id,
        u.username,
        u.`full_name` AS full_name, 
        d.name AS divisi_name,
        j.name AS jabatan_name
    FROM tb_file f
    LEFT JOIN tb_divisi d ON f.divisi_id = d.id
    LEFT JOIN tb_jabatan j ON f.jabatan_id = j.id
    LEFT JOIN tb_users u ON f.user_id = u.id
    WHERE f.id = ? AND f.user_id = ?
";
$stmt = $conn->prepare($query);
$stmt->bind_param("ii", $file_id, $_SESSION['user_id']);
$stmt->execute();
$result = $stmt->get_result();

// Fetch file details
if ($result->num_rows == 1) {
    $file = $result->fetch_assoc();
} else {
    // echo "File not found or access denied.";
    echo "<script>window.location.href= '?page=files'</script>";
    exit();
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

        <table class="table table-bordered">
            <tr>
                <th>ID</th>
                <td><?php echo htmlspecialchars($file['id']); ?></td>
            </tr>
            <tr>
                <th>UUID</th>
                <td><?php echo htmlspecialchars($file['uuid']); ?></td>
            </tr>
            <tr>
                <th>Username</th>
                <td><?php echo htmlspecialchars($file['username']); ?></td>
            </tr>
            <tr>
                <th>Full Name</th>
                <td><?php echo htmlspecialchars($file['full_name']); ?></td>
            </tr>
            <tr>
                <th>File Name</th>
                <td><?php echo htmlspecialchars($file['file_name']); ?></td>
            </tr>
            <tr>
                <th>File</th>
                <td><?php echo htmlspecialchars($file['file']); ?></td>
            </tr>
            <tr>
                <th>File Hash</th>
                <td><?php echo htmlspecialchars($file['file_hash']); ?></td>
            </tr>
            <tr>
                <th>Divisi ID</th>
                <td><?php echo htmlspecialchars($file['divisi_id']); ?></td>
            </tr>
            <tr>
                <th>Divisi Name</th>
                <td><?php echo htmlspecialchars($file['divisi_name']); ?></td>
            </tr>
            <tr>
                <th>Jabatan ID</th>
                <td><?php echo htmlspecialchars($file['jabatan_id']); ?></td>
            </tr>
            <tr>
                <th>Jabatan Name</th>
                <td><?php echo htmlspecialchars($file['jabatan_name']); ?></td>
            </tr>
            <tr>
                <th>Status</th>
                <td><?php echo htmlspecialchars($file['status']); ?></td>
            </tr>
            <tr>
                <th>Created At</th>
                <td><?php echo htmlspecialchars($file['created_at']); ?></td>
            </tr>
            <tr>
                <th>Updated At</th>
                <td><?php echo htmlspecialchars($file['update_at']); ?></td>
            </tr>
        </table>

        <a href="?page=files" class="btn btn-primary">Back to Files</a>
    </div>

    <!-- Bootstrap JS Bundle with Popper from jsDelivr -->
    <!-- <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script> -->
</body>
</html>

<?php
// Close the database connection
$conn->close();
?>
