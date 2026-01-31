<?php 
require_once 'includes/auth.php';
require_once 'includes/db.php';
require_once 'includes/roadmap_helper.php';

requireAuth();
$user = getCurrentUser();

// Input parameters
$unitId = (int)($_GET['unit_id'] ?? 0);
$weekNum = (int)($_GET['week'] ?? 0);
$videoId = (int)($_GET['video'] ?? 0);

// Basic validation
if (!$unitId) {
    header('Location: dashboard.php');
    exit;
}

// Fetch Unit details
$unit = getUnit($unitId, $user['id']);
if (!$unit) {
    header('Location: dashboard.php?error=UnitNotFound');
    exit;
}

// Fetch Roadmap for this unit to find the specific week
$roadmapAll = getUnitRoadmap($unitId);
$activeWeek = null;

// Determine active week (default to current week or first week)
if ($weekNum > 0) {
    foreach ($roadmapAll as $week) {
        if ($week['week_number'] == $weekNum) {
            $activeWeek = $week;
            break;
        }
    }
} else {
    // If no week specified, find the 'current' status week, or default to week 1
    foreach ($roadmapAll as $week) {
        if ($week['status'] === 'current') {
            $activeWeek = $week;
            break;
        }
    }
    if (!$activeWeek && !empty($roadmapAll)) {
        $activeWeek = $roadmapAll[0];
    }
}

if (!$activeWeek) {
    // No roadmap generated yet
    header('Location: roadmap.php?unit_id=' . $unitId);
    exit;
}

// Fetch Videos for the active week
$videos = getWeekVideos($activeWeek['id']);
$currentVideo = null;

if (!empty($videos)) {
    if ($videoId) {
        foreach ($videos as $v) {
            if ($v['id'] == $videoId) {
                $currentVideo = $v;
                break;
            }
        }
    }
    
    // Default to first video if specific one not found or not provided
    if (!$currentVideo && !empty($videos)) {
        $currentVideo = $videos[0];
    }
}

include 'includes/header.php'; 
?>

