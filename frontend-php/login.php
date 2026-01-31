<?php
/**
 * Login Page
 * Handles user authentication and registration
 */

require_once 'includes/auth.php';

initSession();

// Redirect if already logged in
if (isLoggedIn()) {
    header('Location: dashboard.php');
    exit;
}

$error = null;
$success = null;
$activeTab = $_GET['tab'] ?? 'login';

// Handle form submissions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    
    // Validate CSRF token
    if (!validateCsrfToken($_POST['csrf_token'] ?? '')) {
        $error = 'Security validation failed. Please try again.';
    } else {
        if ($action === 'login') {
            // Login
            $email = trim($_POST['email'] ?? '');
            $password = $_POST['password'] ?? '';
            
            if (empty($email) || empty($password)) {
                $error = 'Please enter both email and password';
            } else {
                $result = loginUser($email, $password);
                if ($result['success']) {
                    header('Location: dashboard.php');
                    exit;
                } else {
                    $error = $result['error'];
                }
            }
        } elseif ($action === 'register') {
            // Registration
            $fullName = trim($_POST['full_name'] ?? '');
            $email = trim($_POST['email'] ?? '');
            $password = $_POST['password'] ?? '';
            $confirmPassword = $_POST['confirm_password'] ?? '';
            $university = trim($_POST['university'] ?? 'University of Nairobi');
            
            if (empty($fullName) || empty($email) || empty($password)) {
                $error = 'Please fill in all required fields';
                $activeTab = 'register';
            } elseif ($password !== $confirmPassword) {
                $error = 'Passwords do not match';
                $activeTab = 'register';
            } else {
                $result = registerUser($fullName, $email, $password, $university);
                if ($result['success']) {
                    header('Location: dashboard.php');
                    exit;
                } else {
                    $error = $result['error'];
                    $activeTab = 'register';
                }
            }
        }
    }
}

include 'includes/header.php';
?>

<!-- Top Navigation Bar -->
<div class="d-flex align-items-center p-4 pb-2 justify-content-between z-1">
    <a href="#" class="text-white d-flex align-items-center justify-content-center text-decoration-none" style="width: 48px; height: 48px;">
        <span class="material-symbols-outlined">arrow_back_ios</span>
    </a>
    <h2 class="text-white fs-5 fw-bold m-0 flex-grow-1 text-center pe-5">Octal Foundry</h2>
</div>

