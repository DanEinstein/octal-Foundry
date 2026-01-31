<?php
/**
 * Authentication Functions
 * Handles login, logout, registration, session management
 * 
 * Usage:
 *   require_once 'includes/auth.php';
 *   initSession();
 *   requireAuth(); // Redirect to login if not authenticated
 */

require_once __DIR__ . '/db.php';

/**
 * Initialize session with secure settings
 */
function initSession(): void {
    if (session_status() === PHP_SESSION_NONE) {
        session_start([
            'cookie_lifetime' => 86400 * 30, // 30 days
            'cookie_httponly' => true,
            'cookie_secure'   => isset($_SERVER['HTTPS']),
            'cookie_samesite' => 'Strict',
        ]);
    }
}

/**
 * Register a new user
 * 
 * @param string $fullName User's full name
 * @param string $email User's email address
 * @param string $password Plain text password (will be hashed)
 * @param string|null $university University name
 * @return array ['success' => bool, 'user_id' => int|null, 'error' => string|null]
 */
function registerUser(string $fullName, string $email, string $password, ?string $university = null): array {
    $db = db();
    
    // Validate email format
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        return ['success' => false, 'error' => 'Invalid email format'];
    }
    
    // Validate password length
    if (strlen($password) < 8) {
        return ['success' => false, 'error' => 'Password must be at least 8 characters'];
    }
    
    // Check if email already exists
    $stmt = $db->prepare("SELECT id FROM users WHERE email = ?");
    $stmt->execute([$email]);
    if ($stmt->fetch()) {
        return ['success' => false, 'error' => 'Email already registered'];
    }
    
    // Hash password with bcrypt (cost factor 12)
    $passwordHash = password_hash($password, PASSWORD_BCRYPT, ['cost' => 12]);
    
    try {
        $stmt = $db->prepare(
            "INSERT INTO users (full_name, email, password_hash, university) VALUES (?, ?, ?, ?)"
        );
        $stmt->execute([$fullName, $email, $passwordHash, $university]);
        
        $userId = (int) $db->lastInsertId();
        
        // Auto-login after registration
        initSession();
        session_regenerate_id(true);
        $_SESSION['user_id'] = $userId;
        $_SESSION['user_name'] = $fullName;
        $_SESSION['user_email'] = $email;
        $_SESSION['logged_in'] = true;
        
        return ['success' => true, 'user_id' => $userId];
    } catch (PDOException $e) {
        error_log("Registration error: " . $e->getMessage());
        return ['success' => false, 'error' => 'Registration failed. Please try again.'];
    }
}

/**
 * Login user with email and password
 * 
 * @param string $email User's email
 * @param string $password Plain text password
 * @return array ['success' => bool, 'error' => string|null]
 */
function loginUser(string $email, string $password): array {
    $db = db();
    
    $stmt = $db->prepare(
        "SELECT id, full_name, email, password_hash FROM users WHERE email = ?"
    );
    $stmt->execute([$email]);
    $user = $stmt->fetch();
    
    if (!$user) {
        return ['success' => false, 'error' => 'Invalid email or password'];
    }
    
    // Verify password
    if (!password_verify($password, $user['password_hash'])) {
        return ['success' => false, 'error' => 'Invalid email or password'];
    }
    
    // Regenerate session ID to prevent session fixation
    initSession();
    session_regenerate_id(true);
    
    // Set session variables
    $_SESSION['user_id'] = (int) $user['id'];
    $_SESSION['user_name'] = $user['full_name'];
    $_SESSION['user_email'] = $user['email'];
    $_SESSION['logged_in'] = true;
    
    return ['success' => true];
}

/**
 * Logout current user
 */
function logoutUser(): void {
    initSession();
    $_SESSION = [];
    
    // Delete session cookie
    if (ini_get('session.use_cookies')) {
        $params = session_get_cookie_params();
        setcookie(
            session_name(),
            '',
            time() - 42000,
            $params['path'],
            $params['domain'],
            $params['secure'],
            $params['httponly']
        );
    }
    
    session_destroy();
}

/**
 * Check if user is authenticated, redirect to login if not
 */
function requireAuth(): void {
    initSession();
    if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
        header('Location: login.php');
        exit;
    }
}

/**
 * Check if user is logged in (without redirect)
 * 
 * @return bool True if authenticated
 */
function isLoggedIn(): bool {
    initSession();
    return isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true;
}

/**
 * Get current user ID
 * 
 * @return int|null User ID or null if not logged in
 */
function getCurrentUserId(): ?int {
    initSession();
    return $_SESSION['user_id'] ?? null;
}

/**
 * Get current user's name
 * 
 * @return string|null User name or null if not logged in
 */
function getCurrentUserName(): ?string {
    initSession();
    return $_SESSION['user_name'] ?? null;
}

/**
 * Get full user data from database
 * 
 * @return array|null User data or null if not logged in
 */
function getCurrentUser(): ?array {
    initSession();
    $userId = $_SESSION['user_id'] ?? null;
    
    if (!$userId) {
        return null;
    }
    
    $db = db();
    $stmt = $db->prepare(
        "SELECT id, full_name, email, university, course, year_of_study, created_at 
         FROM users WHERE id = ?"
    );
    $stmt->execute([$userId]);
    return $stmt->fetch() ?: null;
}

/**
 * Generate CSRF token
 * 
 * @return string CSRF token
 */
function getCsrfToken(): string {
    initSession();
    if (!isset($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

/**
 * Validate CSRF token
 * 
 * @param string $token Token from form submission
 * @return bool True if valid
 */
function validateCsrfToken(string $token): bool {
    initSession();
    return isset($_SESSION['csrf_token']) && hash_equals($_SESSION['csrf_token'], $token);
}

/**
 * Output CSRF hidden input field
 */
function csrfField(): void {
    echo '<input type="hidden" name="csrf_token" value="' . htmlspecialchars(getCsrfToken()) . '">';
}
