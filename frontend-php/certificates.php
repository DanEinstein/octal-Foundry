<?php 
require_once 'includes/auth.php';
require_once 'includes/db.php';
require_once 'includes/roadmap_helper.php';

requireAuth();
$user = getCurrentUser();
$db = db();

// Fetch Earned Certificates
$certStmt = $db->prepare("
    SELECT c.*, u.unit_code, u.unit_name 
    FROM certificates c
    JOIN units u ON c.unit_id = u.id
    WHERE c.user_id = ?
    ORDER BY c.issued_at DESC
");
$certStmt->execute([$user['id']]);
$certificates = $certStmt->fetchAll();

// Fetch In-Progress Units for stats
$progStmt = $db->prepare("
    SELECT u.*, 
    (SELECT COUNT(*) FROM roadmaps r WHERE r.unit_id = u.id AND r.status = 'completed') as completed_weeks
    FROM units u
    WHERE u.user_id = ? AND u.status = 'in_progress'
");
$progStmt->execute([$user['id']]);
$inProgress = $progStmt->fetchAll();

include 'includes/header.php'; 
?>

<!-- Certificates Main Content -->
<main class="container-fluid p-3 p-lg-4">
    <!-- Page Header -->
    <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-4">
        <div>
            <h1 class="h4 fw-bold mb-1">My Certificates</h1>
            <p class="text-secondary mb-0">Achievements verified by Octal AI</p>
        </div>
        <div class="d-flex align-items-center gap-2">
            <span class="material-symbols-outlined text-primary-orange">workspace_premium</span>
            <span class="fw-bold"><?php echo count($certificates); ?> Certificates Earned</span>
        </div>
    </div>

    <!-- Certificate Stats -->
    <div class="row g-3 mb-4">
        <div class="col-12 col-md-4">
            <div class="card bg-primary-blue bg-opacity-10 border border-primary-blue border-opacity-25 rounded-4 p-3">
                <div class="d-flex align-items-center gap-3">
                    <div class="rounded-circle bg-primary-blue bg-opacity-25 d-flex align-items-center justify-content-center" style="width: 48px; height: 48px;">
                        <span class="material-symbols-outlined text-primary-blue">verified</span>
                    </div>
                    <div>
                        <h3 class="h4 fw-bold mb-0 text-primary-blue"><?php echo count($certificates); ?></h3>
                        <p class="text-secondary small mb-0">AI Verified</p>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-12 col-md-4">
            <div class="card bg-primary-orange bg-opacity-10 border border-primary-orange border-opacity-25 rounded-4 p-3">
                <div class="d-flex align-items-center gap-3">
                    <div class="rounded-circle bg-primary-orange bg-opacity-25 d-flex align-items-center justify-content-center" style="width: 48px; height: 48px;">
                        <span class="material-symbols-outlined text-primary-orange">download</span>
                    </div>
                    <div>
                        <h3 class="h4 fw-bold mb-0 text-primary-orange">0</h3>
                        <p class="text-secondary small mb-0">Downloads</p>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-12 col-md-4">
            <div class="card bg-success bg-opacity-10 border border-success border-opacity-25 rounded-4 p-3">
                <div class="d-flex align-items-center gap-3">
                    <div class="rounded-circle bg-success bg-opacity-25 d-flex align-items-center justify-content-center" style="width: 48px; height: 48px;">
                        <span class="material-symbols-outlined text-success">share</span>
                    </div>
                    <div>
                        <h3 class="h4 fw-bold mb-0 text-success">0</h3>
                        <p class="text-secondary small mb-0">Shared</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Certificates Grid -->
    <div class="row g-4">
        <?php if (empty($certificates)): ?>
            <div class="col-12 text-center py-5">
                <p class="text-secondary">You haven't earned any certificates yet. Complete a unit to earn one!</p>
            </div>
        <?php else: ?>
            <?php foreach ($certificates as $cert): ?>
            <div class="col-12 col-md-6 col-xl-4">
                <div class="card bg-card-dark border border-white border-opacity-10 rounded-4 overflow-hidden h-100">
                    <!-- Certificate Header -->
                    <div class="bg-primary-blue bg-opacity-10 p-4 text-center position-relative">
                        <!-- Verified Badge -->
                        <div class="position-absolute top-0 end-0 m-2">
                            <div class="d-flex align-items-center gap-1 bg-primary-blue bg-opacity-25 border border-primary-blue border-opacity-25 px-2 py-1 rounded-pill">
                                <span class="material-symbols-outlined text-primary-blue" style="font-size: 14px;">verified</span>
                                <span class="text-primary-blue small fw-bold" style="font-size: 10px;">AI Verified</span>
                            </div>
                        </div>
                        
                        <!-- Certificate Icon -->
                        <div class="rounded-circle bg-primary-blue bg-opacity-25 d-inline-flex align-items-center justify-content-center mb-3" style="width: 80px; height: 80px;">
                            <span class="material-symbols-outlined text-primary-blue" style="font-size: 40px;">workspace_premium</span>
                        </div>
                        
                        <h3 class="h6 fw-bold mb-1"><?php echo htmlspecialchars($cert['unit_name']); ?></h3>
                        <p class="text-secondary small mb-0">Octal Foundry Certificate</p>
                    </div>

                    <!-- Certificate Body -->
                    <div class="card-body p-3">
                        <div class="d-flex align-items-center justify-content-between mb-3">
                            <span class="badge bg-primary-blue bg-opacity-25 text-primary-blue small fw-bold"><?php echo htmlspecialchars($cert['unit_code']); ?></span>
                            <span class="text-secondary small"><?php echo date('F Y', strtotime($cert['issued_at'])); ?></span>
                        </div>

                        <!-- Actions -->
                        <div class="d-flex gap-2">
                            <button class="btn btn-sm bg-primary-orange text-white border-0 rounded-pill px-3 flex-grow-1">
                                <span class="material-symbols-outlined me-1" style="font-size: 16px;">download</span>
                                Download PDF
                            </button>
                            <button class="btn btn-sm btn-outline-secondary rounded-pill px-3">
                                <span class="material-symbols-outlined" style="font-size: 16px;">share</span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>

    <!-- Pending Certificates Section -->
    <?php if (!empty($inProgress)): ?>
    <div class="mt-5">
        <h2 class="h5 fw-bold mb-3">Certificates In Progress</h2>
        <?php foreach ($inProgress as $prog): 
            $percent = min(100, round(($prog['completed_weeks'] / 12) * 100));
        ?>
        <div class="card bg-card-dark border border-white border-opacity-10 rounded-4 p-3 mb-3">
            <div class="d-flex flex-wrap align-items-center gap-3">
                <div class="rounded-circle bg-primary-orange bg-opacity-25 d-flex align-items-center justify-content-center flex-shrink-0" style="width: 48px; height: 48px;">
                    <span class="material-symbols-outlined text-primary-orange">hourglass_top</span>
                </div>
                <div class="flex-grow-1">
                    <h4 class="h6 fw-bold mb-1"><?php echo htmlspecialchars($prog['unit_name']); ?></h4>
                    <p class="text-secondary small mb-0"><?php echo htmlspecialchars($prog['unit_code']); ?> - Complete Week 12 to earn this certificate</p>
                </div>
                <div class="text-end" style="min-width: 150px;">
                    <div class="d-flex align-items-center gap-2">
                        <div class="progress flex-grow-1 bg-secondary bg-opacity-25" style="height: 6px;">
                            <div class="progress-bar bg-primary-orange" style="width: <?php echo $percent; ?>%"></div>
                        </div>
                        <span class="small text-primary-orange fw-bold"><?php echo $percent; ?>%</span>
                    </div>
                </div>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
    <?php endif; ?>
</main>

<?php include 'includes/footer.php'; ?>
