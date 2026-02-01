<?php
/**
 * New Onboarding Page
 * Collects course info, uploads units file, and gets AI recommendations
 */

require_once 'includes/auth.php';
require_once 'includes/api_client.php';
require_once 'includes/roadmap_helper.php';

requireAuth();

$user = getCurrentUser();
$error = null;
$success = null;
$step = (int)($_GET['step'] ?? 1);
$recommendations = null;

// Handle form submissions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!validateCsrfToken($_POST['csrf_token'] ?? '')) {
        $error = 'Security validation failed. Please try again.';
    } else {
        $action = $_POST['action'] ?? '';
        
        if ($action === 'analyze') {
            // Step 1-3: Collect info and analyze
            $courseName = trim($_POST['course_name'] ?? '');
            $yearOfStudy = (int)($_POST['year_of_study'] ?? 1);
            $currentSemester = (int)($_POST['current_semester'] ?? 1);
            $interests = array_filter(array_map('trim', explode(',', $_POST['interests'] ?? '')));
            
            // Handle file upload - send to backend for parsing
            $units = [];
            if (isset($_FILES['units_file']) && $_FILES['units_file']['error'] === UPLOAD_ERR_OK) {
                $tmpPath = $_FILES['units_file']['tmp_name'];
                $fileName = $_FILES['units_file']['name'];
                
                // Send file to backend for parsing (supports TXT, CSV, DOCX, XLSX, images)
                $api = new ApiClient();
                $parseResult = $api->parseUnitsFile($tmpPath, $fileName);
                
                if ($parseResult['success'] && !empty($parseResult['units'])) {
                    $units = $parseResult['units'];
                } else {
                    $error = 'Failed to parse file: ' . ($parseResult['error'] ?? 'Unknown format');
                }
            }
            
            if (empty($courseName)) {
                $error = 'Please enter your course name.';
            } elseif (empty($units)) {
                $error = 'Please upload a file with your units.';
            } else {
                // Save to session for use in next step
                $_SESSION['onboarding'] = [
                    'course_name' => $courseName,
                    'year_of_study' => $yearOfStudy,
                    'current_semester' => $currentSemester,
                    'interests' => $interests,
                    'units' => $units
                ];
                
                // Call AI to analyze
                $api = new ApiClient();
                $response = $api->analyzeCurriculum([
                    'course_name' => $courseName,
                    'year_of_study' => $yearOfStudy,
                    'current_semester' => $currentSemester,
                    'units' => $units,
                    'interests' => $interests
                ]);
                
                if ($response['success'] && !empty($response['recommended_courses'])) {
                    $_SESSION['onboarding']['recommendations'] = $response;
                    header('Location: onboarding.php?step=2');
                    exit;
                } else {
                    $error = 'AI Analysis failed: ' . ($response['error'] ?? 'Unknown error');
                }
            }
        } elseif ($action === 'confirm') {
            // Step 2: User selected a recommended course
            $selectedCourse = $_POST['selected_course'] ?? '';
            $onboarding = $_SESSION['onboarding'] ?? [];
            
            if (empty($selectedCourse) || empty($onboarding)) {
                $error = 'Please select a course.';
            } else {
                // Update user profile
                $db = db();
                $stmt = $db->prepare("UPDATE users SET course_name = ?, year_of_study = ?, current_semester = ?, interests = ? WHERE id = ?");
                $stmt->execute([
                    $onboarding['course_name'],
                    $onboarding['year_of_study'],
                    $onboarding['current_semester'],
                    implode(',', $onboarding['interests'] ?? []),
                    $user['id']
                ]);
                
                // Save uploaded units
                $unitStmt = $db->prepare("INSERT INTO unit_uploads (user_id, unit_code, unit_name) VALUES (?, ?, ?)");
                foreach ($onboarding['units'] as $unit) {
                    $unitStmt->execute([$user['id'], $unit['unit_code'], $unit['unit_name']]);
                }
                
                // Create the recommended practical course as a unit
                $unitId = createUnit(
                    $user['id'], 
                    'SKILL-001', 
                    $selectedCourse, 
                    'AI Recommended', 
                    'Semester ' . $onboarding['current_semester'], 
                    date('Y')
                );
                
                if ($unitId) {
                    // Generate roadmap for this course
                    $api = new ApiClient();
                    $roadmapResponse = $api->generateRoadmap([
                        'unit_code' => 'SKILL-001',
                        'unit_name' => $selectedCourse,
                        'career_path' => implode(', ', $onboarding['interests'] ?? [])
                    ]);
                    
                    if ($roadmapResponse['success'] && !empty($roadmapResponse['roadmap'])) {
                        saveRoadmapToDatabase($unitId, $roadmapResponse['roadmap']);
                    }
                    
                    // Clear session
                    unset($_SESSION['onboarding']);
                    
                    header('Location: dashboard.php?welcome=1');
                    exit;
                } else {
                    $error = 'Failed to create course.';
                }
            }
        }
    }
}

