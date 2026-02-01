<?php
/**
 * Delete Unit Endpoint
 * Handles unit deletion via POST request
 */

require_once 'includes/auth.php';
require_once 'includes/roadmap_helper.php';

requireAuth();

// Only accept POST requests
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    header('Content-Type: application/json');
    echo json_encode(['success' => false, 'error' => 'Method not allowed']);
    exit;
}

// Get user and unit ID
$user = getCurrentUser();
$unitId = isset($_POST['unit_id']) ? (int)$_POST['unit_id'] : 0;

if ($unitId <= 0) {
    http_response_code(400);
    header('Content-Type: application/json');
    echo json_encode(['success' => false, 'error' => 'Invalid unit ID']);
    exit;
}

// Attempt to delete
$success = deleteUnit($unitId, $user['id']);

if ($success) {
    // If AJAX request, return JSON
    if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && 
        strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest') {
        header('Content-Type: application/json');
        echo json_encode(['success' => true, 'message' => 'Unit deleted successfully']);
    } else {
        // Regular form submission - redirect
        header('Location: units.php?deleted=1');
    }
} else {
    if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && 
        strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest') {
        http_response_code(404);
        header('Content-Type: application/json');
        echo json_encode(['success' => false, 'error' => 'Unit not found or could not be deleted']);
    } else {
        header('Location: units.php?error=delete_failed');
    }
}
exit;
