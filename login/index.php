<?php
session_start();
include '../config.php'; // Include database connection

// Check if the user is already logged in
if (isset($_SESSION['user_id'])) {
    // Redirect based on role_name if user is already logged in
    if ($_SESSION['role_name'] === 'admin') {
        header("Location: ../admin/");
        exit();
    } elseif ($_SESSION['role_name'] === 'user') {
        header("Location: ../user/");
        exit();
    } else {
        echo "Unrecognized role.";
        exit();
    }
}

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve and sanitize user input
    $username = trim($_POST['username']);
    $password = trim($_POST['password']); // Added password input

    // Prepare and execute SQL statement to get user details with joined tables
    $stmt = $conn->prepare("
        SELECT 
            u.id, 
            u.username, 
            u.`full_name`, 
            u.role_id, 
            r.name AS role_name, 
            u.divisi_id, 
            d.name AS divisi_name, 
            u.jabatan_id, 
            j.name AS jabatan_name, 
            u.password  -- Include the password field
        FROM 
            tb_users u
        LEFT JOIN 
            tb_role r ON u.role_id = r.id
        LEFT JOIN 
            tb_divisi d ON u.divisi_id = d.id
        LEFT JOIN 
            tb_jabatan j ON u.jabatan_id = j.id
        WHERE 
            u.username = ?
    ");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();
    
    // Check if user exists
    if ($result->num_rows == 1) {
        $user = $result->fetch_assoc();
        
        // Check if the provided password matches
        if ($password === $user['password']) {
            // Set session variables
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['full_name'] = $user['full_name'];
            $_SESSION['role_id'] = $user['role_id'];
            $_SESSION['role_name'] = $user['role_name'];
            $_SESSION['divisi_id'] = $user['divisi_id'];
            $_SESSION['divisi_name'] = $user['divisi_name'];
            $_SESSION['jabatan_id'] = $user['jabatan_id'];
            $_SESSION['jabatan_name'] = $user['jabatan_name'];
            
            // Redirect based on role_name
            if ($user['role_name'] === 'admin') {
                header("Location: ../admin/");
            } elseif ($user['role_name'] === 'user') {
                header("Location: ../user/");
            } else {
                echo "Unrecognized role.";
            }
            exit();
        } else {
            // Authentication failed
            $error = "Invalid username or password.";
        }
    } else {
        // User does not exist
        $error = "Invalid username or password.";
    }
    
    // Close the statement
    $stmt->close();
}

// Close the database connection
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-...your-integrity-hash..." crossorigin="anonymous">
</head>
<body>
    <div class="container d-flex justify-content-center align-items-center vh-100">
        <div class="card" style="width: 100%; max-width: 400px;">
            <div class="card-body">
                <h5 class="card-title text-center mb-4">Login</h5>
                <?php if (isset($error)): ?>
                    <div class="alert alert-danger" role="alert">
                        <?php echo htmlspecialchars($error); ?>
                    </div>
                <?php endif; ?>
                <form method="post" action="">
                    <div class="mb-3">
                        <label for="username" class="form-label">Username</label>
                        <input type="text" class="form-control" id="username" name="username" required>
                    </div>
                    <div class="mb-3">
                        <label for="password" class="form-label">Password</label>
                        <input type="password" class="form-control" id="password" name="password" required>
                    </div>
                    <button type="submit" class="btn btn-primary w-100">Login</button>
                </form>
            </div>
        </div>
    </div>
    <!-- Bootstrap JS and dependencies -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js" integrity="sha384-oBqDVmMz4fnFO9shlJ3H8C0G3IH6S3p3Rk5lbzXZcB5N4Z6kD5xZXKZR7ZlM1J0g" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.min.js" integrity="sha384-...your-integrity-hash..." crossorigin="anonymous"></script>
</body>
</html>
