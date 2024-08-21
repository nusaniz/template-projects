<!-- Main Sidebar Container -->
<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <a href="#" class="brand-link">
        <img src="../assets/<?php echo $web_logo; ?>" alt="Logo" class="brand-image img-circle elevation-3" style="opacity: .8">
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
                <!-- Files Menu Item -->
                <li class="nav-item">
                    <a href="?page=files" class="nav-link <?php echo $current_page === 'files' ? 'active' : ''; ?>">
                        <i class="nav-icon fas fa-file"></i>
                        <p>Files</p>
                    </a>
                </li>
                <!-- Profile Menu Item -->
                <li class="nav-item">
                    <a href="?page=profile" class="nav-link <?php echo $current_page === 'profile' ? 'active' : ''; ?>">
                        <i class="nav-icon fas fa-user"></i>
                        <p>Profile</p>
                    </a>
                </li>
                <!-- Guide Menu Item -->
                <li class="nav-item">
                    <a href="?page=guide" class="nav-link <?php echo $current_page === 'guide' ? 'active' : ''; ?>">
                        <i class="nav-icon fas fa-book"></i>
                        <p>Guide</p>
                    </a>
                </li>
            </ul>
        </nav>
    </div>
</aside>
