<?php
require_once 'includes/auth.php';
require_once 'includes/roadmap_helper.php';

requireAuth();

$user = getCurrentUser();
$units = getUserUnits($user['id']);

$activeUnitId = $_GET['unit_id'] ?? ($units[0]['id'] ?? null);
$activeUnit = null;

if ($activeUnitId) {
    foreach ($units as $u) {
        if ($u['id'] == $activeUnitId) {
            $activeUnit = $u;
            break;
        }
    }
}

$roadmap = [];
$currentWeekNum = 1;
$totalTasks = 0;
$completedTasks = 0;

if ($activeUnit) {
    $roadmap = getUnitRoadmap($activeUnit['id']);
    
    // Calculate progress
    foreach ($roadmap as $week) {
        $totalTasks += $week['tasks_total'];
        $completedTasks += $week['tasks_completed'];
        
        if ($week['status'] === 'current') {
            $currentWeekNum = $week['week_number'];
        }
    }
}

$activeUnitCode = $activeUnit ? $activeUnit['unit_code'] : 'No Course';
$activeUnitName = $activeUnit ? $activeUnit['unit_name'] : 'Select a course to view roadmap';

include 'includes/header.php';
?>

<!-- Roadmap Main Content -->
<main class="container-fluid p-3 p-lg-4">
    <!-- Page Header -->
    <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-4">
        <div>
            <h1 class="h4 fw-bold mb-1">12-Week Learning Roadmap</h1>
            <div class="dropdown">
                <button class="btn btn-link text-white text-decoration-none dropdown-toggle p-0" type="button" data-bs-toggle="dropdown">
                    <span class="text-secondary"><?php echo htmlspecialchars($activeUnitCode); ?> - <?php echo htmlspecialchars($activeUnitName); ?></span>
                </button>
                <ul class="dropdown-menu dropdown-menu-dark shadow-lg border-secondary border-opacity-25">
                    <?php if (empty($units)): ?>
                        <li><span class="dropdown-item disabled">No enrolled units</span></li>
                    <?php else: ?>
                        <?php foreach ($units as $u): ?>
                        <li>
                            <a class="dropdown-item <?php echo $u['id'] == $activeUnitId ? 'active' : ''; ?>" 
                               href="?unit_id=<?php echo $u['id']; ?>">
                                <?php echo htmlspecialchars($u['unit_code'] . ' - ' . $u['unit_name']); ?>
                            </a>
                        </li>
                        <?php endforeach; ?>
                    <?php endif; ?>
                    <li><hr class="dropdown-divider border-white border-opacity-10"></li>
                    <li>
                        <a class="dropdown-item text-primary-orange" href="onboarding.php">
                            <span class="material-symbols-outlined fs-6 align-middle me-2">add_circle</span>
                            Add New Unit
                        </a>
                    </li>
                </ul>
            </div>
        </div>
        
        <?php if ($activeUnit): ?>
        <div class="d-flex align-items-center gap-3">
            <div class="text-end">
                <span class="text-primary-orange fw-bold h5 mb-0">Week <?php echo $currentWeekNum; ?></span>
                <p class="text-secondary small mb-0">of 12 weeks</p>
            </div>
            <div class="rounded-circle bg-primary-orange bg-opacity-25 d-flex align-items-center justify-content-center" style="width: 48px; height: 48px;">
                <span class="text-primary-orange fw-bold"><?php echo $activeUnit['progress_percent']; ?>%</span>
            </div>
        </div>
        <?php endif; ?>
    </div>

    <?php if ($activeUnit): ?>
    <!-- Overall Progress Bar -->
    <div class="card bg-card-dark border border-white border-opacity-10 rounded-4 p-3 mb-4">
        <div class="d-flex align-items-center justify-content-between mb-2">
            <span class="small text-secondary">Overall Progress</span>
            <span class="small text-primary-blue fw-bold"><?php echo $activeUnit['weeks_completed'] ?? 0; ?> of 12 weeks</span>
        </div>
        <div class="progress bg-secondary bg-opacity-25" style="height: 8px;">
            <div class="progress-bar bg-primary-blue" style="width: <?php echo $activeUnit['progress_percent']; ?>%"></div>
        </div>
    </div>

    <!-- Roadmap Timeline -->
    <div class="roadmap-timeline">
        <?php
        if (empty($roadmap)) {
            // Fallback for new units maybe not fully generated yet or empty
            echo '<div class="text-center py-4"><p class="text-secondary">Roadmap data is being generated...</p></div>';
        }

        foreach ($roadmap as $week):
            $statusClass = '';
            $statusIcon = '';
            $statusBadge = '';
            $borderClass = 'border-white border-opacity-10';
            
            switch ($week['status']) {
                case 'completed':
                    $statusClass = 'bg-success';
                    $statusIcon = 'check_circle';
                    $statusBadge = '<span class="badge bg-success bg-opacity-25 text-success small">Completed</span>';
                    break;
                case 'current':
                    $statusClass = 'bg-primary-orange';
                    $statusIcon = 'play_circle';
                    $statusBadge = '<span class="badge bg-primary-orange bg-opacity-25 text-primary-orange small">In Progress</span>';
                    $borderClass = 'border-primary-orange glow-orange';
                    break;
                default: // locked or others
                    $statusClass = 'bg-secondary bg-opacity-50';
                    $statusIcon = 'lock';
                    $statusBadge = '<span class="badge bg-secondary bg-opacity-25 text-secondary small">Locked</span>';
                    break;
            }
            
            $progress = $week['tasks_total'] > 0 ? ($week['tasks_completed'] / $week['tasks_total']) * 100 : 0;
            $topics = json_decode($week['topics'], true) ?? [];
        ?>
        
        <div class="d-flex gap-3 mb-3">
            <!-- Timeline Indicator -->
            <div class="d-flex flex-column align-items-center">
                <div class="rounded-circle <?php echo $statusClass; ?> d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                    <span class="material-symbols-outlined text-white" style="font-size: 20px;"><?php echo $statusIcon; ?></span>
                </div>
                <?php if ($week['week_number'] < 12): ?>
                <div class="flex-grow-1 bg-secondary bg-opacity-25" style="width: 2px; min-height: 20px;"></div>
                <?php endif; ?>
            </div>

            <!-- Week Card -->
            <div class="card bg-card-dark border <?php echo $borderClass; ?> rounded-4 p-3 flex-grow-1 mb-2">
                <div class="d-flex flex-wrap align-items-start justify-content-between gap-2 mb-2">
                    <div>
                        <div class="d-flex align-items-center gap-2 mb-1">
                            <span class="badge bg-primary-blue bg-opacity-25 text-primary-blue small fw-bold">Week <?php echo $week['week_number']; ?></span>
                            <?php echo $statusBadge; ?>
                        </div>
                        <h3 class="h6 fw-bold mb-0"><?php echo htmlspecialchars($week['week_title']); ?></h3>
                    </div>
                    <?php if ($week['status'] !== 'locked'): ?>
                    <div class="text-end">
                        <span class="small text-secondary"><?php echo $week['tasks_completed']; ?>/<?php echo $week['tasks_total']; ?> tasks</span>
                        <div class="progress bg-secondary bg-opacity-25 mt-1" style="width: 80px; height: 4px;">
                            <div class="progress-bar <?php echo $week['status'] === 'completed' ? 'bg-success' : 'bg-primary-orange'; ?>" style="width: <?php echo $progress; ?>%"></div>
                        </div>
                    </div>
                    <?php endif; ?>
                </div>

                <!-- Topics -->
                <div class="d-flex flex-wrap gap-2 mb-3">
                    <?php foreach ($topics as $topic): ?>
                    <span class="badge bg-white bg-opacity-5 text-secondary small fw-normal px-2 py-1"><?php echo htmlspecialchars($topic); ?></span>
                    <?php endforeach; ?>
                </div>

                <!-- Foundry Task & Submission -->
                <?php if (!empty($week['project_task'])): ?>
                <div class="bg-black bg-opacity-20 rounded-3 p-3 border border-white border-opacity-5">
                    <div class="d-flex align-items-center gap-2 mb-2">
                        <span class="material-symbols-outlined text-primary-orange" style="font-size: 20px;">construction</span>
                        <h4 class="h6 fw-bold text-white mb-0">Foundry Task</h4>
                    </div>
                    <p class="text-secondary small mb-3"><?php echo htmlspecialchars($week['project_task']); ?></p>
                    
                    <?php if ($week['status'] !== 'locked'): ?>
                    <form action="submit_task.php" method="POST" enctype="multipart/form-data" class="d-flex gap-2">
                        <input type="hidden" name="roadmap_id" value="<?php echo $week['id']; ?>">
                        <input type="hidden" name="week_number" value="<?php echo $week['week_number']; ?>">
                        <input type="hidden" name="unit_id" value="<?php echo $activeUnitId; ?>">
                        
                        <div class="flex-grow-1">
                            <input type="file" name="submission_file" class="form-control form-control-sm form-control-dark" required>
                        </div>
                        <button type="submit" class="btn btn-sm btn-primary-orange text-white">
                            <span class="material-symbols-outlined" style="font-size: 18px;">upload</span>
                        </button>
                    </form>
                    <?php else: ?>
                    <div class="d-flex align-items-center gap-2 text-secondary small opacity-50">
                        <span class="material-symbols-outlined" style="font-size: 16px;">lock</span>
                        <span>Unlock previous weeks to submit</span>
                    </div>
                    <?php endif; ?>
                </div>
                <?php endif; ?>

                <?php if ($week['status'] === 'current'): ?>
                <div class="mt-3">
                    <a href="dashboard.php?unit_id=<?php echo $activeUnitId; ?>" class="btn btn-sm bg-primary-orange text-white border-0 rounded-pill px-3">
                        Continue Learning
                        <span class="material-symbols-outlined ms-1" style="font-size: 16px;">arrow_forward</span>
                    </a>
                </div>
                <?php endif; ?>
            </div>
        </div>
        
        <?php endforeach; ?>
    </div>

    <?php else: ?>
    <!-- Empty State -->
    <div class="text-center py-5">
        <div class="bg-card-dark rounded-circle d-inline-flex align-items-center justify-content-center mb-4" style="width: 120px; height: 120px;">
            <span class="material-symbols-outlined text-secondary display-3">map</span>
        </div>
        <h2 class="h4 fw-bold text-white mb-3">No Roadmap Selected</h2>
        <p class="text-secondary mb-4">Please select a unit to view its learning path, or add a new one.</p>
        <a href="onboarding.php" class="btn bg-primary-orange text-white fw-bold py-3 px-5 rounded-pill glow-orange border-0 shadow-lg">
            Create Roadmap
        </a>
    </div>
    <?php endif; ?>

</main>

<?php include 'includes/footer.php'; ?>
