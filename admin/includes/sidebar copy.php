<!-- Main Sidebar Container -->
<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <a href="#" class="brand-link">
        <img src="<?php echo $web_logo; ?>" alt="Logo" class="brand-image img-circle elevation-3" style="opacity: .8">
        <span class="brand-text font-weight-light"><?php echo $web_name; ?></span>
    </a>
    <!-- Sidebar -->
    <div class="sidebar">
        <!-- Sidebar Menu -->
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                <?php
                // Determine the current page from the query string
                $current_page = isset($_GET['page']) ? $_GET['page'] : 'home';

                // Check user role (Assuming $_SESSION['role'] is set)
                $is_admin = isset($_SESSION['role']) && $_SESSION['role'] === 'admin';
                ?>
                <!-- Home Menu Item -->
                <li class="nav-item">
                    <a href="?page=home" class="nav-link <?php echo $current_page === 'home' ? 'active' : ''; ?>">
                        <i class="nav-icon fas fa-home"></i>
                        <p>Home</p>
                    </a>
                </li>
                <!-- About Menu Item -->
                <li class="nav-item">
                    <a href="?page=about" class="nav-link <?php echo $current_page === 'about' ? 'active' : ''; ?>">
                        <i class="nav-icon fas fa-info"></i>
                        <p>About</p>
                    </a>
                </li>

                <!-- Users Menu Item -->
                <li class="nav-item">
                    <a href="?page=users" class="nav-link <?php echo $current_page === 'users' ? 'active' : ''; ?>">
                        <i class="nav-icon fas fa-users"></i>
                        <p>Users</p>
                    </a>
                </li>
                
                <!-- Settings Menu Item -->
                <li class="nav-item">
                    <a href="?page=settings" class="nav-link <?php echo $current_page === 'settings' ? 'active' : ''; ?>">
                        <i class="nav-icon fas fa-cogs"></i>
                        <p>Settings</p>
                    </a>
                </li>
            </ul>
        </nav>
    </div>
</aside>