<div class="flex-grow-1 d-flex flex-column">
    <!-- Hero Section -->
    <div class="px-4 py-2">
        <div class="w-100 rounded-xl shadow-lg position-relative d-flex flex-column justify-content-end overflow-hidden"
             style="background-image: url('https://lh3.googleusercontent.com/aida-public/AB6AXuCu1gvtJtp6LDXgLu1oinCDuOosBzrj5MP7uqa5Gv3haKf5EP_ZHAKTYPuE-amBYWeWt4c9vzWu8aeOeVrVIA2aG_9o_dj38DDOGEPA5bleW5-2NHqWSZB3_dCelAsrrs7fVhQKDypcpBK09vEw1mZCpw9oZApb12t-nm7oEa57RatfGmxHtunPv_XNECgZ3SrFZcU8_Q0inFceSPHW0gaFhVTptDyKScPldybWUV2AFCrixyonbxPqEnJlOQ_15tZsm73tmXtdfcw'); background-size: cover; background-position: center; min-height: 200px;">
            <div class="position-absolute top-0 start-0 w-100 h-100" style="background: linear-gradient(to top, rgba(18,18,18,0.8), transparent);"></div>
            <div class="position-relative z-2 p-4">
                <h1 class="text-white fw-bold display-6 mb-0"><?php echo $activeTab === 'register' ? 'Join the Forge' : 'Enter the Forge'; ?></h1>
                <p class="text-secondary small mt-1 mb-0"><?php echo $activeTab === 'register' ? 'Create your account' : 'Start building with Octal Foundry'; ?></p>
            </div>
        </div>
    </div>

    <!-- Error/Success Messages -->
    <?php if ($error): ?>
    <div class="px-4 pt-3">
        <div class="alert alert-danger alert-dismissible fade show py-2 rounded-3" role="alert">
            <span class="material-symbols-outlined me-2" style="font-size: 1.25rem; vertical-align: middle;">error</span>
            <?php echo htmlspecialchars($error); ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    </div>
    <?php endif; ?>

    <?php if ($success): ?>
    <div class="px-4 pt-3">
        <div class="alert alert-success alert-dismissible fade show py-2 rounded-3" role="alert">
            <span class="material-symbols-outlined me-2" style="font-size: 1.25rem; vertical-align: middle;">check_circle</span>
            <?php echo htmlspecialchars($success); ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    </div>
    <?php endif; ?>

    <!-- Tab Navigation -->
    <div class="px-4 pt-3">
        <div class="d-flex bg-card-dark rounded-3 p-1">
            <a href="?tab=login" class="btn flex-fill py-2 rounded-3 fw-bold small <?php echo $activeTab === 'login' ? 'bg-primary-orange text-white' : 'text-secondary'; ?>">
                Sign In
            </a>
            <a href="?tab=register" class="btn flex-fill py-2 rounded-3 fw-bold small <?php echo $activeTab === 'register' ? 'bg-primary-orange text-white' : 'text-secondary'; ?>">
                Register
            </a>
        </div>
    </div>

    <?php if ($activeTab === 'login'): ?>
    <!-- Login Form -->
    <form method="POST" action="login.php" class="d-flex flex-column px-4 py-4 gap-3">
        <input type="hidden" name="action" value="login">
        <?php csrfField(); ?>
        
        <!-- Email Field -->
        <div class="d-flex flex-column gap-1">
            <p class="text-secondary small fw-bold text-uppercase px-1 mb-1" style="font-size: 0.75rem; letter-spacing: 0.05em;">University Email</p>
            <div class="position-relative">
                <span class="material-symbols-outlined position-absolute top-50 translate-middle-y text-secondary" style="left: 1rem;">alternate_email</span>
                <input type="email" name="email" class="form-control form-control-dark rounded-3 ps-5 py-3" placeholder="student@university.edu" required>
            </div>
        </div>

        <!-- Password Field -->
        <div class="d-flex flex-column gap-1">
            <div class="d-flex justify-content-between align-items-center px-1">
                <p class="text-secondary small fw-bold text-uppercase mb-1" style="font-size: 0.75rem; letter-spacing: 0.05em;">Password</p>
                <button type="button" class="btn btn-link text-primary-orange text-decoration-none p-0 small fw-bold" style="font-size: 0.75rem;">Forgot?</button>
            </div>
            <div class="position-relative">
                <span class="material-symbols-outlined position-absolute top-50 translate-middle-y text-secondary" style="left: 1rem;">lock</span>
                <input type="password" name="password" class="form-control form-control-dark rounded-3 ps-5 py-3" placeholder="Enter your password" required>
            </div>
        </div>

        <!-- Remember Me -->
        <div class="d-flex align-items-center py-2 px-1">
            <div class="form-check form-switch">
                <input class="form-check-input bg-secondary border-0" type="checkbox" role="switch" id="rememberSwitch" checked style="width: 3rem; height: 1.5rem;">
                <label class="form-check-label text-secondary ms-2 small fw-bold" for="rememberSwitch">Remember for 30 days</label>
            </div>
        </div>

        <!-- Primary Login Button -->
        <button type="submit" class="btn bg-primary-orange text-white fw-bold py-3 rounded-3 d-flex align-items-center justify-content-center gap-2 glow-orange border-0 shadow-lg w-100">
            <span>IGNITE ACCESS</span>
            <span class="material-symbols-outlined filled">bolt</span>
        </button>

        <!-- Divider -->
        <div class="d-flex align-items-center py-3">
            <div class="flex-grow-1 border-top border-secondary opacity-25"></div>
            <span class="mx-3 text-secondary xsmall fw-bold" style="font-size: 0.75rem;">OR JOIN WITH</span>
            <div class="flex-grow-1 border-top border-secondary opacity-25"></div>
        </div>

        <!-- Social Login -->
        <button type="button" class="btn btn-outline-secondary text-white fw-medium py-3 rounded-3 d-flex align-items-center justify-content-center gap-3 w-100 border-opacity-25 bg-transparent hover-bg-dark">
            <svg width="20" height="20" viewBox="0 0 24 24">
                <path d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z" fill="#4285F4"></path>
                <path d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z" fill="#34A853"></path>
                <path d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z" fill="#FBBC05"></path>
                <path d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z" fill="#EA4335"></path>
            </svg>
            Google Education
        </button>

        <!-- Footer Link -->
        <div class="pt-3 pb-4 text-center">
            <p class="text-secondary small">
                New to the Foundry?
                <a href="?tab=register" class="text-primary-orange fw-bold text-decoration-none">Join the cohort</a>
            </p>
        </div>
    </form>

    <?php else: ?>
    <!-- Registration Form -->
    <form method="POST" action="login.php" class="d-flex flex-column px-4 py-4 gap-3">
        <input type="hidden" name="action" value="register">
        <?php csrfField(); ?>
        
        <!-- Full Name Field -->
        <div class="d-flex flex-column gap-1">
            <p class="text-secondary small fw-bold text-uppercase px-1 mb-1" style="font-size: 0.75rem; letter-spacing: 0.05em;">Full Name *</p>
            <div class="position-relative">
                <span class="material-symbols-outlined position-absolute top-50 translate-middle-y text-secondary" style="left: 1rem;">person</span>
                <input type="text" name="full_name" class="form-control form-control-dark rounded-3 ps-5 py-3" placeholder="John Kamau" required>
            </div>
        </div>

        <!-- Email Field -->
        <div class="d-flex flex-column gap-1">
            <p class="text-secondary small fw-bold text-uppercase px-1 mb-1" style="font-size: 0.75rem; letter-spacing: 0.05em;">University Email *</p>
            <div class="position-relative">
                <span class="material-symbols-outlined position-absolute top-50 translate-middle-y text-secondary" style="left: 1rem;">alternate_email</span>
                <input type="email" name="email" class="form-control form-control-dark rounded-3 ps-5 py-3" placeholder="student@uon.ac.ke" required>
            </div>
        </div>

        <!-- University Field -->
        <div class="d-flex flex-column gap-1">
            <p class="text-secondary small fw-bold text-uppercase px-1 mb-1" style="font-size: 0.75rem; letter-spacing: 0.05em;">University</p>
            <div class="position-relative">
                <span class="material-symbols-outlined position-absolute top-50 translate-middle-y text-secondary" style="left: 1rem;">school</span>
                <select name="university" class="form-select form-control-dark rounded-3 ps-5 py-3">
                    <option value="University of Nairobi">University of Nairobi</option>
                    <option value="Kenyatta University">Kenyatta University</option>
                    <option value="Jomo Kenyatta University">Jomo Kenyatta University</option>
                    <option value="Strathmore University">Strathmore University</option>
                    <option value="Moi University">Moi University</option>
                    <option value="Other">Other</option>
                </select>
            </div>
        </div>

        <!-- Password Field -->
        <div class="d-flex flex-column gap-1">
            <p class="text-secondary small fw-bold text-uppercase px-1 mb-1" style="font-size: 0.75rem; letter-spacing: 0.05em;">Password *</p>
            <div class="position-relative">
                <span class="material-symbols-outlined position-absolute top-50 translate-middle-y text-secondary" style="left: 1rem;">lock</span>
                <input type="password" name="password" class="form-control form-control-dark rounded-3 ps-5 py-3" placeholder="Minimum 8 characters" minlength="8" required>
            </div>
        </div>

        <!-- Confirm Password Field -->
        <div class="d-flex flex-column gap-1">
            <p class="text-secondary small fw-bold text-uppercase px-1 mb-1" style="font-size: 0.75rem; letter-spacing: 0.05em;">Confirm Password *</p>
            <div class="position-relative">
                <span class="material-symbols-outlined position-absolute top-50 translate-middle-y text-secondary" style="left: 1rem;">lock</span>
                <input type="password" name="confirm_password" class="form-control form-control-dark rounded-3 ps-5 py-3" placeholder="Re-enter password" minlength="8" required>
            </div>
        </div>

        <!-- Primary Register Button -->
        <button type="submit" class="btn bg-primary-orange text-white fw-bold py-3 rounded-3 d-flex align-items-center justify-content-center gap-2 glow-orange border-0 shadow-lg w-100 mt-2">
            <span>CREATE ACCOUNT</span>
            <span class="material-symbols-outlined filled">rocket_launch</span>
        </button>

        <!-- Footer Link -->
        <div class="pt-3 pb-4 text-center">
            <p class="text-secondary small">
                Already have an account?
                <a href="?tab=login" class="text-primary-orange fw-bold text-decoration-none">Sign in</a>
            </p>
        </div>
    </form>
    <?php endif; ?>

    <!-- Bottom Indicator -->
    <div class="mt-auto mb-2 mx-auto bg-secondary rounded-pill" style="width: 120px; height: 6px; opacity: 0.3;"></div>
</div>

<?php include 'includes/footer.php'; ?>
