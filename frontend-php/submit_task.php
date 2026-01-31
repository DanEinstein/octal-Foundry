<?php
/**
 * Submit Task Logic
 * Handles file uploads for Foundry Tasks
 */

require_once 'includes/auth.php';
require_once 'includes/db.php';
require_once 'includes/roadmap_helper.php';

requireAuth(); // Ensure user is logged in

$user = getCurrentUser();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $roadmapId = (int)($_POST['roadmap_id'] ?? 0);
    $weekNumber = (int)($_POST['week_number'] ?? 0);
    $unitId = (int)($_POST['unit_id'] ?? 0);
    $file = $_FILES['submission_file'] ?? null;
    
    // Basic validation
    if (!$roadmapId || !$file || $file['error'] !== UPLOAD_ERR_OK) {
        $error = "Invalid submission. Please try again.";
    } else {
        // defined upload directory
        $uploadDir = __DIR__ . '/uploads/submissions/' . $user['id'] . '/';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }
        
        $fileName = basename($file['name']);
        // Sanitize filename
        $fileName = preg_replace('/[^a-zA-Z0-9._-]/', '', $fileName);
        $targetPath = $uploadDir . time() . '_' . $fileName;
        $dbPath = 'uploads/submissions/' . $user['id'] . '/' . time() . '_' . $fileName;
        
        // Validate file type (allow PDF, ZIP, code files, images)
        $allowedExtensions = ['pdf', 'zip', 'py', 'php', 'js', 'html', 'css', 'txt', 'md', 'jpg', 'png'];
        $fileExt = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
        
        if (!in_array($fileExt, $allowedExtensions)) {
            $error = "File type not allowed. Allowed: " . implode(', ', $allowedExtensions);
        } else {
            if (move_uploaded_file($file['tmp_name'], $targetPath)) {
                // Save to database
                $db = db();
                $stmt = $db->prepare(
                    "INSERT INTO submissions (user_id, roadmap_id, week_number, file_path, file_type) 
                     VALUES (?, ?, ?, ?, ?)"
                );
                
                try {
                    $stmt->execute([
                        $user['id'],
                        $roadmapId,
                        $weekNumber,
                        $dbPath,
                        $fileExt
                    ]);
                    
                    // Success - redirect back with success message
                    header('Location: roadmap.php?unit_id=' . $unitId . '&submission=success');
                    exit;
                    
                } catch (PDOException $e) {
                    error_log("Database error creating submission: " . $e->getMessage());
                    $error = "System error during submission.";
                }
            } else {
                $error = "Failed to upload file.";
            }
        }
    }
    
    // If error, redirect back with error
    if (isset($error)) {
        header('Location: roadmap.php?unit_id=' . $unitId . '&error=' . urlencode($error));
        exit;
    }
} else {
    // Redirect if not POST
    header('Location: dashboard.php');
    exit;
}
