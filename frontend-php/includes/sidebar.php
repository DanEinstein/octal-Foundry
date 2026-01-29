<!-- Left Sidebar Navigation -->
<aside id="sidebar" class="sidebar bg-background-darker border-end border-secondary border-opacity-25">
    <!-- Brand Logo -->
    <div class="sidebar-header p-3 border-bottom border-secondary border-opacity-25">
        <a href="dashboard.php" class="text-decoration-none d-flex align-items-center gap-2">
            <div class="bg-primary-orange rounded-2 p-2 d-flex align-items-center justify-content-center">
                <span class="material-symbols-outlined text-white">hub</span>
            </div>
            <span class="sidebar-brand fw-bold text-white">Octal Foundry</span>
        </a>
    </div>

    <!-- Navigation Links -->
    <nav class="sidebar-nav p-3">
        <ul class="nav flex-column gap-1">
            <li class="nav-item">
                <a href="dashboard.php" class="nav-link sidebar-link d-flex align-items-center gap-3 px-3 py-2 rounded-3 <?php echo basename($_SERVER['PHP_SELF']) == 'dashboard.php' ? 'active' : ''; ?>">
                    <span class="material-symbols-outlined">dashboard</span>
                    <span class="sidebar-text">Dashboard</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="units.php" class="nav-link sidebar-link d-flex align-items-center gap-3 px-3 py-2 rounded-3 <?php echo basename($_SERVER['PHP_SELF']) == 'units.php' ? 'active' : ''; ?>">
                    <span class="material-symbols-outlined">menu_book</span>
                    <span class="sidebar-text">My Units</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="roadmap.php" class="nav-link sidebar-link d-flex align-items-center gap-3 px-3 py-2 rounded-3 <?php echo basename($_SERVER['PHP_SELF']) == 'roadmap.php' ? 'active' : ''; ?>">
                    <span class="material-symbols-outlined">route</span>
                    <span class="sidebar-text">Roadmap</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="skills.php" class="nav-link sidebar-link d-flex align-items-center gap-3 px-3 py-2 rounded-3 <?php echo basename($_SERVER['PHP_SELF']) == 'skills.php' ? 'active' : ''; ?>">
                    <span class="material-symbols-outlined">radar</span>
                    <span class="sidebar-text">Skills Radar</span>
                </a>
            </li>
        </ul>

        <!-- Divider -->
        <hr class="border-secondary border-opacity-25 my-3">

        <!-- Secondary Links -->
        <ul class="nav flex-column gap-1">
            <li class="nav-item">
                <a href="index.php" class="nav-link sidebar-link d-flex align-items-center gap-3 px-3 py-2 rounded-3 <?php echo basename($_SERVER['PHP_SELF']) == 'index.php' ? 'active' : ''; ?>">
                    <span class="material-symbols-outlined">person</span>
                    <span class="sidebar-text">Portfolio</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="learning.php" class="nav-link sidebar-link d-flex align-items-center gap-3 px-3 py-2 rounded-3 <?php echo basename($_SERVER['PHP_SELF']) == 'learning.php' ? 'active' : ''; ?>">
                    <span class="material-symbols-outlined">school</span>
                    <span class="sidebar-text">Learning</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="certificates.php" class="nav-link sidebar-link d-flex align-items-center gap-3 px-3 py-2 rounded-3 <?php echo basename($_SERVER['PHP_SELF']) == 'certificates.php' ? 'active' : ''; ?>">
                    <span class="material-symbols-outlined">workspace_premium</span>
                    <span class="sidebar-text">Certificates</span>
                </a>
            </li>
        </ul>
    </nav>

    <!-- User Profile Section at Bottom -->
    <div class="sidebar-footer mt-auto p-3 border-top border-secondary border-opacity-25">
        <div class="d-flex align-items-center gap-3">
            <div class="rounded-circle bg-primary-blue d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                <span class="material-symbols-outlined text-white">person</span>
            </div>
            <div class="sidebar-user-info flex-grow-1">
                <p class="mb-0 small fw-bold text-white">John Kamau</p>
                <p class="mb-0 text-secondary" style="font-size: 11px;">UoN - Year 3</p>
            </div>
            <a href="login.php" class="text-secondary">
                <span class="material-symbols-outlined">logout</span>
            </a>
        </div>
    </div>
</aside>

<!-- Mobile Sidebar Overlay -->
<div id="sidebar-overlay" class="sidebar-overlay d-lg-none"></div>
