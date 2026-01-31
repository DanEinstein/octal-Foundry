<?php
require_once 'includes/auth.php';
require_once 'includes/roadmap_helper.php';

requireAuth();

$user = getCurrentUser();
$units = getUserUnits($user['id']);

// Calculate stats
$totalUnits = count($units);
$completedUnits = 0;
$inProgressUnits = 0;
$notStartedUnits = 0;

foreach ($units as $unit) {
    if ($unit['status'] === 'completed') $completedUnits++;
    elseif ($unit['status'] === 'in_progress') $inProgressUnits++;
    else $notStartedUnits++;
}

include 'includes/header.php';
?>

<!-- My Units Main Content -->
<main class="container-fluid p-3 p-lg-4">
    <!-- Page Header -->
    <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-4">
        <div>
            <h1 class="h4 fw-bold mb-1">My Units</h1>
            <p class="text-secondary mb-0">
                <?php echo htmlspecialchars($user['university'] ?? 'University Student'); ?> 
                <?php if (!empty($user['year_of_study'])) echo ' - Year ' . $user['year_of_study']; ?>
            </p>
        </div>
        <div class="d-flex gap-2">
            <button class="btn btn-outline-secondary rounded-pill px-3 d-flex align-items-center gap-2">
                <span class="material-symbols-outlined" style="font-size: 18px;">filter_list</span>
                Filter
            </button>
            <a href="onboarding.php" class="btn bg-primary-orange text-white rounded-pill px-3 border-0 d-flex align-items-center gap-2">
                <span class="material-symbols-outlined" style="font-size: 18px;">add</span>
                Add Unit
            </a>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="row g-3 mb-4">
        <div class="col-6 col-lg-3">
            <div class="card bg-card-dark border border-white border-opacity-10 rounded-4 p-3 text-center">
                <span class="material-symbols-outlined text-primary-blue mb-2" style="font-size: 32px;">menu_book</span>
                <h3 class="h4 fw-bold mb-0"><?php echo $totalUnits; ?></h3>
                <p class="text-secondary small mb-0">Enrolled Units</p>
            </div>
        </div>
        <div class="col-6 col-lg-3">
            <div class="card bg-card-dark border border-white border-opacity-10 rounded-4 p-3 text-center">
                <span class="material-symbols-outlined text-success mb-2" style="font-size: 32px;">check_circle</span>
                <h3 class="h4 fw-bold mb-0"><?php echo $completedUnits; ?></h3>
                <p class="text-secondary small mb-0">Completed</p>
            </div>
        </div>
        <div class="col-6 col-lg-3">
            <div class="card bg-card-dark border border-white border-opacity-10 rounded-4 p-3 text-center">
                <span class="material-symbols-outlined text-primary-orange mb-2" style="font-size: 32px;">play_circle</span>
                <h3 class="h4 fw-bold mb-0"><?php echo $inProgressUnits; ?></h3>
                <p class="text-secondary small mb-0">In Progress</p>
            </div>
        </div>
        <div class="col-6 col-lg-3">
            <div class="card bg-card-dark border border-white border-opacity-10 rounded-4 p-3 text-center">
                <span class="material-symbols-outlined text-secondary mb-2" style="font-size: 32px;">schedule</span>
                <h3 class="h4 fw-bold mb-0"><?php echo $notStartedUnits; ?></h3>
                <p class="text-secondary small mb-0">Not Started</p>
            </div>
        </div>
    </div>

    <!-- Units Grid -->
    <div class="row g-4">
        <?php if (empty($units)): ?>
        <div class="col-12 text-center py-5">
            <div class="bg-card-dark rounded-circle d-inline-flex align-items-center justify-content-center mb-4" style="width: 120px; height: 120px;">
                <span class="material-symbols-outlined text-secondary display-3">school</span>
            </div>
            <h2 class="h4 fw-bold text-white mb-3">No Units Found</h2>
            <p class="text-secondary mb-4">Add your first unit to start learning.</p>
            <a href="onboarding.php" class="btn bg-primary-orange text-white fw-bold py-3 px-5 rounded-pill glow-orange border-0 shadow-lg">
                Add Unit
            </a>
        </div>
        <?php else: 
            foreach ($units as $unit):
                $statusBadge = '';
                $borderClass = 'border-white border-opacity-10';
                $color = 'primary-blue';
                $icon = 'school';
                
                switch ($unit['status']) {
                    case 'completed':
                        $statusBadge = '<span class="badge bg-success bg-opacity-25 text-success small">Completed</span>';
                        $color = 'success';
                        $icon = 'emoji_events';
                        break;
                    case 'in_progress':
                        $statusBadge = '<span class="badge bg-primary-orange bg-opacity-25 text-primary-orange small">In Progress</span>';
                        $borderClass = 'border-primary-orange glow-orange';
                        $color = 'primary-orange';
                        $icon = 'play_circle';
                        break;
                    case 'not_started':
                        $statusBadge = '<span class="badge bg-secondary bg-opacity-25 text-secondary small">Not Started</span>';
                        $color = 'secondary';
                        $icon = 'schedule';
                        break;
                }
        ?>
        <div class="col-12 col-md-6 col-xl-4">
            <div class="card bg-card-dark border <?php echo $borderClass; ?> rounded-4 p-3 h-100">
                <!-- Unit Header -->
                <div class="d-flex align-items-start gap-3 mb-3">
                    <div class="rounded-3 bg-<?php echo $color; ?> bg-opacity-25 d-flex align-items-center justify-content-center flex-shrink-0" style="width: 48px; height: 48px;">
                        <span class="material-symbols-outlined text-<?php echo $color; ?>"><?php echo $icon; ?></span>
                    </div>
                    <div class="flex-grow-1">
                        <div class="d-flex align-items-center gap-2 mb-1">
                            <span class="badge bg-primary-blue bg-opacity-25 text-primary-blue small fw-bold"><?php echo htmlspecialchars($unit['unit_code']); ?></span>
                            <?php echo $statusBadge; ?>
                        </div>
                        <h3 class="h6 fw-bold mb-1"><?php echo htmlspecialchars($unit['unit_name']); ?></h3>
                        <p class="text-secondary small mb-0"><?php echo htmlspecialchars($unit['lecturer_name'] ?? 'Unknown Lecturer'); ?></p>
                    </div>
                </div>

                <!-- Progress -->
                <div class="mb-3">
                    <div class="d-flex align-items-center justify-content-between mb-1">
                        <span class="small text-secondary">Progress</span>
                        <span class="small fw-bold text-<?php echo $color; ?>"><?php echo $unit['progress_percent']; ?>%</span>
                    </div>
                    <div class="progress bg-secondary bg-opacity-25" style="height: 6px;">
                        <div class="progress-bar bg-<?php echo $color; ?>" style="width: <?php echo $unit['progress_percent']; ?>%"></div>
                    </div>
                    <p class="text-secondary small mt-1 mb-0">12 Week Roadmap</p>
                </div>

                <!-- Actions -->
                <div class="d-flex gap-2 mt-auto">
                    <?php if ($unit['status'] === 'in_progress'): ?>
                    <a href="dashboard.php?unit_id=<?php echo $unit['id']; ?>" class="btn btn-sm bg-primary-orange text-white border-0 rounded-pill px-3 flex-grow-1">
                        Continue
                        <span class="material-symbols-outlined ms-1" style="font-size: 16px;">arrow_forward</span>
                    </a>
                    <?php elseif ($unit['status'] === 'completed'): ?>
                    <a href="certificates.php" class="btn btn-sm btn-outline-success rounded-pill px-3 flex-grow-1">
                        <span class="material-symbols-outlined me-1" style="font-size: 16px;">workspace_premium</span>
                        View Certificate
                    </a>
                    <?php else: ?>
                    <a href="dashboard.php?unit_id=<?php echo $unit['id']; ?>" class="btn btn-sm btn-outline-secondary rounded-pill px-3 flex-grow-1">
                        <span class="material-symbols-outlined me-1" style="font-size: 16px;">play_arrow</span>
                        Start Learning
                    </a>
                    <?php endif; ?>
                    <a href="roadmap.php?unit_id=<?php echo $unit['id']; ?>" class="btn btn-sm btn-outline-secondary rounded-pill px-3">
                        <span class="material-symbols-outlined" style="font-size: 16px;">route</span>
                    </a>
                </div>
            </div>
        </div>
        <?php endforeach; endif; ?>
    </div>
</main>

<?php include 'includes/footer.php'; ?>
