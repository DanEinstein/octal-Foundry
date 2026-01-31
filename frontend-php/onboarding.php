<?php
/**
 * Onboarding Page
 * Collects course unit details and generates AI roadmap
 */

require_once 'includes/auth.php';
require_once 'includes/api_client.php';
require_once 'includes/roadmap_helper.php';

requireAuth(); // Ensure user is logged in

$user = getCurrentUser();
$error = null;
$success = null;
$loading = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validate CSRF
    if (!validateCsrfToken($_POST['csrf_token'] ?? '')) {
        $error = 'Security validation failed. Please try again.';
    } else {
        // Extract form data
        $unitCode = trim($_POST['unit_code'] ?? '');
        $unitName = trim($_POST['unit_name'] ?? '');
        $lecturer = trim($_POST['lecturer'] ?? '');
        $semester = trim($_POST['semester'] ?? '');
        $year = (int)($_POST['year'] ?? date('Y'));
        
        if (empty($unitCode) || empty($unitName)) {
            $error = 'Please provide at least the Unit Code and Name.';
        } else {
            $loading = true;
            
            // 1. Create Unit in Database
            $unitId = createUnit($user['id'], $unitCode, $unitName, $lecturer, $semester, $year);
            
            if ($unitId) {
                // 2. Call FastAPI to generate roadmap
                $api = new ApiClient();
                $response = $api->generateRoadmap([
                    'unit_code' => $unitCode,
                    'unit_name' => $unitName,
                    'lecturer_name' => $lecturer,
                    'semester' => $semester,
                    'year' => $year
                ]);
                
                if ($response['success'] && !empty($response['roadmap'])) {
                    // 3. Save Roadmap to Database
                    if (saveRoadmapToDatabase($unitId, $response['roadmap'])) {
                        header('Location: dashboard.php?new_unit=' . $unitId);
                        exit;
                    } else {
                        $error = 'Failed to save roadmap to database.';
                    }
                } else {
                    $error = 'AI Generation failed: ' . ($response['error'] ?? 'Unknown error');
                }
            } else {
                $error = 'Failed to create unit record.';
            }
            $loading = false;
        }
    }
}

include 'includes/header.php';
?>

<!-- Loading Overlay (Hidden by default) -->
<div id="loadingOverlay" class="d-none position-fixed top-0 start-0 w-100 h-100 bg-black bg-opacity-75 z-3 d-flex flex-column align-items-center justify-content-center">
    <div class="spinner-border text-primary-orange mb-3" role="status" style="width: 3rem; height: 3rem;">
        <span class="visually-hidden">Loading...</span>
    </div>
    <h3 class="text-white fw-bold h5">Forging Your Roadmap...</h3>
    <p class="text-secondary small">Consulting the AI architect</p>
</div>

<div class="d-flex align-items-center p-4 pb-2 justify-content-between z-1">
    <a href="dashboard.php" class="text-white d-flex align-items-center justify-content-center text-decoration-none" style="width: 48px; height: 48px;">
        <span class="material-symbols-outlined">arrow_back_ios</span>
    </a>
    <h2 class="text-white fs-5 fw-bold m-0 flex-grow-1 text-center pe-5">Add New Unit</h2>
</div>

<div class="container-fluid p-4">
    <div class="row justify-content-center">
        <div class="col-12 col-md-8 col-lg-6">
            
            <?php if ($error): ?>
            <div class="alert alert-danger alert-dismissible fade show rounded-3 mb-4" role="alert">
                <span class="material-symbols-outlined me-2" style="font-size: 1.25rem; vertical-align: middle;">error</span>
                <?php echo htmlspecialchars($error); ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
            <?php endif; ?>

            <div class="card bg-card-dark border border-white border-opacity-10 rounded-4 p-4">
                <div class="mb-4 text-center">
                    <div class="d-inline-flex align-items-center justify-content-center bg-primary-orange bg-opacity-10 rounded-circle mb-3" style="width: 64px; height: 64px;">
                        <span class="material-symbols-outlined text-primary-orange" style="font-size: 32px;">school</span>
                    </div>
                    <h1 class="h4 fw-bold mb-2">Unit Details</h1>
                    <p class="text-secondary small">Enter your course information to generate a personalized learning path.</p>
                </div>

                <form method="POST" action="onboarding.php" onsubmit="document.getElementById('loadingOverlay').classList.remove('d-none')">
                    <?php csrfField(); ?>
                    
                    <div class="row g-3">
                        <!-- Unit Code -->
                        <div class="col-12 col-md-4">
                            <label class="form-label text-secondary small fw-bold text-uppercase">Unit Code</label>
                            <input type="text" name="unit_code" class="form-control form-control-dark rounded-3 py-2" placeholder="e.g. CIT 301" required>
                        </div>
                        
                        <!-- Unit Name -->
                        <div class="col-12 col-md-8">
                            <label class="form-label text-secondary small fw-bold text-uppercase">Unit Name</label>
                            <input type="text" name="unit_name" class="form-control form-control-dark rounded-3 py-2" placeholder="e.g. Machine Learning" required>
                        </div>

                        <!-- Lecturer -->
                        <div class="col-12">
                            <label class="form-label text-secondary small fw-bold text-uppercase">Lecturer Name</label>
                            <input type="text" name="lecturer" class="form-control form-control-dark rounded-3 py-2" placeholder="e.g. Dr. Wanjiku Mwangi">
                        </div>

                        <!-- Semester & Year -->
                        <div class="col-6">
                            <label class="form-label text-secondary small fw-bold text-uppercase">Semester</label>
                            <select name="semester" class="form-select form-control-dark rounded-3 py-2">
                                <option value="Semester 1">Semester 1</option>
                                <option value="Semester 2">Semester 2</option>
                                <option value="Semester 3">Semester 3</option>
                            </select>
                        </div>
                        
                        <div class="col-6">
                            <label class="form-label text-secondary small fw-bold text-uppercase">Year</label>
                            <select name="year" class="form-select form-control-dark rounded-3 py-2">
                                <?php 
                                $currentYear = date('Y');
                                for ($i = $currentYear; $i <= $currentYear + 2; $i++) {
                                    echo "<option value=\"$i\">$i</option>";
                                }
                                ?>
                            </select>
                        </div>
                    </div>

                    <div class="mt-4 pt-2">
                        <button type="submit" class="btn bg-primary-orange text-white fw-bold py-3 rounded-3 d-flex align-items-center justify-content-center gap-2 glow-orange border-0 shadow-lg w-100">
                            <span>GENERATE ROADMAP</span>
                            <span class="material-symbols-outlined filled">auto_awesome</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>
