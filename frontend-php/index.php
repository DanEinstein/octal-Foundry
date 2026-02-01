<?php
/**
 * Portfolio Index Page
 * Displays student's public profile with real data
 */

require_once 'includes/auth.php';
require_once 'includes/db.php';

requireAuth();
$user = getCurrentUser();
$db = db();

// Fetch user's submissions (projects)
$stmt = $db->prepare("
    SELECT s.*, r.week_title, r.project_task, u.unit_name, u.unit_code
    FROM submissions s
    JOIN roadmaps r ON s.roadmap_id = r.id
    JOIN units u ON r.unit_id = u.id
    WHERE s.user_id = ?
    ORDER BY s.submitted_at DESC
    LIMIT 6
");
$stmt->execute([$user['id']]);
$projects = $stmt->fetchAll();

// Fetch user's units for skills
$stmtUnits = $db->prepare("SELECT DISTINCT unit_name, unit_code FROM units WHERE user_id = ?");
$stmtUnits->execute([$user['id']]);
$units = $stmtUnits->fetchAll();

// Count completed weeks (submissions as proxy for completed tasks)
$stmtCompleted = $db->prepare("
    SELECT COUNT(*) as completed_count
    FROM submissions
    WHERE user_id = ?
");
$stmtCompleted->execute([$user['id']]);
$completedCount = $stmtCompleted->fetch()['completed_count'] ?? 0;

// Build display name
$displayName = $user['full_name'] ?? $user['email'] ?? 'Student';
$courseName = $user['course_name'] ?? $user['course'] ?? 'Student';
$yearOfStudy = $user['year_of_study'] ?? null;
$displayTitle = $courseName . ($yearOfStudy ? ", Year $yearOfStudy" : '');

include 'includes/header.php';
?>

<main class="container-fluid p-3 p-lg-4">
    <!-- Page Header -->
    <div class="d-flex flex-wrap align-items-center justify-content-between gap-2 mb-4">
        <h1 class="h5 h4-md fw-bold mb-0">My Portfolio</h1>
        <div class="d-flex gap-2">
            <button class="btn btn-outline-secondary rounded-pill px-2 px-sm-3 d-flex align-items-center gap-1 gap-sm-2">
                <span class="material-symbols-outlined" style="font-size: 18px;">share</span>
                <span class="d-none d-sm-inline">Share</span>
            </button>
            <a href="settings.php" class="btn btn-outline-secondary rounded-pill px-2 px-sm-3 d-flex align-items-center">
                <span class="material-symbols-outlined" style="font-size: 18px;">settings</span>
            </a>
        </div>
    </div>

    <!-- Profile Header -->
    <section class="card bg-card-dark border border-white border-opacity-10 rounded-4 p-3 p-md-4 mb-4">
        <div class="d-flex flex-column align-items-center text-center">
            <!-- Avatar -->
            <div class="position-relative mb-3">
                <div class="rounded-circle p-1 border border-3 border-md-4 border-primary border-opacity-25" style="width: 100px; height: 100px; border-color: var(--primary-blue) !important;">
                    <div class="w-100 h-100 rounded-circle bg-primary-orange d-flex align-items-center justify-content-center">
                        <span class="text-white display-6 fw-bold"><?php echo strtoupper(substr($displayName, 0, 1)); ?></span>
                    </div>
                </div>
                <?php if (count($projects) >= 3): ?>
                <div class="position-absolute bottom-0 end-0 bg-primary-blue text-white p-1 rounded-circle border border-2 border-dark d-flex align-items-center justify-content-center">
                    <span class="material-symbols-outlined" style="font-size: 0.875rem;">verified</span>
                </div>
                <?php endif; ?>
            </div>

            <!-- User Info -->
            <h1 class="h4 fw-bold mb-1"><?php echo htmlspecialchars($displayName); ?></h1>
            <p class="text-secondary fw-medium mb-3 small"><?php echo htmlspecialchars($displayTitle); ?></p>

            <?php if (count($projects) >= 3): ?>
            <!-- Verified Badge -->
            <div class="d-inline-flex align-items-center gap-2 bg-primary-blue bg-opacity-10 border border-primary border-opacity-25 px-2 px-sm-3 py-1 rounded-pill mb-3 verified-glow" style="border-color: var(--primary-blue) !important;">
                <span class="material-symbols-outlined text-primary-blue" style="font-size: 1.25rem;">workspace_premium</span>
                <span class="text-primary-blue small fw-bold text-uppercase tracking-wider" style="font-size: 0.65rem;">Verified by Octal AI</span>
            </div>
            <?php endif; ?>

            <!-- Action Buttons -->
            <div class="d-flex flex-column flex-sm-row gap-2 w-100 justify-content-center">
                <button class="btn bg-primary-orange text-white py-2 px-3 px-sm-4 rounded-pill fw-bold d-flex align-items-center justify-content-center gap-2 border-0 glow-orange">
                    <span class="material-symbols-outlined" style="font-size: 1.25rem;">download</span>
                    <span>Download CV</span>
                </button>
                <a href="certificates.php" class="btn btn-outline-secondary py-2 px-3 px-sm-4 rounded-pill fw-bold d-flex align-items-center justify-content-center gap-2">
                    <span class="material-symbols-outlined" style="font-size: 1.25rem;">workspace_premium</span>
                    <span>Certificates</span>
                </a>
            </div>
        </div>
    </section>

    <!-- Tabs Navigation -->
    <nav class="bg-card-dark border border-white border-opacity-10 rounded-4 mb-4 overflow-hidden">
        <div class="d-flex">
            <a href="index.php" class="text-decoration-none py-2 py-sm-3 border-bottom border-2 border-primary text-primary-blue fw-bold small flex-fill text-center" style="border-color: var(--primary-blue) !important;">Projects</a>
            <a href="certificates.php" class="text-decoration-none py-2 py-sm-3 border-bottom border-2 border-transparent text-secondary fw-bold small flex-fill text-center">Certificates</a>
            <a href="skills.php" class="text-decoration-none py-2 py-sm-3 border-bottom border-2 border-transparent text-secondary fw-bold small flex-fill text-center">Skills</a>
        </div>
    </nav>

    <!-- Section Header -->
    <div class="d-flex align-items-center justify-content-between mb-3">
        <h3 class="h6 h5-sm fw-bold m-0">Practical Work</h3>
        <span class="text-primary-blue small fw-bold"><?php echo count($projects); ?> Projects</span>
    </div>

    <!-- Project Cards Grid -->
    <div class="row g-3 g-lg-4 mb-4">
        <?php if (empty($projects)): ?>
            <div class="col-12 text-center py-5">
                <div class="bg-card-dark rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 80px; height: 80px;">
                    <span class="material-symbols-outlined text-secondary fs-2">folder_open</span>
                </div>
                <h4 class="h6 text-white">No Projects Yet</h4>
                <p class="text-secondary small">Complete Foundry Tasks in your roadmap to build your portfolio.</p>
                <a href="dashboard.php" class="btn btn-outline-primary-orange rounded-pill px-4">Go to Dashboard</a>
            </div>
        <?php else: ?>
            <?php foreach ($projects as $project): ?>
            <div class="col-12 col-md-6">
                <div class="card bg-card-dark border border-white border-opacity-10 rounded-4 overflow-hidden shadow h-100">
                    <!-- Project Preview -->
                    <div class="ratio ratio-16x9 bg-black bg-opacity-50 d-flex align-items-center justify-content-center">
                        <?php 
                        $ext = strtolower($project['file_type'] ?? '');
                        if (in_array($ext, ['jpg', 'png', 'jpeg', 'gif', 'webp']) && !empty($project['file_path'])): ?>
                            <img src="<?php echo htmlspecialchars($project['file_path']); ?>" alt="Project Preview" class="w-100 h-100 object-fit-cover">
                        <?php else: ?>
                            <span class="material-symbols-outlined text-primary-orange" style="font-size: 48px;">code</span>
                        <?php endif; ?>
                    </div>
                    <div class="card-body p-3">
                        <div class="d-flex justify-content-between align-items-start mb-2">
                            <h4 class="h6 fw-bold text-white m-0"><?php echo htmlspecialchars($project['week_title'] ?? 'Project'); ?></h4>
                            <a href="<?php echo htmlspecialchars($project['file_path'] ?? '#'); ?>" class="text-primary-blue flex-shrink-0 ms-2" download>
                                <span class="material-symbols-outlined">download</span>
                            </a>
                        </div>
                        <p class="text-secondary small mb-3"><?php echo htmlspecialchars(substr($project['project_task'] ?? '', 0, 100)); ?><?php echo strlen($project['project_task'] ?? '') > 100 ? '...' : ''; ?></p>
                        <div class="d-flex flex-wrap gap-1 gap-sm-2 mb-3">
                            <span class="badge bg-primary-orange bg-opacity-10 text-primary-orange fw-bold text-uppercase px-2 py-1" style="font-size: 0.65rem;">
                                <?php echo htmlspecialchars($project['unit_code'] ?? 'SKILL'); ?>
                            </span>
                            <span class="badge bg-primary-blue bg-opacity-10 text-primary-blue fw-bold text-uppercase px-2 py-1" style="font-size: 0.65rem;">
                                <?php echo date('M Y', strtotime($project['submitted_at'])); ?>
                            </span>
                        </div>
                        <a href="<?php echo htmlspecialchars($project['file_path'] ?? '#'); ?>" download class="btn btn-dark w-100 py-2 fw-bold small border border-secondary border-opacity-25">
                            Download Submission
                        </a>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>

    <!-- Skills Section (from units) -->
    <?php if (!empty($units)): ?>
    <section class="mb-4">
        <div class="d-flex align-items-center justify-content-between mb-3">
            <h3 class="h6 h5-sm fw-bold m-0">Skills & Courses</h3>
            <a href="units.php" class="text-primary-blue text-decoration-none small fw-bold">View All</a>
        </div>
        <div class="d-flex overflow-auto gap-3 pb-2 no-scrollbar">
            <?php foreach ($units as $unit): ?>
            <a href="learning.php" class="flex-shrink-0 card bg-card-dark border border-white border-opacity-10 rounded-4 p-3 text-center text-decoration-none" style="min-width: 140px; max-width: 160px;">
                <div class="rounded-circle bg-primary-blue bg-opacity-25 d-flex align-items-center justify-content-center mx-auto mb-2" style="width: 56px; height: 56px;">
                    <span class="material-symbols-outlined text-primary-blue" style="font-size: 1.75rem;">school</span>
                </div>
                <p class="small fw-bold text-white mb-1" style="font-size: 0.8rem;"><?php echo htmlspecialchars($unit['unit_code'] ?? 'SKILL'); ?></p>
                <p class="text-secondary mb-0 text-truncate" style="font-size: 0.65rem;"><?php echo htmlspecialchars($unit['unit_name']); ?></p>
            </a>
            <?php endforeach; ?>
        </div>
    </section>
    <?php endif; ?>

    <!-- Stats Section -->
    <section class="mb-4">
        <h3 class="h6 h5-sm fw-bold mb-3">Progress Overview</h3>
        <div class="row g-3">
            <div class="col-6 col-md-3">
                <div class="card bg-card-dark border border-white border-opacity-10 rounded-4 p-3 text-center">
                    <span class="material-symbols-outlined text-primary-orange mb-2" style="font-size: 2rem;">code</span>
                    <h4 class="h3 fw-bold mb-0"><?php echo count($projects); ?></h4>
                    <p class="text-secondary small mb-0">Projects</p>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="card bg-card-dark border border-white border-opacity-10 rounded-4 p-3 text-center">
                    <span class="material-symbols-outlined text-primary-blue mb-2" style="font-size: 2rem;">school</span>
                    <h4 class="h3 fw-bold mb-0"><?php echo count($units); ?></h4>
                    <p class="text-secondary small mb-0">Courses</p>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="card bg-card-dark border border-white border-opacity-10 rounded-4 p-3 text-center">
                    <span class="material-symbols-outlined text-success mb-2" style="font-size: 2rem;">task_alt</span>
                    <h4 class="h3 fw-bold mb-0"><?php echo $completedCount; ?></h4>
                    <p class="text-secondary small mb-0">Completed</p>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="card bg-card-dark border border-white border-opacity-10 rounded-4 p-3 text-center">
                    <span class="material-symbols-outlined text-warning mb-2" style="font-size: 2rem;">workspace_premium</span>
                    <h4 class="h3 fw-bold mb-0"><?php echo count($projects) >= 3 ? '1' : '0'; ?></h4>
                    <p class="text-secondary small mb-0">Certificates</p>
                </div>
            </div>
        </div>
    </section>
</main>

<?php include 'includes/footer.php'; ?>