<main class="container-fluid p-3 p-lg-4">
    <!-- Page Header -->
    <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-4">
        <div>
            <p class="text-primary-blue fw-bold small text-uppercase tracking-widest mb-1" style="font-size: 10px; letter-spacing: 0.2em;">
                <?php echo htmlspecialchars($unit['unit_code'] . ' - ' . $unit['unit_name']); ?>
            </p>
            <h1 class="h4 fw-bold mb-0">Week <?php echo $activeWeek['week_number']; ?>: <?php echo htmlspecialchars($activeWeek['week_title']); ?></h1>
        </div>
        <div class="d-flex gap-2">
            <a href="roadmap.php?unit_id=<?php echo $unitId; ?>" class="btn btn-outline-secondary rounded-pill px-3 d-flex align-items-center gap-2">
                <span class="material-symbols-outlined" style="font-size: 18px;">map</span>
                Back to Roadmap
            </a>
        </div>
    </div>

    <!-- Two Column Layout -->
    <div class="row g-4">
        <!-- Left Column: Video + Content -->
        <div class="col-12 col-xl-8">
            <!-- MediaPlayer Section -->
            <?php if ($currentVideo): ?>
            <div class="card bg-card-dark border border-white border-opacity-10 rounded-4 overflow-hidden mb-4">
                <div class="position-relative d-flex flex-column">
                    <div class="ratio ratio-16x9 bg-black">
                        <!-- YouTube Embed -->
                        <iframe 
                            src="https://www.youtube.com/embed/<?php echo htmlspecialchars($currentVideo['video_id']); ?>?rel=0" 
                            title="<?php echo htmlspecialchars($currentVideo['title']); ?>" 
                            frameborder="0" 
                            allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" 
                            allowfullscreen>
                        </iframe>
                    </div>
                </div>
                <div class="p-4">
                    <h2 class="h5 fw-bold text-white mb-2"><?php echo htmlspecialchars($currentVideo['title']); ?></h2>
                    <p class="text-secondary small mb-0"><?php echo htmlspecialchars($currentVideo['description'] ?? ''); ?></p>
                </div>
            </div>
            <?php else: ?>
            <div class="card bg-card-dark border border-white border-opacity-10 rounded-4 overflow-hidden mb-4 p-5 text-center">
                <div class="mb-3">
                    <span class="material-symbols-outlined text-secondary" style="font-size: 48px;">videocam_off</span>
                </div>
                <h3 class="h5 text-white">No Videos Available</h3>
                <p class="text-secondary small">This week doesn't have any video content curated yet.</p>
            </div>
            <?php endif; ?>

            <!-- Week Videos List -->
            <div class="card bg-card-dark border border-white border-opacity-10 rounded-4 overflow-hidden mb-4">
                <div class="card-header bg-transparent border-bottom border-secondary border-opacity-25 p-3">
                    <h3 class="h6 fw-bold text-white m-0">In This Module</h3>
                </div>
                <div class="list-group list-group-flush">
                    <?php foreach ($videos as $index => $v): 
                        $isActive = ($currentVideo && $currentVideo['id'] == $v['id']);
                        $bgClass = $isActive ? 'bg-primary-blue bg-opacity-10' : 'bg-transparent';
                    ?>
                    <a href="learning.php?unit_id=<?php echo $unitId; ?>&week=<?php echo $activeWeek['week_number']; ?>&video=<?php echo $v['id']; ?>" 
                       class="list-group-item list-group-item-action d-flex align-items-center gap-3 p-3 <?php echo $bgClass; ?> border-secondary border-opacity-10 text-white">
                        <div class="position-relative flex-shrink-0 rounded overflow-hidden" style="width: 120px; aspect-ratio: 16/9;">
                            <img src="<?php echo htmlspecialchars($v['thumbnail_url']); ?>" class="w-100 h-100 object-fit-cover" alt="Thumb">
                            <?php if ($isActive): ?>
                            <div class="position-absolute top-0 start-0 w-100 h-100 bg-black bg-opacity-50 d-flex align-items-center justify-content-center">
                                <span class="material-symbols-outlined text-white">play_circle</span>
                            </div>
                            <?php endif; ?>
                        </div>
                        <div class="flex-grow-1 min-width-0">
                            <h6 class="mb-1 text-truncate small fw-bold <?php echo $isActive ? 'text-primary-blue' : 'text-white'; ?>">
                                <?php echo htmlspecialchars($v['title']); ?>
                            </h6>
                            <small class="text-secondary d-block"><?php echo htmlspecialchars($v['channel_name']); ?></small>
                        </div>
                        <div class="text-secondary small">
                            <?php echo htmlspecialchars($v['duration']); ?>
                        </div>
                    </a>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>

        <!-- Right Column: Foundry Task + AI Coach -->
        <div class="col-12 col-xl-4">
            <!-- Foundry Task Card -->
            <div class="card bg-card-dark border border-white border-opacity-10 rounded-4 overflow-hidden mb-4">
                <div class="card-header bg-transparent border-bottom border-secondary border-opacity-25 p-3">
                    <div class="d-flex align-items-center justify-content-between">
                        <div class="d-flex align-items-center gap-2">
                            <div class="bg-primary-blue rounded-circle" style="width: 8px; height: 8px;"></div>
                            <h2 class="h6 fw-bold text-white m-0">Foundry Task</h2>
                        </div>
                        <span class="badge bg-warning bg-opacity-10 text-warning border border-warning border-opacity-25 small fw-bold text-uppercase">Project</span>
                    </div>
                </div>
                <div class="card-body p-3">
                    <p class="text-secondary small mb-3">
                        <?php echo htmlspecialchars($activeWeek['project_task'] ?? 'No specific task for this week.'); ?>
                    </p>

                    <!-- Submission Status / Link -->
                    <div class="bg-black bg-opacity-20 rounded-3 p-3 border border-white border-opacity-5 mb-3">
                        <div class="d-flex align-items-center gap-2 mb-2">
                            <span class="material-symbols-outlined text-success" style="font-size: 20px;">check_circle</span>
                            <span class="text-white small fw-bold">Task Verification</span>
                        </div>
                        <p class="text-secondary xsmall mb-0">Upload your solution in the Roadmap view to complete this week.</p>
                    </div>

                    <a href="roadmap.php?unit_id=<?php echo $unitId; ?>#week-<?php echo $activeWeek['week_number']; ?>" class="btn bg-primary-blue text-white fw-bold py-2 rounded-pill w-100 d-flex align-items-center justify-content-center gap-2">
                        <span class="material-symbols-outlined" style="font-size: 18px;">upload_file</span>
                        Go to Submission
                    </a>
                </div>
            </div>

            <!-- AI Coach Card -->
            <div class="card bg-card-dark border border-white border-opacity-10 rounded-4 overflow-hidden">
                <div class="card-header bg-transparent border-bottom border-secondary border-opacity-25 p-3">
                    <div class="d-flex align-items-center gap-2">
                        <div class="rounded-circle bg-primary-blue bg-opacity-25 d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                            <span class="material-symbols-outlined text-primary-blue">smart_toy</span>
                        </div>
                        <div>
                            <h4 class="h6 fw-bold mb-0">AI Coach</h4>
                            <span class="text-success small">
                                <span class="material-symbols-outlined" style="font-size: 10px;">circle</span>
                                Online
                            </span>
                        </div>
                    </div>
                </div>
                <div class="card-body p-3">
                    <div class="bg-primary-blue bg-opacity-10 border border-primary-blue border-opacity-25 rounded-3 p-3 mb-3">
                        <div class="d-flex align-items-start gap-2">
                            <span class="material-symbols-outlined text-primary-blue" style="font-size: 20px;">auto_awesome</span>
                            <div>
                                <p class="small fw-bold text-primary-blue mb-1">Coach Tip</p>
                                <p class="small mb-0" id="aiCoachText">Loading insights for <?php echo htmlspecialchars($activeWeek['week_title']); ?>...</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>

<script>
    async function fetchAICoach() {
        const textElement = document.getElementById('aiCoachText');
        // Simple stub for now - normally this would call an endpoint with context
        setTimeout(() => {
            const tips = [
                "Focus on the practical application of this concept.",
                "Don't forget to review the supplementary materials.",
                "This topic builds strongly on last week's work.",
                "Try implementing a small prototype to test your understanding."
            ];
            textElement.innerText = tips[Math.floor(Math.random() * tips.length)];
        }, 1500);
    }

    document.addEventListener('DOMContentLoaded', function() {
        fetchAICoach();
    });
</script>

<?php include 'includes/footer.php'; ?>
