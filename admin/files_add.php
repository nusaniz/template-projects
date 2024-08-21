<?php
// session_start();
include '../config.php';  // Adjusted path to config.php

// Check if the user is logged in and has the admin role
if (!isset($_SESSION['user_id']) || $_SESSION['role_name'] !== 'admin') {
    header("Location: ../login.php");
    exit();
}

// Initialize variables for the form
$file_name = '';
$status = 'active'; // Default status
$user_id = '';
$divisi_id = '';
$jabatan_id = '';
$description = ''; // Initialize description
$errors = [];

// Fetch users for the user_id dropdown
$userQuery = "SELECT id, `full_name` FROM tb_users";
$userResult = $conn->query($userQuery);

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve and sanitize user input
    $user_id = trim($_POST['user_id']);
    $file_name = trim($_POST['file_name']);
    $divisi_id = trim($_POST['divisi_id']);
    $jabatan_id = trim($_POST['jabatan_id']);
    $description = trim($_POST['description']); // Sanitize description input
    $status = trim($_POST['status']);

    // Handle file upload
    if (isset($_FILES['file']) && $_FILES['file']['error'] === UPLOAD_ERR_OK) {
        $fileTmpPath = $_FILES['file']['tmp_name'];
        $fileOriginalName = $_FILES['file']['name']; // Original file name
        $fileSize = $_FILES['file']['size'];
        $fileType = $_FILES['file']['type'];

        // Generate a unique prefix
        $uniquePrefix = substr(uniqid(time(), true), 0, 10);
        $fileBaseName = $uniquePrefix . '_' . basename($fileOriginalName); // Rename file with prefix

        $uploadFileDir = '../uploads/';
        $destPath = $uploadFileDir . $fileBaseName; // Use new file name

        // Check if the upload directory exists, if not create it
        if (!is_dir($uploadFileDir)) {
            mkdir($uploadFileDir, 0755, true);
        }

        // Move the file to the desired directory
        if (move_uploaded_file($fileTmpPath, $destPath)) {
            // Calculate the SHA-256 hash of the uploaded file
            $fileHash = hash_file('sha256', $destPath);

            // Prepare the SQL statement to insert a new file
            $stmt = $conn->prepare("
                INSERT INTO tb_file (user_id, file_name, file, file_hash, divisi_id, jabatan_id, status, description) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?)
            ");
            $stmt->bind_param("isssssss", $user_id, $file_name, $fileBaseName, $fileHash, $divisi_id, $jabatan_id, $status, $description);

            if ($stmt->execute()) {
                // Redirect to files list after successful insertion
                echo "<script>window.location.href = '?page=files';</script>";
                exit();
            } else {
                $errors[] = "Failed to add file: " . $conn->error;
            }

            // Close the statement
            $stmt->close();
        } else {
            $errors[] = "Failed to move uploaded file.";
        }
    } else {
        $errors[] = "No file uploaded or there was an upload error.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $web_name;?> | Add File</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>
    <div class="container pt-4 pb-4">
        <h3 class="mb-4">Add New File</h3>

        <!-- Display errors if any -->
        <?php if (!empty($errors)): ?>
            <div class="alert alert-danger">
                <ul>
                    <?php foreach ($errors as $error): ?>
                        <li><?php echo htmlspecialchars($error); ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>

        <!-- Add File Form -->
        <form method="post" action="" enctype="multipart/form-data">
            <div class="mb-3">
                <label for="user_id" class="form-label">User</label>
                <select id="user_id" name="user_id" class="form-select" required>
                    <option value="">Select User</option>
                    <?php while ($user = $userResult->fetch_assoc()): ?>
                        <option value="<?php echo htmlspecialchars($user['id']); ?>" <?php echo ($user_id == $user['id']) ? 'selected' : ''; ?>>
                            <?php echo htmlspecialchars($user['full_name']); ?>
                        </option>
                    <?php endwhile; ?>
                </select>
            </div>
            <div class="mb-3">
                <label for="file_name" class="form-label">File Name</label>
                <input type="text" id="file_name" name="file_name" class="form-control" value="<?php echo htmlspecialchars($file_name); ?>" required>
            </div>
            <div class="mb-3">
                <label for="file" class="form-label">File</label>
                <input type="file" id="file" name="file" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="description" class="form-label">Description</label>
                <textarea id="description" name="description" class="form-control" rows="4"><?php echo htmlspecialchars($description); ?></textarea>
            </div>
            <div class="mb-3">
                <label for="divisi_id" class="form-label">Divisi</label>
                <select id="divisi_id" name="divisi_id" class="form-select">
                    <!-- Options will be populated based on user selection -->
                </select>
            </div>
            <div class="mb-3">
                <label for="jabatan_id" class="form-label">Jabatan</label>
                <select id="jabatan_id" name="jabatan_id" class="form-select">
                    <!-- Options will be populated based on user selection -->
                </select>
            </div>
            <div class="mb-3">
                <label for="status" class="form-label">Status</label>
                <select id="status" name="status" class="form-select" required>
                    <option value="active" <?php echo ($status == 'active') ? 'selected' : ''; ?>>Active</option>
                    <option value="inactive" <?php echo ($status == 'inactive') ? 'selected' : ''; ?>>Inactive</option>
                </select>
            </div>
            <button type="submit" class="btn btn-primary">Add File</button>
            <a href="?page=files" class="btn btn-secondary">Cancel</a>
        </form>
    </div>

    <script>
    $(document).ready(function() {
        $('#user_id').change(function() {
            var userId = $(this).val();

            if (userId) {
                $.ajax({
                    url: 'get_user_data.php',
                    type: 'GET',
                    data: { user_id: userId },
                    dataType: 'json',
                    success: function(data) {
                        if (data) {
                            $('#divisi_id').empty();
                            $('#jabatan_id').empty();

                            // Populate divisi dropdown
                            $.each(data.divisi, function(index, item) {
                                $('#divisi_id').append(
                                    $('<option></option>').val(item.id).text(item.name)
                                );
                            });

                            // Populate jabatan dropdown
                            $.each(data.jabatan, function(index, item) {
                                $('#jabatan_id').append(
                                    $('<option></option>').val(item.id).text(item.name)
                                );
                            });
                        }
                    }
                });
            } else {
                $('#divisi_id').empty();
                $('#jabatan_id').empty();
            }
        });
    });
    </script>
</body>
</html>

<?php
// Close the database connection
$conn->close();
?>
