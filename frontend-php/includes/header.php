<!DOCTYPE html>
<html lang="en" data-bs-theme="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Octal Foundry</title>

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@300;400;500;600;700&family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" rel="stylesheet" />

    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">

    <!-- Custom CSS -->
    <link href="css/style.css" rel="stylesheet">
</head>
<body class="bg-background-dark text-white font-display">
    <?php 
    // Determine if current page needs sidebar layout
    $dashboard_pages = ['dashboard.php', 'units.php', 'roadmap.php', 'skills.php', 'certificates.php', 'index.php', 'learning.php'];
    $current_page = basename($_SERVER['PHP_SELF']);
    $has_sidebar = in_array($current_page, $dashboard_pages);
    ?>

    <!-- App Layout Wrapper -->
    <div class="app-wrapper d-flex min-vh-100">

    <?php if ($has_sidebar): include 'includes/sidebar.php'; endif; ?>

    <!-- Main Content Area -->
    <div class="main-content flex-grow-1 <?php echo $has_sidebar ? 'with-sidebar' : ''; ?>">

    <?php if ($has_sidebar): ?>
    <!-- Top Bar for Dashboard Pages -->
    <header class="topbar sticky-top bg-background-dark glass-effect border-bottom border-secondary border-opacity-25 px-3 py-2 d-flex align-items-center justify-content-between">
        <!-- Mobile Menu Toggle -->
        <button id="sidebar-toggle" class="btn btn-link text-white p-2 d-lg-none">
            <span class="material-symbols-outlined">menu</span>
        </button>
        
        <!-- Page Title (Mobile) -->
        <h1 class="h6 fw-bold mb-0 d-lg-none">Octal Foundry</h1>
        
        <!-- Search Bar (Desktop) -->
        <div class="d-none d-lg-flex flex-grow-1 me-3" style="max-width: 400px;">
            <div class="input-group">
                <span class="input-group-text bg-transparent border-secondary border-opacity-25 text-secondary">
                    <span class="material-symbols-outlined" style="font-size: 20px;">search</span>
                </span>
                <input type="text" class="form-control form-control-dark border-secondary border-opacity-25" placeholder="Search units, topics...">
            </div>
        </div>
        
        <!-- Right Actions -->
        <div class="d-flex align-items-center gap-2">
            <button class="btn btn-link text-secondary p-2 position-relative">
                <span class="material-symbols-outlined">notifications</span>
                <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-primary-orange" style="font-size: 10px;">3</span>
            </button>
            <button class="btn btn-link text-secondary p-2 d-none d-lg-block">
                <span class="material-symbols-outlined">settings</span>
            </button>
        </div>
    </header>
    <?php endif; ?>

    <!-- Page Content Start -->
