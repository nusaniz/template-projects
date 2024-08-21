<?php
// session_start();
include '../config.php';  // Adjusted path to config.php

// Check if the user is logged in and has the admin role
if (!isset($_SESSION['user_id']) || $_SESSION['role_name'] !== 'admin') {
    header("Location: ../login/");
    exit();
}

$fileId = isset($_GET['id']) ? (int)$_GET['id'] : 0;

// Fetch the existing file data
if ($fileId > 0) {
    $fileQuery = "
        SELECT 
            f.id, 
            f.user_id, 
            f.file_name, 
            f.file, 
            f.file_hash,
            f.divisi_id, 
            f.jabatan_id, 
            f.status,
            f.description  -- Added description
        FROM tb_file f
        WHERE f.id = $fileId
    ";
    $fileResult = mysqli_query($conn, $fileQuery);

    if ($fileResult && mysqli_num_rows($fileResult) > 0) {
        $fileData = mysqli_fetch_assoc($fileResult);
        $userId = $fileData['user_id']; // Get user_id to fetch related divisi and jabatan
    } else {
        die("File not found.");
    }
} else {
    die("Invalid file ID.");
}

// Fetch users for dropdown
$userQuery = "SELECT id, `full_name` FROM tb_users";
$userResult = mysqli_query($conn, $userQuery);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $web_name;?> | Edit File</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>
    <div class="container pt-4 pb-4">
        <h3 class="mb-4">Edit File</h3>

        <form method="post" enctype="multipart/form-data">
            <div class="mb-3">
                <label for="user_id" class="form-label">User</label>
                <select name="user_id" id="user_id" class="form-select" required>
                    <?php while ($userRow = mysqli_fetch_assoc($userResult)): ?>
                    <option value="<?php echo htmlspecialchars($userRow['id']); ?>" <?php echo ($userRow['id'] == $fileData['user_id']) ? 'selected' : ''; ?>>
                        <?php echo htmlspecialchars($userRow['full_name']); ?>
                    </option>
                    <?php endwhile; ?>
                </select>
            </div>

            <div class="mb-3">
                <label for="file_name" class="form-label">File Name</label>
                <input type="text" name="file_name" id="file_name" class="form-control" value="<?php echo htmlspecialchars($fileData['file_name']); ?>" required>
            </div>

            <div class="mb-3">
                <label for="file" class="form-label">File</label>
                <input type="file" name="file" id="file" class="form-control">
                <input type="hidden" name="current_file" value="<?php echo htmlspecialchars($fileData['file']); ?>">
                <?php if (!empty($fileData['file'])): ?>
                <p>Current file: <?php echo htmlspecialchars($fileData['file']); ?></p>
                <?php endif; ?>
            </div>

            <div class="mb-3">
                <label for="description" class="form-label">Description</label>
                <textarea name="description" id="description" class="form-control" rows="4"><?php echo htmlspecialchars($fileData['description']); ?></textarea>
            </div>

            <div class="mb-3">
                <label for="divisi_id" class="form-label">Divisi</label>
                <select name="divisi_id" id="divisi_id" class="form-select" required>
                    <!-- Options will be populated by AJAX -->
                </select>
            </div>

            <div class="mb-3">
                <label for="jabatan_id" class="form-label">Jabatan</label>
                <select name="jabatan_id" id="jabatan_id" class="form-select" required>
                    <!-- Options will be populated by AJAX -->
                </select>
            </div>

            <div class="mb-3">
                <label for="status" class="form-label">Status</label>
                <input type="text" name="status" id="status" class="form-control" value="<?php echo htmlspecialchars($fileData['status']); ?>" required>
            </div>

            <button type="submit" class="btn btn-primary">Update File</button>
            <a href="?page=files" class="btn btn-secondary">Cancel</a>
        </form>
    </div>

    <script>
        $(document).ready(function() {
            // Function to load divisi and jabatan based on selected user
            function loadUserData(userId) {
                $.ajax({
                    url: 'get_user_data.php',
                    type: 'GET',
                    data: { user_id: userId },
                    dataType: 'json',
                    success: function(data) {
                        if (data.error) {
                            alert(data.error);
                        } else {
                            // Populate divisi dropdown
                            var divisiOptions = data.divisi.map(function(item) {
                                return `<option value="${item.id}" ${item.selected ? 'selected' : ''}>${item.name}</option>`;
                            }).join('');
                            $('#divisi_id').html(divisiOptions);

                            // Populate jabatan dropdown
                            var jabatanOptions = data.jabatan.map(function(item) {
                                return `<option value="${item.id}" ${item.selected ? 'selected' : ''}>${item.name}</option>`;
                            }).join('');
                            $('#jabatan_id').html(jabatanOptions);
                        }
                    }
                });
            }

            // Trigger AJAX call on user_id change
            $('#user_id').change(function() {
                var userId = $(this).val();
                loadUserData(userId);
            });

            // Initial load for selected user
            loadUserData($('#user_id').val());
        });
    </script>
</body>
</html>

<?php
// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $userId = mysqli_real_escape_string($conn, $_POST['user_id']);
    $fileName = mysqli_real_escape_string($conn, $_POST['file_name']);
    $divisiId = mysqli_real_escape_string($conn, $_POST['divisi_id']);
    $jabatanId = mysqli_real_escape_string($conn, $_POST['jabatan_id']);
    $status = mysqli_real_escape_string($conn, $_POST['status']);
    $description = mysqli_real_escape_string($conn, $_POST['description']);  // Get description

    // Handle file upload
    $file = $_FILES['file'];
    $currentFile = $_POST['current_file'];
    $fileBaseName = $currentFile;
    $fileHash = '';

    if ($file['error'] == UPLOAD_ERR_OK) {
        $uploadDir = '../uploads/';
        
        // Generate a unique prefix
        $uniquePrefix = substr(uniqid(time(), true), 0, 10);
        $fileBaseName = $uniquePrefix . '_' . basename($file['name']); // Rename file with prefix
        $filePath = $uploadDir . $fileBaseName;

        // Move the uploaded file to the server
        if (move_uploaded_file($file['tmp_name'], $filePath)) {
            // Calculate the SHA-256 hash of the uploaded file
            $fileHash = hash_file('sha256', $filePath);

            // Remove old file if it exists
            if ($currentFile && file_exists($uploadDir . $currentFile)) {
                unlink($uploadDir . $currentFile);
            }
        } else {
            echo "Error uploading file.";
            exit();
        }
    } else {
        // Use existing file hash if no new file uploaded
        $fileBaseName = $currentFile;
        $fileHash = mysqli_real_escape_string($conn, $fileData['file_hash']);
    }

    $updateQuery = "
        UPDATE tb_file 
        SET 
            user_id = '$userId', 
            file_name = '$fileName', 
            file = '$fileBaseName',  -- Only base name here
            file_hash = '$fileHash', 
            divisi_id = '$divisiId', 
            jabatan_id = '$jabatanId', 
            status = '$status',
            description = '$description'  -- Update description
        WHERE id = $fileId
    ";

    if (mysqli_query($conn, $updateQuery)) {
        // Redirect to files list page
        echo "<script>window.location.href = '?page=files';</script>";
        exit();
    } else {
        echo "Error updating file: " . mysqli_error($conn);
    }
}

// Close the database connection
$conn->close();
?>
