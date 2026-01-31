<?php
/**
 * Portfolio Page
 * Displays student's project submissions (Foundry Tasks)
 */

require_once 'includes/auth.php';
require_once 'includes/db.php';
require_once 'includes/roadmap_helper.php';

requireAuth();
$user = getCurrentUser();

// Fetch submissions
$db = db();
$stmt = $db->prepare("
    SELECT s.*, r.week_title, r.project_task, u.unit_name, u.unit_code
    FROM submissions s
    JOIN roadmaps r ON s.roadmap_id = r.id
    JOIN units u ON r.unit_id = u.id
    WHERE s.user_id = ?
    ORDER BY s.submitted_at DESC
");
$stmt->execute([$user['id']]);
$submissions = $stmt->fetchAll();

include 'includes/header.php';
?>

<div class="d-flex align-items-center p-4 pb-2 justify-content-between z-1">
    <a href="dashboard.php" class="text-white d-flex align-items-center justify-content-center text-decoration-none" style="width: 48px; height: 48px;">
        <span class="material-symbols-outlined">arrow_back_ios</span>
    </a>
    <h2 class="text-white fs-5 fw-bold m-0 flex-grow-1 text-center pe-5">My Portfolio</h2>
</div>

<div class="container-fluid p-4">
    <!-- Header -->
    <div class="text-center mb-5">
        <h1 class="display-5 fw-bold text-white mb-2">Foundry Projects</h1>
        <p class="text-secondary">Showcasing <?php echo count($submissions); ?> completed practical tasks.</p>
    </div>

    <!-- Submissions Grid -->
    <div class="row g-4">
        <?php if (empty($submissions)): ?>
            <div class="col-12 text-center py-5">
                <div class="bg-card-dark rounded-circle d-inline-flex align-items-center justify-content-center mb-4" style="width: 100px; height: 100px;">
                    <span class="material-symbols-outlined text-secondary fs-1">folder_open</span>
                </div>
                <h3 class="h5 text-white">No Projects Yet</h3>
                <p class="text-secondary small">Complete Foundry Tasks in your roadmap to build your portfolio.</p>
                <a href="dashboard.php" class="btn btn-outline-primary-orange rounded-pill px-4 mt-3">Go to Dashboard</a>
            </div>
        <?php else: ?>
            <?php foreach ($submissions as $sub): ?>
                <div class="col-12 col-md-6 col-xl-4">
                    <div class="card bg-card-dark border border-white border-opacity-10 rounded-4 h-100 p-0 overflow-hidden hover-lift">
                        <!-- Preview based on file type -->
                        <div class="bg-black bg-opacity-50 p-4 text-center d-flex align-items-center justify-content-center" style="height: 200px;">
                            <?php 
                            $ext = strtolower($sub['file_type']);
                            $icon = 'description';
                            $color = 'text-secondary';
                            
                            if (in_array($ext, ['jpg', 'png', 'jpeg'])) {
                                echo '<img src="' . htmlspecialchars($sub['file_path']) . '" alt="Project Preview" class="img-fluid h-100 w-100 object-fit-cover">';
                            } else {
                                if ($ext === 'pdf') { $icon = 'picture_as_pdf'; $color = 'text-danger'; }
                                elseif (in_array($ext, ['zip', 'rar'])) { $icon = 'folder_zip'; $color = 'text-warning'; }
                                elseif (in_array($ext, ['py', 'js', 'php', 'html'])) { $icon = 'code'; $color = 'text-info'; }
                                
                                echo '<span class="material-symbols-outlined ' . $color . '" style="font-size: 64px;">' . $icon . '</span>';
                            }
                            ?>
                        </div>
                        
                        <div class="card-body p-4 d-flex flex-column">
                            <div class="d-flex justify-content-between align-items-start mb-2">
                                <span class="badge bg-primary-orange bg-opacity-10 text-primary-orange small"><?php echo htmlspecialchars($sub['unit_code']); ?></span>
                                <small class="text-secondary" style="font-size: 0.75rem;"><?php echo date('M d, Y', strtotime($sub['submitted_at'])); ?></small>
                            </div>
                            
                            <h5 class="card-title text-white fw-bold mb-2"><?php echo htmlspecialchars($sub['week_title']); ?></h5>
                            <p class="card-text text-secondary small flex-grow-1 line-clamp-3">
                                <span class="fw-bold text-white text-opacity-75">Task:</span> 
                                <?php echo htmlspecialchars($sub['project_task']); ?>
                            </p>
                            
                            <div class="mt-3 pt-3 border-top border-white border-opacity-5">
                                <a href="<?php echo htmlspecialchars($sub['file_path']); ?>" download class="btn btn-sm btn-outline-light w-100 d-flex align-items-center justify-content-center gap-2">
                                    <span class="material-symbols-outlined fs-6">download</span>
                                    Download Submission
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</div>

<style>
.hover-lift {
    transition: transform 0.2s ease, box-shadow 0.2s ease;
}
.hover-lift:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 20px rgba(0,0,0,0.3);
}
.line-clamp-3 {
    display: -webkit-box;
    -webkit-line-clamp: 3;
    -webkit-box-orient: vertical;
    overflow: hidden;
}
</style>

<?php include 'includes/footer.php'; ?>
