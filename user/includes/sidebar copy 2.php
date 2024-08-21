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
                
                <!-- Roles Menu Item -->
                <li class="nav-item">
                    <a href="?page=roles" class="nav-link <?php echo $current_page === 'roles' ? 'active' : ''; ?>">
                        <i class="nav-icon fas fa-user-tag"></i>
                        <p>Roles</p>
                    </a>
                </li>

                <!-- Jabatans Menu Item -->
                <li class="nav-item">
                    <a href="?page=jabatans" class="nav-link <?php echo $current_page === 'jabatans' ? 'active' : ''; ?>">
                        <i class="nav-icon fas fa-briefcase"></i>
                        <p>Jabatans</p>
                    </a>
                </li>

                <!-- Settings Menu Item -->
                <li class="nav-item">
                    <a href="?page=settings" class="nav-link <?php echo $current_page === 'settings' ? 'active' : ''; ?>">
                        <i class="nav-icon fas fa-cogs"></i>
                        <p>Settings</p>
                    </a>
                </li>

                <!-- Keuangan Menu Item with Submenus -->
                <li class="nav-item <?php echo in_array($current_page, ['tagihan', 'tagihan_add_all_v2', 'tagihan_pay']) ? 'menu-open' : ''; ?>">
                    <a href="#" class="nav-link <?php echo in_array($current_page, ['tagihan', 'tagihan_add_all_v2', 'tagihan_pay']) ? 'active' : ''; ?>">
                        <i class="nav-icon fas fa-wallet"></i>
                        <p>
                            Keuangan
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <!-- Tagihan Submenu Item -->
                        <li class="nav-item">
                            <a href="?page=tagihan" class="nav-link <?php echo $current_page === 'tagihan' ? 'active' : ''; ?>">
                                <i class="nav-icon fas fa-file-invoice"></i>
                                <p>Tagihan</p>
                            </a>
                        </li>
                        <!-- Tagihan Add All V2 Submenu Item -->
                        <li class="nav-item">
                            <a href="?page=tagihan_add_all_v2" class="nav-link <?php echo $current_page === 'tagihan_add_all_v2' ? 'active' : ''; ?>">
                                <i class="nav-icon fas fa-plus-circle"></i>
                                <p>Tagihan Add All V2</p>
                            </a>
                        </li>
                        <!-- Tagihan Pay Submenu Item -->
                        <li class="nav-item">
                            <a href="?page=tagihan_pay" class="nav-link <?php echo $current_page === 'tagihan_pay' ? 'active' : ''; ?>">
                                <i class="nav-icon fas fa-dollar-sign"></i>
                                <p>Tagihan Pay</p>
                            </a>
                        </li>
                    </ul>
                </li>

                <!-- Agenda Menu Item -->
                <li class="nav-item">
                    <a href="?page=agenda" class="nav-link <?php echo $current_page === 'agenda' ? 'active' : ''; ?>">
                        <i class="nav-icon fas fa-calendar"></i>
                        <p>Agenda</p>
                    </a>
                </li>

                <!-- Jenis Tagihan Menu Item -->
                <li class="nav-item">
                    <a href="?page=jenis_tagihan" class="nav-link <?php echo $current_page === 'jenis_tagihan' ? 'active' : ''; ?>">
                        <i class="nav-icon fas fa-tag"></i>
                        <p>Jenis Tagihan</p>
                    </a>
                </li>

                <!-- Metode Pembayaran Menu Item -->
                <li class="nav-item">
                    <a href="?page=metode_pembayaran" class="nav-link <?php echo $current_page === 'metode_pembayaran' ? 'active' : ''; ?>">
                        <i class="nav-icon fas fa-credit-card"></i>
                        <p>Metode Pembayaran</p>
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
