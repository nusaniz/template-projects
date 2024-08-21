<!-- Navbar -->
<nav class="main-header navbar navbar-expand navbar-light">
    <!-- First UL for existing menu items -->
    <ul class="navbar-nav">
        <!-- Existing menu item -->
        <li class="nav-item">
            <a class="nav-link" data-widget="pushmenu" href="#" role="button">
                <i class="fas fa-bars"></i>
            </a>
        </li>
    </ul>

    <!-- Second UL for user info and logout item -->
    <ul class="navbar-nav ml-auto">
        <!-- Display username -->
        <li class="nav-item">
            <a class="nav-link" href="#" role="button">
                <i class="fas fa-user"></i> 
                <?php
                // Check if username is set in session
                if (isset($_SESSION['username'])) {
                    echo htmlspecialchars($_SESSION['username']); // Output username
                } else {
                    echo 'Guest'; // Fallback if username is not set
                }
                ?>
            </a>
        </li>
        <!-- Logout item -->
        <li class="nav-item">
            <a class="nav-link" href="../logout/" role="button">
                <i class="fas fa-sign-out-alt"></i> Logout
            </a>
        </li>
    </ul>

    
    <!-- Second UL for logout item -->
    <!-- <ul class="navbar-nav ml-auto"> -->
        <!-- Logout item -->
        <!-- <li class="nav-item">
            <a class="nav-link" href="../logout.php" role="button">
                <i class="fas fa-sign-out-alt"></i> Logout
            </a>
        </li>
    </ul> -->
</nav>
