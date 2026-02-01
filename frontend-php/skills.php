<?php 
require_once 'includes/auth.php';
require_once 'includes/db.php';

requireAuth();
$user = getCurrentUser();
$db = db();

// Fetch Completed Topics from roadmaps
$stmt = $db->prepare("
    SELECT r.topics, r.status, r.week_title, r.project_task
    FROM roadmaps r
    JOIN units u ON r.unit_id = u.id
    WHERE u.user_id = ? AND r.status = 'completed'
");
$stmt->execute([$user['id']]);
$rows = $stmt->fetchAll();

// Fetch submissions for practical work
$subStmt = $db->prepare("SELECT COUNT(*) as count FROM submissions WHERE user_id = ?");
$subStmt->execute([$user['id']]);
$submissionCount = $subStmt->fetch()['count'] ?? 0;

// Fetch units for skill breadth
$unitStmt = $db->prepare("SELECT COUNT(*) as count FROM units WHERE user_id = ?");
$unitStmt->execute([$user['id']]);
$unitCount = $unitStmt->fetch()['count'] ?? 0;

// Fetch total completed roadmap weeks
$completedWeeksStmt = $db->prepare("
    SELECT COUNT(*) as count FROM roadmaps r
    JOIN units u ON r.unit_id = u.id
    WHERE u.user_id = ? AND r.status = 'completed'
");
$completedWeeksStmt->execute([$user['id']]);
$completedWeeks = $completedWeeksStmt->fetch()['count'] ?? 0;

$allTopics = [];
$completedCount = 0;

foreach ($rows as $row) {
    $completedCount++;
    $topics = json_decode($row['topics'], true);
    if (is_array($topics)) {
        foreach ($topics as $t) {
            $t = trim($t);
            if (!isset($allTopics[$t])) {
                $allTopics[$t] = 0;
            }
            $allTopics[$t]++;
        }
    }
}

// Sort topics by frequency
arsort($allTopics);
$topSkills = array_slice($allTopics, 0, 5);
$totalSkillsFound = count($allTopics);

// Calculate 6 Skill Categories (0-100 scale) based on actual data
$skillCategories = [
    'Technical' => 0,
    'Soft Skills' => 0,
    'Practical' => 0,
    'Theory' => 0,
    'Problem Solving' => 0,
    'Analytical' => 0
];

// Technical: Based on number of topics learned
$skillCategories['Technical'] = min(100, $totalSkillsFound * 8);

// Soft Skills: Based on completed weeks (showing consistency)
$skillCategories['Soft Skills'] = min(100, $completedWeeks * 12);

// Practical: Based on actual project submissions
$skillCategories['Practical'] = min(100, $submissionCount * 20);

// Theory: Based on completed roadmap weeks
$skillCategories['Theory'] = min(100, $completedWeeks * 10);

// Problem Solving: Based on topics containing keywords
$problemSolvingKeywords = ['algorithm', 'debug', 'problem', 'logic', 'design', 'architecture'];
$psCount = 0;
foreach ($allTopics as $topic => $count) {
    foreach ($problemSolvingKeywords as $kw) {
        if (stripos($topic, $kw) !== false) {
            $psCount += $count;
            break;
        }
    }
}
$skillCategories['Problem Solving'] = min(100, $psCount * 15);

// Analytical: Based on data/analysis related topics
$analyticalKeywords = ['data', 'analysis', 'sql', 'statistics', 'machine', 'learning', 'ai', 'model'];
$anCount = 0;
foreach ($allTopics as $topic => $count) {
    foreach ($analyticalKeywords as $kw) {
        if (stripos($topic, $kw) !== false) {
            $anCount += $count;
            break;
        }
    }
}
$skillCategories['Analytical'] = min(100, $anCount * 15);

// Calculate radar polygon points (hexagon: 6 points)
// Points are positioned at 60-degree intervals, starting from top
function calculateRadarPoint($score, $index, $maxRadius = 45) {
    $angle = (-90 + ($index * 60)) * (M_PI / 180); // Start from top, go clockwise
    $radius = ($score / 100) * $maxRadius;
    $x = 50 + ($radius * cos($angle));
    $y = 50 + ($radius * sin($angle));
    return [$x, $y];
}

$radarPoints = [];
$i = 0;
foreach ($skillCategories as $category => $score) {
    $point = calculateRadarPoint($score, $i);
    $radarPoints[] = $point[0] . '% ' . $point[1] . '%';
    $i++;
}
$radarPolygon = implode(', ', $radarPoints);

// Determine Career Match (Heuristic based on skill profile)
$careerMatch = "Generalist Developer";
$careerScore = max(50, min(95, array_sum($skillCategories) / 6));

// Determine best career based on highest skills
$maxSkill = max($skillCategories);
$topCategory = array_search($maxSkill, $skillCategories);

if ($skillCategories['Analytical'] >= 60 || stripos(implode(' ', array_keys($allTopics)), 'data') !== false) {
    $careerMatch = "AI & Data Scientist";
    $careerScore = min(98, $skillCategories['Analytical'] + 20);
} elseif ($skillCategories['Practical'] >= 70) {
    $careerMatch = "Full Stack Developer";
    $careerScore = min(98, $skillCategories['Practical'] + 15);
} elseif ($skillCategories['Problem Solving'] >= 60) {
    $careerMatch = "Software Architect";
    $careerScore = min(98, $skillCategories['Problem Solving'] + 18);
} elseif ($skillCategories['Technical'] >= 70) {
    $careerMatch = "Backend Engineer";
    $careerScore = min(98, $skillCategories['Technical'] + 12);
}

// Fetch Latest Achievement (Last completed week)
$achieveStmt = $db->prepare("
    SELECT r.week_title, u.unit_code 
    FROM roadmaps r
    JOIN units u ON r.unit_id = u.id
    WHERE u.user_id = ? AND r.status = 'completed'
    ORDER BY r.id DESC LIMIT 1
");
$achieveStmt->execute([$user['id']]);
$latestAchievement = $achieveStmt->fetch();

// Get user interests for display
$userInterests = $user['interests'] ?? '';
$interestsList = array_filter(array_map('trim', explode(',', $userInterests)));

include 'includes/header.php'; 
?>

<!-- Top App Bar -->
<header class="sticky-top bg-background-dark glass-effect border-bottom border-secondary border-opacity-25 z-3">
    <div class="d-flex align-items-center p-3 justify-content-between">
        <a href="dashboard.php" class="btn btn-link text-secondary p-0 d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
            <span class="material-symbols-outlined">arrow_back</span>
        </a>
        <h1 class="h5 fw-bold text-white m-0 text-center flex-grow-1">Skills Radar</h1>
        <div class="d-flex justify-content-end" style="width: 40px;">
        </div>
    </div>
</header>

<main class="container px-0 pb-5 mb-5">
    <!-- Stats Summary -->
    <section class="px-3 pt-3">
        <div class="row g-2">
            <div class="col-4">
                <div class="bg-card-dark border border-secondary border-opacity-25 rounded-3 p-2 text-center">
                    <span class="d-block h4 fw-bold text-primary-blue mb-0"><?php echo $completedWeeks; ?></span>
                    <span class="small text-secondary">Weeks Done</span>
                </div>
            </div>
            <div class="col-4">
                <div class="bg-card-dark border border-secondary border-opacity-25 rounded-3 p-2 text-center">
                    <span class="d-block h4 fw-bold text-primary-orange mb-0"><?php echo $submissionCount; ?></span>
                    <span class="small text-secondary">Projects</span>
                </div>
            </div>
            <div class="col-4">
                <div class="bg-card-dark border border-secondary border-opacity-25 rounded-3 p-2 text-center">
                    <span class="d-block h4 fw-bold text-success mb-0"><?php echo $totalSkillsFound; ?></span>
                    <span class="small text-secondary">Skills</span>
                </div>
            </div>
        </div>
    </section>

    <!-- Radar Chart Visualizer -->
    <section class="p-3 d-flex flex-column align-items-center">
        <div class="position-relative w-100 mt-4 d-flex align-items-center justify-content-center" style="aspect-ratio: 1/1; max-width: 320px;">
            <!-- Hexagonal Background Grid -->
            <style>
                .radar-grid { clip-path: polygon(50% 0%, 100% 25%, 100% 75%, 50% 100%, 0% 75%, 0% 25%); }
                .glow-blue-filter { filter: drop-shadow(0 0 8px rgba(13, 127, 242, 0.4)); }
            </style>
            <div class="position-absolute top-0 start-0 w-100 h-100 border border-secondary border-opacity-25 radar-grid opacity-25"></div>
            <div class="position-absolute border border-secondary border-opacity-25 radar-grid opacity-25" style="width: 75%; height: 75%;"></div>
            <div class="position-absolute border border-secondary border-opacity-25 radar-grid opacity-25" style="width: 50%; height: 50%;"></div>
            <div class="position-absolute border border-secondary border-opacity-25 radar-grid opacity-25" style="width: 25%; height: 25%;"></div>

            <!-- Radar Shape - DYNAMIC based on skill scores -->
            <div class="position-absolute w-100 h-100 glow-blue-filter"
                 style="clip-path: polygon(<?php echo $radarPolygon; ?>); background: rgba(13, 127, 242, 0.3); border: 2px solid var(--primary-blue);"></div>

            <!-- Labels with dynamic scores -->
            <span class="position-absolute top-0 text-primary-blue fw-bold text-uppercase tracking-widest small" style="font-size: 10px;">Technical (<?php echo $skillCategories['Technical']; ?>%)</span>
            <span class="position-absolute top-25 end-0 text-secondary fw-bold text-uppercase tracking-widest small" style="transform: rotate(90deg) translateX(50%); font-size: 9px; right: -20px;">Soft Skills</span>
            <span class="position-absolute bottom-25 end-0 text-secondary fw-bold text-uppercase tracking-widest small" style="transform: rotate(90deg) translateX(50%); font-size: 10px; right: -10px;">Practical</span>
            <span class="position-absolute bottom-0 text-secondary fw-bold text-uppercase tracking-widest small" style="font-size: 10px;">Theory (<?php echo $skillCategories['Theory']; ?>%)</span>
            <span class="position-absolute bottom-25 start-0 text-primary-orange fw-bold text-uppercase tracking-widest small" style="transform: rotate(-90deg) translateX(-50%); font-size: 9px; left: -20px;">Problem Solving</span>
            <span class="position-absolute top-25 start-0 text-secondary fw-bold text-uppercase tracking-widest small" style="transform: rotate(-90deg) translateX(-50%); font-size: 10px; left: -10px;">Analytical</span>
        </div>

        <div class="mt-4 d-flex gap-4">
            <div class="d-flex align-items-center gap-2">
                <div class="rounded-circle bg-primary-blue" style="width: 8px; height: 8px;"></div>
                <span class="small text-secondary">Current Mastery</span>
            </div>
        </div>
    </section>

    <!-- Top Skills Breakdown -->
    <section class="mt-3 px-3">
        <div class="d-flex align-items-center justify-content-between mb-3">
            <h3 class="h5 fw-bold text-white m-0">Top Skills Breakdown</h3>
            <span class="small fw-bold text-primary-blue"><?php echo $totalSkillsFound; ?> detected</span>
        </div>
        <div class="d-flex flex-column gap-3">
            <?php if (empty($topSkills)): ?>
                <div class="text-center py-3 text-secondary small">
                    Complete roadmap weeks to unlock skill insights.
                </div>
            <?php else: 
                $colors = ['bg-primary-blue', 'bg-primary-orange', 'bg-success', 'bg-info', 'bg-warning'];
                $i = 0;
                foreach ($topSkills as $skill => $count): 
                    $percent = min(100, $count * 20);
                    $color = $colors[$i % count($colors)];
                    $i++;
            ?>
            <!-- Skill Item -->
            <div class="bg-card-dark border border-secondary border-opacity-25 p-3 rounded-4 bg-opacity-50">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <span class="fw-semibold small"><?php echo htmlspecialchars($skill); ?></span>
                    <span class="small fw-bold <?php echo str_replace('bg-', 'text-', $color); ?>"><?php echo $percent; ?>%</span>
                </div>
                <div class="progress bg-secondary bg-opacity-25" style="height: 6px;">
                    <div class="progress-bar <?php echo $color; ?> rounded-pill" role="progressbar" style="width: <?php echo $percent; ?>%"></div>
                </div>
            </div>
            <?php endforeach; endif; ?>
        </div>
    </section>

    <!-- Skill Categories Breakdown -->
    <section class="mt-4 px-3">
        <h3 class="h5 fw-bold text-white mb-3">Category Breakdown</h3>
        <div class="row g-2">
            <?php 
            $categoryIcons = [
                'Technical' => 'code',
                'Soft Skills' => 'groups',
                'Practical' => 'build',
                'Theory' => 'menu_book',
                'Problem Solving' => 'psychology',
                'Analytical' => 'analytics'
            ];
            foreach ($skillCategories as $cat => $score): 
                $icon = $categoryIcons[$cat] ?? 'star';
            ?>
            <div class="col-6">
                <div class="bg-card-dark border border-secondary border-opacity-25 rounded-3 p-3">
                    <div class="d-flex align-items-center gap-2 mb-2">
                        <span class="material-symbols-outlined text-primary-blue" style="font-size: 1.25rem;"><?php echo $icon; ?></span>
                        <span class="small fw-semibold"><?php echo $cat; ?></span>
                    </div>
                    <div class="progress bg-secondary bg-opacity-25" style="height: 4px;">
                        <div class="progress-bar bg-primary-blue rounded-pill" style="width: <?php echo $score; ?>%"></div>
                    </div>
                    <span class="small text-secondary d-block mt-1"><?php echo $score; ?>%</span>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </section>

    <!-- AI Career Prediction -->
    <section class="mt-4 px-3">
        <div class="position-relative overflow-hidden p-4 rounded-4 border border-primary border-opacity-25"
             style="background: linear-gradient(135deg, rgba(13, 127, 242, 0.2), rgba(249, 128, 6, 0.1));">
            <div class="position-relative z-2">
                <div class="d-flex align-items-center gap-2 mb-3">
                    <span class="material-symbols-outlined text-primary-blue fs-5">psychology</span>
                    <h3 class="h6 fw-bold m-0">AI Career Prediction</h3>
                </div>
                <p class="small text-secondary mb-3">Based on your proficiency in completed units, you have a high match for:</p>
                <div class="d-flex flex-wrap gap-2">
                    <div class="bg-white bg-opacity-10 backdrop-blur px-3 py-1 rounded-pill d-flex align-items-center gap-2 border border-white border-opacity-10">
                        <span class="small fw-bold"><?php echo htmlspecialchars($careerMatch); ?></span>
                        <span class="badge bg-primary-blue text-white rounded px-1 fw-bold" style="font-size: 10px;"><?php echo round($careerScore); ?>%</span>
                    </div>
                </div>
            </div>
            <!-- Abstract Glow -->
            <div class="position-absolute bottom-0 end-0 rounded-circle bg-primary-blue bg-opacity-10 blur-3xl" style="width: 128px; height: 128px; filter: blur(40px); transform: translate(30%, 30%);"></div>
        </div>
    </section>

    <!-- Latest Achievement -->
    <?php if ($latestAchievement): ?>
    <section class="mt-4 px-3 mb-5">
        <h3 class="h5 fw-bold text-white mb-3">Latest Achievement</h3>
        <div class="w-100 rounded-4 overflow-hidden position-relative shadow-sm bg-card-dark border border-secondary border-opacity-25" style="height: 100px;">
            <div class="position-absolute top-0 start-0 w-100 h-100 d-flex flex-column justify-content-center p-3">
                <p class="small fw-bold text-primary-orange text-uppercase tracking-widest mb-1">Module Completed</p>
                <p class="fw-bold text-white mb-0"><?php echo htmlspecialchars($latestAchievement['unit_code']); ?>: <?php echo htmlspecialchars($latestAchievement['week_title']); ?></p>
            </div>
        </div>
    </section>
    <?php else: ?>
    <section class="mt-4 px-3 mb-5">
        <div class="text-center py-4 bg-card-dark rounded-4 border border-secondary border-opacity-25">
            <span class="material-symbols-outlined text-secondary mb-2" style="font-size: 48px;">emoji_events</span>
            <p class="text-secondary small mb-0">Complete roadmap weeks to earn achievements!</p>
        </div>
    </section>
    <?php endif; ?>
</main>

<!-- Bottom Navigation Bar -->
<nav class="fixed-bottom bg-background-dark glass-effect border-top border-secondary border-opacity-25 px-4 py-3 d-flex justify-content-between align-items-center z-3">
    <a href="dashboard.php" class="btn btn-link text-secondary text-decoration-none p-0 d-flex flex-column align-items-center gap-1">
        <span class="material-symbols-outlined">home</span>
        <span class="small fw-medium" style="font-size: 10px;">Home</span>
    </a>
    <a href="skills.php" class="btn btn-link text-primary-blue text-decoration-none p-0 d-flex flex-column align-items-center gap-1">
        <span class="material-symbols-outlined filled">analytics</span>
        <span class="small fw-medium" style="font-size: 10px;">Radar</span>
    </a>
    <a href="certificates.php" class="btn btn-link text-secondary text-decoration-none p-0 d-flex flex-column align-items-center gap-1">
        <span class="material-symbols-outlined">workspace_premium</span>
        <span class="small fw-medium" style="font-size: 10px;">Certs</span>
    </a>
    <a href="portfolio.php" class="btn btn-link text-secondary text-decoration-none p-0 d-flex flex-column align-items-center gap-1">
        <span class="material-symbols-outlined">description</span>
        <span class="small fw-medium" style="font-size: 10px;">Portfolio</span>
    </a>
</nav>

<?php include 'includes/footer.php'; ?>
