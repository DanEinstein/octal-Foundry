<?php 
require_once 'includes/auth.php';
require_once 'includes/roadmap_helper.php';

requireAuth();

$user = getCurrentUser();
$units = getUserUnits($user['id']);

// Determine which unit to show (default to most recent, or specific ID)
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

// Get current week and videos for the active unit
$currentWeek = null;
$weekVideos = [];
if ($activeUnit) {
    $currentWeek = getCurrentWeek($activeUnit['id']);
    if (!$currentWeek) {
        // Fallback to first week if no 'current' week found (e.g. all locked or completed)
        $roadmap = getUnitRoadmap($activeUnit['id']);
        $currentWeek = $roadmap[0] ?? null;
    }
    
    if ($currentWeek) {
        $weekVideos = getWeekVideos($currentWeek['id']);
    }
}

// Handle video navigation
$currentVideoIndex = (int)($_GET['v'] ?? 0);
$currentVideo = $weekVideos[$currentVideoIndex] ?? ($weekVideos[0] ?? null);

include 'includes/header.php'; 
?>

<!-- Dashboard Main Content -->
<main class="container-fluid p-3 p-lg-4">
    <!-- Welcome Section -->
    <div class="mb-4 d-flex justify-content-between align-items-end">
        <div>
            <h1 class="h4 fw-bold mb-1">Welcome back, <?php echo htmlspecialchars($user['full_name']); ?></h1>
            <p class="text-secondary mb-0">
                <?php if ($activeUnit): ?>
                    <?php echo htmlspecialchars($activeUnit['unit_code']); ?> - Week <?php echo $currentWeek['week_number'] ?? 1; ?> of 12
                <?php else: ?>
                    Ready to start your learning journey?
                <?php endif; ?>
            </p>
        </div>
        <?php if (empty($units)): ?>
        <a href="onboarding.php" class="btn bg-primary-orange text-white fw-bold glow-orange border-0 rounded-pill px-4">
            <span class="material-symbols-outlined me-2">add</span>
            Add First Unit
        </a>
        <?php endif; ?>
    </div>

    <?php if ($activeUnit): ?>
    <!-- Current Unit Progress -->
    <div class="card bg-card-dark border border-white border-opacity-10 rounded-4 p-3 mb-4">
        <div class="d-flex flex-wrap align-items-center justify-content-between gap-3">
            <div class="d-flex align-items-center gap-3">
                <div class="dropdown">
                    <button class="btn btn-outline-secondary border-0 p-0" type="button" data-bs-toggle="dropdown">
                        <span class="material-symbols-outlined text-secondary fs-4">expand_more</span>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-dark shadow-lg border-secondary border-opacity-25">
                        <?php foreach ($units as $u): ?>
                        <li>
                            <a class="dropdown-item <?php echo $u['id'] == $activeUnit['id'] ? 'active' : ''; ?>" 
                               href="?unit_id=<?php echo $u['id']; ?>">
                                <?php echo htmlspecialchars($u['unit_code']); ?>
                            </a>
                        </li>
                        <?php endforeach; ?>
                        <li><hr class="dropdown-divider border-white border-opacity-10"></li>
                        <li>
                            <a class="dropdown-item text-primary-orange" href="onboarding.php">
                                <span class="material-symbols-outlined fs-6 align-middle me-2">add_circle</span>
                                Add New Unit
                            </a>
                        </li>
                    </ul>
                </div>
                <div>
                    <span class="badge bg-primary-blue bg-opacity-25 text-primary-blue small fw-bold mb-2">
                        <?php echo htmlspecialchars($activeUnit['unit_code']); ?>
                    </span>
                    <h2 class="h6 fw-bold mb-1"><?php echo htmlspecialchars($activeUnit['unit_name']); ?></h2>
                    <p class="text-secondary small mb-0">
                        <?php echo htmlspecialchars($currentWeek['week_title'] ?? 'Course Started'); ?>
                    </p>
                </div>
            </div>
            <div class="text-end">
                <div class="d-flex align-items-center gap-2 mb-1">
                    <span class="text-primary-orange fw-bold"><?php echo $activeUnit['progress_percent']; ?>%</span>
                    <span class="text-secondary small">complete</span>
                </div>
                <div class="progress bg-secondary bg-opacity-25" style="width: 150px; height: 6px;">
                    <div class="progress-bar bg-primary-orange" style="width: <?php echo $activeUnit['progress_percent']; ?>%"></div>
                </div>
            </div>
        </div>
    </div>

    <!-- Two Column Layout: Video Player + AI Coach -->
    <div class="row g-4">
        <!-- Left Column: YouTube Video Player -->
        <div class="col-12 col-xl-8">
            <div class="card bg-card-dark border border-white border-opacity-10 rounded-4 overflow-hidden h-100">
                <?php if ($currentVideo): ?>
                <!-- Video Header -->
                <div class="card-header bg-transparent border-bottom border-secondary border-opacity-25 p-3">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <span class="badge bg-primary-orange bg-opacity-25 text-primary-orange small fw-bold mb-1">
                                Video <?php echo $currentVideo['position']; ?> of <?php echo count($weekVideos); ?>
                            </span>
                            <h3 class="h6 fw-bold mb-0 text-truncate" style="max-width: 400px;">
                                <?php echo htmlspecialchars($currentVideo['title']); ?>
                            </h3>
                        </div>
                        <div class="d-flex gap-2">
                            <button class="btn btn-sm btn-outline-secondary rounded-pill px-3">
                                <span class="material-symbols-outlined me-1" style="font-size: 16px;">bookmark</span>
                                Save
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Video Player (16:9 Responsive) -->
                <div class="ratio ratio-16x9 bg-black">
                    <iframe 
                        src="https://www.youtube.com/embed/<?php echo htmlspecialchars($currentVideo['video_id']); ?>" 
                        title="<?php echo htmlspecialchars($currentVideo['title']); ?>" 
                        frameborder="0" 
                        allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" 
                        allowfullscreen>
                    </iframe>
                </div>

                <!-- Video Info -->
                <div class="card-body p-3">
                    <div class="d-flex flex-wrap align-items-center gap-3 mb-3">
                        <div class="d-flex align-items-center gap-2">
                            <div class="rounded-circle bg-danger d-flex align-items-center justify-content-center" style="width: 32px; height: 32px;">
                                <span class="text-white fw-bold" style="font-size: 12px;">YT</span>
                            </div>
                            <span class="small fw-medium"><?php echo htmlspecialchars($currentVideo['channel_name']); ?></span>
                        </div>
                        <?php if ($currentVideo['view_count']): ?>
                        <span class="text-secondary small"><?php echo htmlspecialchars($currentVideo['view_count']); ?> views</span>
                        <?php endif; ?>
                        <?php if ($currentVideo['duration']): ?>
                        <span class="text-secondary small"><?php echo htmlspecialchars($currentVideo['duration']); ?> duration</span>
                        <?php endif; ?>
                    </div>
                    <p class="text-secondary small mb-0 text-truncate-2">
                        <?php echo htmlspecialchars($currentVideo['description']); ?>
                    </p>
                </div>

                <!-- Video Navigation -->
                <div class="card-footer bg-transparent border-top border-secondary border-opacity-25 p-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <a href="?unit_id=<?php echo $activeUnitId; ?>&v=<?php echo max(0, $currentVideoIndex - 1); ?>" 
                           class="btn btn-outline-secondary btn-sm rounded-pill px-3 <?php echo $currentVideoIndex === 0 ? 'disabled' : ''; ?>">
                            <span class="material-symbols-outlined me-1" style="font-size: 16px;">arrow_back</span>
                            Previous
                        </a>
                        <div class="text-center">
                            <span class="text-secondary small">Video <?php echo $currentVideoIndex + 1; ?> of <?php echo count($weekVideos); ?></span>
                        </div>
                        <a href="?unit_id=<?php echo $activeUnitId; ?>&v=<?php echo min(count($weekVideos) - 1, $currentVideoIndex + 1); ?>" 
                           class="btn btn-primary-orange btn-sm rounded-pill px-3 bg-primary-orange border-0 <?php echo $currentVideoIndex === count($weekVideos) - 1 ? 'disabled' : ''; ?>">
                            Next
                            <span class="material-symbols-outlined ms-1" style="font-size: 16px;">arrow_forward</span>
                        </a>
                    </div>
                </div>
                <?php else: ?>
                <div class="p-5 text-center">
                    <span class="material-symbols-outlined text-secondary display-1 mb-3">videocam_off</span>
                    <h3 class="h5 text-white">No videos available</h3>
                    <p class="text-secondary">This week doesn't have any curated videos yet.</p>
                </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- Right Column: AI Performance Coach Widget -->
        <div class="col-12 col-xl-4">
            <div class="card bg-card-dark border border-white border-opacity-10 rounded-4 h-100 d-flex flex-column">
                <!-- Coach Header -->
                <div class="card-header bg-transparent border-bottom border-secondary border-opacity-25 p-3">
                    <div class="d-flex align-items-center gap-2">
                        <div class="rounded-circle bg-primary-blue bg-opacity-25 d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                            <span class="material-symbols-outlined text-primary-blue">psychology</span>
                        </div>
                        <div>
                            <h4 class="h6 fw-bold mb-0">AI Performance Coach</h4>
                            <span class="text-success small">
                                <span class="material-symbols-outlined" style="font-size: 12px;">circle</span>
                                Online
                            </span>
                        </div>
                    </div>
                </div>

                <!-- Coach Messages -->
                <div class="card-body p-3 flex-grow-1 overflow-auto" style="max-height: 400px;">
                    <?php if ($currentWeek): ?>
                    <!-- AI Message -->
                    <div class="d-flex gap-2 mb-3">
                        <div class="rounded-circle bg-primary-blue bg-opacity-25 flex-shrink-0 d-flex align-items-center justify-content-center" style="width: 32px; height: 32px;">
                            <span class="material-symbols-outlined text-primary-blue" style="font-size: 18px;">smart_toy</span>
                        </div>
                        <div class="glass-panel rounded-4 p-3 flex-grow-1">
                            <p class="small mb-2">Welcome to Week <?php echo $currentWeek['week_number']; ?>! I've curated <?php echo count($weekVideos); ?> videos for you.</p>
                            <p class="small mb-0 text-secondary">Topic: <?php echo htmlspecialchars($currentWeek['week_title']); ?></p>
                        </div>
                    </div>

                    <?php if (!empty($currentWeek['week_description'])): ?>
                    <!-- Description Card -->
                    <div class="bg-primary-blue bg-opacity-10 border border-primary-blue border-opacity-25 rounded-4 p-3 mb-3 ms-5">
                        <div class="d-flex align-items-start gap-2">
                            <span class="material-symbols-outlined text-primary-blue" style="font-size: 20px;">info</span>
                            <div>
                                <p class="small fw-bold text-primary-blue mb-1">Week Overview</p>
                                <p class="small mb-0"><?php echo htmlspecialchars($currentWeek['week_description']); ?></p>
                            </div>
                        </div>
                    </div>
                    <?php endif; ?>
                    <?php else: ?>
                    <!-- No active week -->
                    <div class="text-center py-4">
                        <span class="material-symbols-outlined text-secondary mb-2" style="font-size: 48px;">school</span>
                        <p class="text-secondary small">No active roadmap yet. Add a unit to get started!</p>
                        <a href="onboarding.php" class="btn btn-primary-blue btn-sm rounded-pill px-3">Add Your First Unit</a>
                    </div>
                    <?php endif; ?>
                </div>

                <!-- Coach Input -->
                <div class="card-footer bg-transparent border-top border-secondary border-opacity-25 p-3">
                    <div class="input-group">
                        <input type="text" class="form-control form-control-dark border-secondary border-opacity-25 rounded-start-pill" placeholder="Ask your AI coach...">
                        <button class="btn bg-primary-orange text-white rounded-end-pill px-3 border-0">
                            <span class="material-symbols-outlined" style="font-size: 20px;">send</span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Weekly Tasks Section -->
    <div class="mt-4">
        <div class="d-flex align-items-center justify-content-between mb-3">
            <h3 class="h5 fw-bold mb-0">This Week's Topics</h3>
            <a href="roadmap.php?unit_id=<?php echo $activeUnitId; ?>" class="text-primary-blue text-decoration-none small fw-bold">View Full Roadmap</a>
        </div>
        <div class="row g-3">
            <?php 
            $topics = json_decode($currentWeek['topics'] ?? '[]', true);
            foreach ($topics as $index => $topic): 
            ?>
            <!-- Topic Card -->
            <div class="col-12 col-md-6 col-lg-3">
                <div class="card bg-card-dark border border-white border-opacity-10 rounded-4 p-3 h-100">
                    <div class="d-flex align-items-center gap-2 mb-2">
                        <div class="rounded-circle bg-primary-orange d-flex align-items-center justify-content-center" style="width: 24px; height: 24px;">
                            <span class="material-symbols-outlined text-white" style="font-size: 16px;">play_arrow</span>
                        </div>
                        <span class="badge bg-primary-orange bg-opacity-25 text-primary-orange small">In Progress</span>
                    </div>
                    <h4 class="small fw-bold mb-1">Topic <?php echo $index + 1; ?></h4>
                    <p class="text-secondary small mb-0"><?php echo htmlspecialchars($topic); ?></p>
                </div>
            </div>
            <?php endforeach; ?>
            
            <?php if (empty($topics)): ?>
            <div class="col-12">
                <p class="text-secondary text-center py-4">No specific topics listed for this week.</p>
            </div>
            <?php endif; ?>
        </div>
    </div>

    <?php else: ?>
    <!-- No Units View -->
    <div class="text-center py-5">
        <div class="bg-card-dark rounded-circle d-inline-flex align-items-center justify-content-center mb-4" style="width: 120px; height: 120px;">
            <span class="material-symbols-outlined text-secondary display-3">school</span>
        </div>
        <h2 class="h4 fw-bold text-white mb-3">No Units Found</h2>
        <p class="text-secondary mb-4" style="max-width: 400px; margin: 0 auto;">
            It looks like you haven't added any course units yet. Start by adding a unit to generate your personalized learning roadmap.
        </p>
        <a href="onboarding.php" class="btn bg-primary-orange text-white fw-bold py-3 px-5 rounded-pill glow-orange border-0 shadow-lg">
            Create Your First Roadmap
        </a>
    </div>
    <?php endif; ?>

</main>

<?php include 'includes/footer.php'; ?>
