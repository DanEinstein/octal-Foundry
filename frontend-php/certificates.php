<?php include 'includes/header.php'; ?>

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
            <span class="fw-bold">5 Certificates Earned</span>
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
                        <h3 class="h4 fw-bold mb-0 text-primary-blue">5</h3>
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
                        <h3 class="h4 fw-bold mb-0 text-primary-orange">12</h3>
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
                        <h3 class="h4 fw-bold mb-0 text-success">8</h3>
                        <p class="text-secondary small mb-0">Shared</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Certificates Grid -->
    <div class="row g-4">
        <?php
        // Dummy certificates data
        $certificates = [
            [
                'title' => 'Machine Learning Level 1',
                'unit' => 'CIT 301',
                'date' => 'January 2026',
                'skills' => ['Python', 'Pandas', 'Scikit-learn'],
                'icon' => 'psychology',
                'color' => 'primary-blue',
                'verified' => true
            ],
            [
                'title' => 'Advanced Python Development',
                'unit' => 'CIT 303',
                'date' => 'December 2025',
                'skills' => ['OOP', 'Decorators', 'Async'],
                'icon' => 'data_object',
                'color' => 'primary-orange',
                'verified' => true
            ],
            [
                'title' => 'Database Administration',
                'unit' => 'CIT 302',
                'date' => 'November 2025',
                'skills' => ['MySQL', 'Normalization', 'Query Optimization'],
                'icon' => 'database',
                'color' => 'success',
                'verified' => true
            ],
            [
                'title' => 'Web Development Fundamentals',
                'unit' => 'CIT 305',
                'date' => 'October 2025',
                'skills' => ['HTML5', 'CSS3', 'JavaScript'],
                'icon' => 'language',
                'color' => 'info',
                'verified' => true
            ],
            [
                'title' => 'Software Engineering Practices',
                'unit' => 'CIT 303',
                'date' => 'September 2025',
                'skills' => ['Git', 'Agile', 'Testing'],
                'icon' => 'code',
                'color' => 'warning',
                'verified' => true
            ]
        ];

        foreach ($certificates as $cert):
        ?>
        <div class="col-12 col-md-6 col-xl-4">
            <div class="card bg-card-dark border border-white border-opacity-10 rounded-4 overflow-hidden h-100">
                <!-- Certificate Header -->
                <div class="bg-<?php echo $cert['color']; ?> bg-opacity-10 p-4 text-center position-relative">
                    <!-- Verified Badge -->
                    <?php if ($cert['verified']): ?>
                    <div class="position-absolute top-0 end-0 m-2">
                        <div class="d-flex align-items-center gap-1 bg-primary-blue bg-opacity-25 border border-primary-blue border-opacity-25 px-2 py-1 rounded-pill">
                            <span class="material-symbols-outlined text-primary-blue" style="font-size: 14px;">verified</span>
                            <span class="text-primary-blue small fw-bold" style="font-size: 10px;">AI Verified</span>
                        </div>
                    </div>
                    <?php endif; ?>
                    
                    <!-- Certificate Icon -->
                    <div class="rounded-circle bg-<?php echo $cert['color']; ?> bg-opacity-25 d-inline-flex align-items-center justify-content-center mb-3" style="width: 80px; height: 80px;">
                        <span class="material-symbols-outlined text-<?php echo $cert['color']; ?>" style="font-size: 40px;"><?php echo $cert['icon']; ?></span>
                    </div>
                    
                    <h3 class="h6 fw-bold mb-1"><?php echo $cert['title']; ?></h3>
                    <p class="text-secondary small mb-0">Octal Foundry Certificate</p>
                </div>

                <!-- Certificate Body -->
                <div class="card-body p-3">
                    <div class="d-flex align-items-center justify-content-between mb-3">
                        <span class="badge bg-primary-blue bg-opacity-25 text-primary-blue small fw-bold"><?php echo $cert['unit']; ?></span>
                        <span class="text-secondary small"><?php echo $cert['date']; ?></span>
                    </div>

                    <!-- Skills -->
                    <p class="small text-secondary mb-2">Skills Demonstrated:</p>
                    <div class="d-flex flex-wrap gap-2 mb-3">
                        <?php foreach ($cert['skills'] as $skill): ?>
                        <span class="badge bg-white bg-opacity-5 text-white small fw-normal px-2 py-1"><?php echo $skill; ?></span>
                        <?php endforeach; ?>
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
    </div>

    <!-- Pending Certificates Section -->
    <div class="mt-5">
        <h2 class="h5 fw-bold mb-3">Certificates In Progress</h2>
        <div class="card bg-card-dark border border-white border-opacity-10 rounded-4 p-3">
            <div class="d-flex flex-wrap align-items-center gap-3">
                <div class="rounded-circle bg-primary-orange bg-opacity-25 d-flex align-items-center justify-content-center flex-shrink-0" style="width: 48px; height: 48px;">
                    <span class="material-symbols-outlined text-primary-orange">hourglass_top</span>
                </div>
                <div class="flex-grow-1">
                    <h4 class="h6 fw-bold mb-1">Neural Networks Mastery</h4>
                    <p class="text-secondary small mb-0">CIT 301 - Complete Week 12 to earn this certificate</p>
                </div>
                <div class="text-end">
                    <div class="d-flex align-items-center gap-2">
                        <div class="progress bg-secondary bg-opacity-25" style="width: 100px; height: 6px;">
                            <div class="progress-bar bg-primary-orange" style="width: 33%"></div>
                        </div>
                        <span class="small text-primary-orange fw-bold">33%</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>

<?php include 'includes/footer.php'; ?>
