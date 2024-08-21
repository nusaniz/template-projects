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

                // Check user role (Assuming $_SESSION['role'] is set)
                $is_admin = isset($_SESSION['role']) && $_SESSION['role'] === 'admin';

                // Check if any of the Keuangan subpages are active
                $is_keuangan_active = in_array($current_page, ['tagihan', 'tagihan_add_all_v2', 'tagihan_pay', 'jenis_tagihan', 'metode_pembayaran', 'payments']);
                // Check if any of the Users Management subpages are active
                $is_users_management_active = in_array($current_page, ['users', 'jabatans', 'roles', 'divisi', 'jabatan']);
                // Check if any of the E-Books subpages are active
                $is_ebooks_active = in_array($current_page, ['ebooks', 'ebooks_add']);
                // Check if any of the Files subpages are active
                $is_files_active = in_array($current_page, ['files', 'files_add', 'files_edit', 'files_detail', 'files_delete', 'file_verification']);
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

                <!-- Files Menu Item with Submenus -->
                <li class="nav-item <?php echo $is_files_active ? 'menu-open' : ''; ?>">
                    <a href="#" class="nav-link <?php echo $is_files_active ? 'active' : ''; ?>">
                        <i class="nav-icon fas fa-file"></i>
                        <p>
                            Files
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <!-- Files List Submenu Item -->
                        <li class="nav-item">
                            <a href="?page=files" class="nav-link <?php echo $current_page === 'files' ? 'active' : ''; ?>">
                                <i class="nav-icon fas fa-list"></i>
                                <p>Files List</p>
                            </a>
                        </li>
                        <!-- Add File Submenu Item -->
                        <li class="nav-item">
                            <a href="?page=files_add" class="nav-link <?php echo $current_page === 'files_add' ? 'active' : ''; ?>">
                                <i class="nav-icon fas fa-plus"></i>
                                <p>Add New File</p>
                            </a>
                        </li>
                        <!-- File Verification Submenu Item -->
                        <li class="nav-item">
                            <a href="?page=file_verification" class="nav-link <?php echo $current_page === 'file_verification' ? 'active' : ''; ?>">
                                <i class="nav-icon fas fa-check"></i>
                                <p>File Verification</p>
                            </a>
                        </li>
                    </ul>
                </li>

                <!-- Users Management Menu Item with Submenus -->
                <li class="nav-item <?php echo $is_users_management_active ? 'menu-open' : ''; ?>">
                    <a href="#" class="nav-link <?php echo $is_users_management_active ? 'active' : ''; ?>">
                        <i class="nav-icon fas fa-users-cog"></i>
                        <p>
                            Users Management
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <!-- Users Submenu Item -->
                        <li class="nav-item">
                            <a href="?page=users" class="nav-link <?php echo $current_page === 'users' ? 'active' : ''; ?>">
                                <i class="nav-icon fas fa-users"></i>
                                <p>Users</p>
                            </a>
                        </li>
                        <!-- Roles Submenu Item -->
                        <li class="nav-item">
                            <a href="?page=roles" class="nav-link <?php echo $current_page === 'roles' ? 'active' : ''; ?>">
                                <i class="nav-icon fas fa-user-tag"></i>
                                <p>Roles</p>
                            </a>
                        </li>
                        <!-- Divisi Submenu Item -->
                        <li class="nav-item">
                            <a href="?page=divisi" class="nav-link <?php echo $current_page === 'divisi' ? 'active' : ''; ?>">
                                <i class="nav-icon fas fa-building"></i>
                                <p>Divisi</p>
                            </a>
                        </li>
                        <!-- Jabatan Submenu Item -->
                        <li class="nav-item">
                            <a href="?page=jabatan" class="nav-link <?php echo $current_page === 'jabatan' ? 'active' : ''; ?>">
                                <i class="nav-icon fas fa-briefcase"></i>
                                <p>Jabatan</p>
                            </a>
                        </li>
                    </ul>
                </li>

                <!-- Settings Menu Item -->
                <li class="nav-item">
                    <a href="?page=settings" class="nav-link <?php echo $current_page === 'settings' ? 'active' : ''; ?>">
                        <i class="nav-icon fas fa-cogs"></i>
                        <p>Settings</p>
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
