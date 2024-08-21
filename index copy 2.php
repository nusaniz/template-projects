<?php
session_start();
// Include necessary files or settings
include 'config.php'; // Adjust the path as needed
include 'setup.php'; // Adjust the path as needed

// Define a default page
$page = 'verify';

// Check if a page parameter is set in the URL
if (isset($_GET['page'])) {
    $page = $_GET['page'];
}

// Define allowed pages to prevent directory traversal attacks
$allowed_pages = ['home', 'about', 'contact', 'verify']; // List of allowed page names

// Check if the requested page is in the allowed pages
if (in_array($page, $allowed_pages)) {
    $file = $page . '.php';
} else {
    $file = '404.php'; // Fallback to a 404 page if the page is not allowed
}

// Check if the user is logged in and retrieve role_name (You can adjust this as per your authentication logic)
$isLoggedIn = isset($_SESSION['user_id']);
$roleName = $isLoggedIn ? $_SESSION['role_name'] : null;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Website</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <style>
        .navbar-brand img {
            max-height: 40px; /* Adjust the logo size as needed */
        }
    </style>
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <div class="container">
            <a class="navbar-brand" href=".">
                <img src="assets/<?php echo htmlspecialchars($web_logo, ENT_QUOTES, 'UTF-8');?>" alt="Logo"> <!-- Replace with your logo path -->
                <?php echo htmlspecialchars($web_name, ENT_QUOTES, 'UTF-8');?>
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <!-- <li class="nav-item">
                        <a class="nav-link" href="?page=home">Home</a>
                    </li> -->
                    <li class="nav-item">
                        <a class="nav-link" href="?page=verify">Verify</a>
                    </li>
                    <!-- <li class="nav-item">
                        <a class="nav-link" href="?page=about">About</a>
                    </li> -->
                    <!-- <li class="nav-item">
                        <a class="nav-link" href="?page=contact">Contact</a>
                    </li> -->
                </ul>
                <ul class="navbar-nav ms-auto">
                    <?php if ($isLoggedIn): ?>
                        <?php if ($roleName === 'admin'): ?>
                            <li class="nav-item">
                                <a class="nav-link" href="admin/">Admin Dashboard</a>
                            </li>
                        <?php elseif ($roleName === 'user'): ?>
                            <li class="nav-item">
                                <a class="nav-link" href="user/">User Dashboard</a>
                            </li>
                        <?php endif; ?>
                        <li class="nav-item">
                            <a class="nav-link" href="logout/">Logout</a>
                        </li>
                    <?php else: ?>
                        <li class="nav-item">
                            <a class="nav-link" href="login/">Login</a>
                        </li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main role="main" class="container">
        <?php include $file; ?>
    </main>

    <!-- Footer -->
    <footer class="bg-light text-center py-3" style="position: absolute; bottom: 0; width: 100%;">
        <div class="container">
            <p class="mb-0">© <?php echo date('Y'); ?> <?php echo htmlspecialchars($web_name, ENT_QUOTES, 'UTF-8');?>. All rights reserved.</p>
        </div>
    </footer>

    <!-- Bootstrap JS and dependencies -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.7/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.min.js"></script>
</body>
</html>