// Load recommendations from session for step 2
if ($step === 2 && isset($_SESSION['onboarding']['recommendations'])) {
    $recommendations = $_SESSION['onboarding']['recommendations'];
}

include 'includes/header.php';
?>

<!-- Loading Overlay -->
<div id="loadingOverlay" class="d-none position-fixed top-0 start-0 w-100 h-100 bg-black bg-opacity-75 z-3 d-flex flex-column align-items-center justify-content-center">
    <div class="spinner-border text-primary-orange mb-3" role="status" style="width: 3rem; height: 3rem;">
        <span class="visually-hidden">Loading...</span>
    </div>
    <h3 class="text-white fw-bold h5">Analyzing Your Curriculum...</h3>
    <p class="text-secondary small">Our AI is reviewing your units and interests</p>
</div>

<div class="d-flex align-items-center p-4 pb-2 justify-content-between z-1">
    <a href="<?php echo $step > 1 ? 'onboarding.php?step=1' : 'dashboard.php'; ?>" class="text-white d-flex align-items-center justify-content-center text-decoration-none" style="width: 48px; height: 48px;">
        <span class="material-symbols-outlined">arrow_back_ios</span>
    </a>
    <h2 class="text-white fs-5 fw-bold m-0 flex-grow-1 text-center pe-5">
        <?php echo $step === 1 ? 'Setup Your Profile' : 'Choose Your Path'; ?>
    </h2>
</div>

