<?php 
require_once 'includes/auth.php';
require_once 'includes/db.php';

requireAuth();
$user = getCurrentUser();
$db = db();

// Fetch Completed Topics
$stmt = $db->prepare("
    SELECT r.topics, r.status 
    FROM roadmaps r
    JOIN units u ON r.unit_id = u.id
    WHERE u.user_id = ? AND r.status = 'completed'
");
$stmt->execute([$user['id']]);
$rows = $stmt->fetchAll();

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
$topSkills = array_slice($allTopics, 0, 3);
$totalSkillsFound = count($allTopics);

// Determine Career Match (Heuristic)
$careerMatch = "Generalist Developer";
$careerScore = 85;

// If we have data, try to guess
if (!empty($allTopics)) {
    $keywords = array_keys($allTopics);
    $text = implode(' ', $keywords);
    
    if (stripos($text, 'data') !== false || stripos($text, 'learning') !== false || stripos($text, 'ai') !== false) {
        $careerMatch = "AI & Data Scientist";
        $careerScore = 92;
    } elseif (stripos($text, 'security') !== false || stripos($text, 'network') !== false) {
        $careerMatch = "Cybersecurity Analyst";
        $careerScore = 88;
    } elseif (stripos($text, 'design') !== false || stripos($text, 'user') !== false) {
        $careerMatch = "UX/UI Designer";
        $careerScore = 90;
    } elseif (stripos($text, 'web') !== false || stripos($text, 'html') !== false) {
        $careerMatch = "Full Stack Developer";
        $careerScore = 94;
    }
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
    <!-- Radar Chart Visualizer -->
    <section class="p-3 d-flex flex-column align-items-center">
        <div class="position-relative w-100 mt-4 d-flex align-items-center justify-content-center" style="aspect-ratio: 1/1; max-width: 320px;">
            <!-- Hexagonal Background Grid -->
            <style>
                .radar-grid { clip-path: polygon(50% 0%, 100% 25%, 100% 75%, 50% 100%, 0% 75%, 0% 25%); }
                .glow-blue-filter { filter: drop-shadow(0 0 8px rgba(17, 115, 212, 0.4)); }
            </style>
            <div class="position-absolute top-0 start-0 w-100 h-100 border border-secondary border-opacity-25 radar-grid opacity-25"></div>
            <div class="position-absolute border border-secondary border-opacity-25 radar-grid opacity-25" style="width: 75%; height: 75%;"></div>
            <div class="position-absolute border border-secondary border-opacity-25 radar-grid opacity-25" style="width: 50%; height: 50%;"></div>
            <div class="position-absolute border border-secondary border-opacity-25 radar-grid opacity-25" style="width: 25%; height: 25%;"></div>

            <!-- Radar Shape (Static for visual impact, theoretically mapped to skills) -->
            <div class="position-absolute w-100 h-100 glow-blue-filter"
                 style="clip-path: polygon(50% 15%, 85% 30%, 80% 80%, 40% 90%, 15% 70%, 20% 35%); background: rgba(13, 127, 242, 0.3); border: 2px solid var(--primary-blue);"></div>

            <!-- Labels -->
            <span class="position-absolute top-0 text-primary-blue fw-bold text-uppercase tracking-widest small" style="font-size: 10px;">Technical</span>
            <span class="position-absolute top-25 end-0 text-secondary fw-bold text-uppercase tracking-widest small" style="transform: rotate(90deg) translateX(50%); font-size: 10px; right: -10px;">Soft Skills</span>
            <span class="position-absolute bottom-25 end-0 text-secondary fw-bold text-uppercase tracking-widest small" style="transform: rotate(90deg) translateX(50%); font-size: 10px; right: -10px;">Practical</span>
            <span class="position-absolute bottom-0 text-secondary fw-bold text-uppercase tracking-widest small" style="font-size: 10px;">Theory</span>
            <span class="position-absolute bottom-25 start-0 text-primary-orange fw-bold text-uppercase tracking-widest small" style="transform: rotate(-90deg) translateX(-50%); font-size: 10px; left: -10px;">Problem Solving</span>
            <span class="position-absolute top-25 start-0 text-secondary fw-bold text-uppercase tracking-widest small" style="transform: rotate(-90deg) translateX(-50%); font-size: 10px; left: -10px;">Analytical</span>
        </div>

        <div class="mt-4 d-flex gap-4">
            <div class="d-flex align-items-center gap-2">
                <div class="rounded-circle bg-primary-blue" style="width: 8px; height: 8px;"></div>
                <span class="small text-secondary">Current Mastery</span>
            </div>
            <div class="d-flex align-items-center gap-2">
                <div class="rounded-circle bg-primary-orange" style="width: 8px; height: 8px;"></div>
                <span class="small text-secondary">Target Benchmark</span>
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
                $colors = ['bg-primary-blue', 'bg-primary-orange', 'bg-secondary'];
                $i = 0;
                foreach ($topSkills as $skill => $count): 
                    $percent = min(100, $count * 20); // Arbitrary scaling: 5 occurrences = 100%
                    $color = $colors[$i % 3];
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
                        <span class="badge bg-primary-blue text-white rounded px-1 fw-bold" style="font-size: 10px;"><?php echo $careerScore; ?>%</span>
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
        <div class="w-100 rounded-4 overflow-hidden position-relative shadow-sm"
             style="height: 128px; background-image: url('https://lh3.googleusercontent.com/aida-public/AB6AXuA-rgURQ77ai23wi1sEoI2aOwBToL925vxMEsc4iMUX80N3JJ1BShbwexhyowv7ClwgATKuhpEIB2EHyivVmjIXl6gI7lzQCy-c6B-JsWPrsCLPPaxqYlSxcMzeXZVfZzjf_QcfHEbWvFCbgQfQPI1X56ruJwrUVgUn6Abzd3VT2cLmHRkAIcWYI6YmsikDGSH55IDl5xyrPjhMm1-1Bl9_03_PH2-oqNIblG4TVwEi0gz-ECQ99hnk0unyfQln95DeTyBTvM5Inug'); background-size: cover; background-position: center;">
            <div class="position-absolute top-0 start-0 w-100 h-100 d-flex flex-column justify-content-end p-3" style="background: linear-gradient(to top, rgba(16,25,34,0.9), transparent);">
                <p class="small fw-bold text-primary-orange text-uppercase tracking-widest mb-1">Module Completed</p>
                <p class="fw-bold text-white mb-0"><?php echo htmlspecialchars($latestAchievement['unit_code']); ?>: <?php echo htmlspecialchars($latestAchievement['week_title']); ?></p>
            </div>
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