<div class="container-fluid p-4">
    <div class="row justify-content-center">
        <div class="col-12 col-lg-8">
            
            <?php if ($error): ?>
            <div class="alert alert-danger alert-dismissible fade show rounded-3 mb-4" role="alert">
                <span class="material-symbols-outlined me-2" style="font-size: 1.25rem; vertical-align: middle;">error</span>
                <?php echo htmlspecialchars($error); ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
            <?php endif; ?>

            <!-- Progress Steps -->
            <div class="d-flex justify-content-center mb-4">
                <div class="d-flex align-items-center gap-2">
                    <div class="rounded-circle d-flex align-items-center justify-content-center <?php echo $step >= 1 ? 'bg-primary-orange' : 'bg-secondary'; ?>" style="width: 32px; height: 32px;">
                        <span class="text-white small fw-bold">1</span>
                    </div>
                    <div class="bg-secondary" style="width: 60px; height: 2px;"></div>
                    <div class="rounded-circle d-flex align-items-center justify-content-center <?php echo $step >= 2 ? 'bg-primary-orange' : 'bg-secondary'; ?>" style="width: 32px; height: 32px;">
                        <span class="text-white small fw-bold">2</span>
                    </div>
                </div>
            </div>

            <?php if ($step === 1): ?>
            <!-- STEP 1: Profile & Upload -->
            <div class="card bg-card-dark border border-white border-opacity-10 rounded-4 p-4">
                <div class="mb-4 text-center">
                    <div class="d-inline-flex align-items-center justify-content-center bg-primary-orange bg-opacity-10 rounded-circle mb-3" style="width: 64px; height: 64px;">
                        <span class="material-symbols-outlined text-primary-orange" style="font-size: 32px;">school</span>
                    </div>
                    <h1 class="h4 fw-bold mb-2">Tell Us About Yourself</h1>
                    <p class="text-secondary small">We'll analyze your curriculum and recommend the perfect skills path.</p>
                </div>

                <form method="POST" enctype="multipart/form-data" onsubmit="document.getElementById('loadingOverlay').classList.remove('d-none')">
                    <?php csrfField(); ?>
                    <input type="hidden" name="action" value="analyze">
                    
                    <div class="row g-3">
                        <!-- Course Name -->
                        <div class="col-12">
                            <label class="form-label text-secondary small fw-bold text-uppercase">Your Course / Program *</label>
                            <input type="text" name="course_name" class="form-control form-control-dark rounded-3 py-2" 
                                   placeholder="e.g. BSc Computer Science, Medicine, Engineering" required>
                        </div>
                        
                        <!-- Year & Semester -->
                        <div class="col-6">
                            <label class="form-label text-secondary small fw-bold text-uppercase">Year of Study *</label>
                            <select name="year_of_study" class="form-select form-control-dark rounded-3 py-2" required>
                                <option value="1">Year 1</option>
                                <option value="2">Year 2</option>
                                <option value="3">Year 3</option>
                                <option value="4">Year 4</option>
                                <option value="5">Year 5</option>
                                <option value="6">Year 6</option>
                            </select>
                        </div>
                        <div class="col-6">
                            <label class="form-label text-secondary small fw-bold text-uppercase">Current Semester *</label>
                            <select name="current_semester" class="form-select form-control-dark rounded-3 py-2" required>
                                <option value="1">Semester 1</option>
                                <option value="2">Semester 2</option>
                            </select>
                        </div>

                        <!-- Upload Units -->
                        <div class="col-12">
                            <label class="form-label text-secondary small fw-bold text-uppercase">Upload Your Units *</label>
                            <div class="border border-dashed border-secondary rounded-3 p-4 text-center bg-black bg-opacity-25">
                                <span class="material-symbols-outlined text-primary-blue mb-2" style="font-size: 48px;">upload_file</span>
                                <p class="text-white mb-2">Upload a file with your course units</p>
                                <p class="text-secondary small mb-3">Supported: TXT, CSV, DOCX, XLSX, PNG, JPG</p>
                                <input type="file" name="units_file" id="unitsFile" class="form-control form-control-dark" 
                                       accept=".txt,.csv,.docx,.xlsx,.doc,.xls,.png,.jpg,.jpeg,.webp" required>
                            </div>
                        </div>

                        <!-- Interests -->
                        <div class="col-12">
                            <label class="form-label text-secondary small fw-bold text-uppercase">Your Interests</label>
                            <input type="text" name="interests" class="form-control form-control-dark rounded-3 py-2" 
                                   placeholder="e.g. Data Science, AI, Web Development, Mobile Apps (comma separated)">
                            <small class="text-secondary">What fields are you passionate about?</small>
                        </div>
                    </div>

                    <div class="mt-4 pt-2">
                        <button type="submit" class="btn bg-primary-orange text-white fw-bold py-3 rounded-3 d-flex align-items-center justify-content-center gap-2 glow-orange border-0 shadow-lg w-100">
                            <span>ANALYZE MY CURRICULUM</span>
                            <span class="material-symbols-outlined filled">auto_awesome</span>
                        </button>
                    </div>
                </form>
            </div>
            
            <?php elseif ($step === 2 && $recommendations): ?>
            <!-- STEP 2: Show Recommendations -->
            <div class="card bg-card-dark border border-white border-opacity-10 rounded-4 p-4 mb-4">
                <div class="mb-4 text-center">
                    <div class="d-inline-flex align-items-center justify-content-center bg-primary-blue bg-opacity-10 rounded-circle mb-3" style="width: 64px; height: 64px;">
                        <span class="material-symbols-outlined text-primary-blue" style="font-size: 32px;">psychology</span>
                    </div>
                    <h1 class="h4 fw-bold mb-2">Your Personalized Recommendations</h1>
                    <p class="text-secondary small"><?php echo htmlspecialchars($recommendations['student_profile_summary'] ?? ''); ?></p>
                </div>

                <form method="POST">
                    <?php csrfField(); ?>
                    <input type="hidden" name="action" value="confirm">
                    
                    <!-- Primary Recommendation (Highlighted) -->
                    <?php if (isset($recommendations['primary_recommendation'])): 
                        $primary = $recommendations['primary_recommendation'];
                    ?>
                    <div class="mb-4">
                        <h5 class="text-primary-orange fw-bold mb-3">
                            <span class="material-symbols-outlined me-1">star</span>
                            Top Recommendation
                        </h5>
                        <label class="d-block cursor-pointer">
                            <input type="radio" name="selected_course" value="<?php echo htmlspecialchars($primary['course_name']); ?>" class="d-none" checked>
                            <div class="border border-2 border-primary-orange rounded-4 p-4 bg-primary-orange bg-opacity-10">
                                <div class="d-flex justify-content-between align-items-start mb-2">
                                    <h4 class="h5 fw-bold text-white mb-0"><?php echo htmlspecialchars($primary['course_name']); ?></h4>
                                    <span class="badge bg-primary-orange"><?php echo $primary['relevance_score']; ?>% Match</span>
                                </div>
                                <p class="text-secondary mb-2"><?php echo htmlspecialchars($primary['description']); ?></p>
                                <p class="small text-primary-blue mb-0">
                                    <span class="material-symbols-outlined me-1" style="font-size: 16px;">lightbulb</span>
                                    <?php echo htmlspecialchars($primary['why_recommended']); ?>
                                </p>
                            </div>
                        </label>
                    </div>
                    <?php endif; ?>

                    <!-- Other Recommendations -->
                    <?php if (!empty($recommendations['recommended_courses'])): ?>
                    <h5 class="text-secondary fw-bold mb-3">Other Options</h5>
                    <div class="row g-3">
                        <?php foreach ($recommendations['recommended_courses'] as $course): 
                            if ($course['course_name'] === ($primary['course_name'] ?? '')) continue;
                        ?>
                        <div class="col-12 col-md-6">
                            <label class="d-block cursor-pointer h-100">
                                <input type="radio" name="selected_course" value="<?php echo htmlspecialchars($course['course_name']); ?>" class="d-none peer">
                                <div class="border border-secondary border-opacity-25 rounded-4 p-3 h-100 hover-border-primary">
                                    <div class="d-flex justify-content-between align-items-start mb-2">
                                        <h5 class="h6 fw-bold text-white mb-0"><?php echo htmlspecialchars($course['course_name']); ?></h5>
                                        <span class="badge bg-secondary"><?php echo $course['relevance_score']; ?>%</span>
                                    </div>
                                    <p class="text-secondary small mb-0"><?php echo htmlspecialchars($course['description']); ?></p>
                                </div>
                            </label>
                        </div>
                        <?php endforeach; ?>
                    </div>
                    <?php endif; ?>

                    <div class="mt-4 pt-2">
                        <button type="submit" class="btn bg-primary-orange text-white fw-bold py-3 rounded-3 d-flex align-items-center justify-content-center gap-2 glow-orange border-0 shadow-lg w-100">
                            <span>START MY LEARNING JOURNEY</span>
                            <span class="material-symbols-outlined filled">rocket_launch</span>
                        </button>
                    </div>
                </form>
            </div>
            <?php endif; ?>

        </div>
    </div>
</div>

<style>
.border-dashed { border-style: dashed !important; }
.cursor-pointer { cursor: pointer; }
.hover-border-primary:hover { border-color: var(--primary-blue) !important; }
input[type="radio"]:checked + div { border-color: var(--primary-orange) !important; background-color: rgba(249, 128, 6, 0.1); }
</style>

<?php include 'includes/footer.php'; ?>
