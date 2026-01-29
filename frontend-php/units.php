<?php include 'includes/header.php'; ?>

<!-- My Units Main Content -->
<main class="container-fluid p-3 p-lg-4">
    <!-- Page Header -->
    <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-4">
        <div>
            <h1 class="h4 fw-bold mb-1">My Units</h1>
            <p class="text-secondary mb-0">Semester 1, Year 3 - University of Nairobi</p>
        </div>
        <div class="d-flex gap-2">
            <button class="btn btn-outline-secondary rounded-pill px-3 d-flex align-items-center gap-2">
                <span class="material-symbols-outlined" style="font-size: 18px;">filter_list</span>
                Filter
            </button>
            <button class="btn bg-primary-orange text-white rounded-pill px-3 border-0 d-flex align-items-center gap-2">
                <span class="material-symbols-outlined" style="font-size: 18px;">add</span>
                Add Unit
            </button>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="row g-3 mb-4">
        <div class="col-6 col-lg-3">
            <div class="card bg-card-dark border border-white border-opacity-10 rounded-4 p-3 text-center">
                <span class="material-symbols-outlined text-primary-blue mb-2" style="font-size: 32px;">menu_book</span>
                <h3 class="h4 fw-bold mb-0">6</h3>
                <p class="text-secondary small mb-0">Enrolled Units</p>
            </div>
        </div>
        <div class="col-6 col-lg-3">
            <div class="card bg-card-dark border border-white border-opacity-10 rounded-4 p-3 text-center">
                <span class="material-symbols-outlined text-success mb-2" style="font-size: 32px;">check_circle</span>
                <h3 class="h4 fw-bold mb-0">2</h3>
                <p class="text-secondary small mb-0">Completed</p>
            </div>
        </div>
        <div class="col-6 col-lg-3">
            <div class="card bg-card-dark border border-white border-opacity-10 rounded-4 p-3 text-center">
                <span class="material-symbols-outlined text-primary-orange mb-2" style="font-size: 32px;">play_circle</span>
                <h3 class="h4 fw-bold mb-0">3</h3>
                <p class="text-secondary small mb-0">In Progress</p>
            </div>
        </div>
        <div class="col-6 col-lg-3">
            <div class="card bg-card-dark border border-white border-opacity-10 rounded-4 p-3 text-center">
                <span class="material-symbols-outlined text-secondary mb-2" style="font-size: 32px;">schedule</span>
                <h3 class="h4 fw-bold mb-0">1</h3>
                <p class="text-secondary small mb-0">Not Started</p>
            </div>
        </div>
    </div>

    <!-- Units Grid -->
    <div class="row g-4">
        <?php
        // Dummy units data
        $units = [
            [
                'code' => 'CIT 301',
                'name' => 'Machine Learning Fundamentals',
                'lecturer' => 'Dr. Wanjiku Mwangi',
                'status' => 'in_progress',
                'progress' => 37,
                'weeks_completed' => 4,
                'total_weeks' => 12,
                'icon' => 'psychology',
                'color' => 'primary-blue'
            ],
            [
                'code' => 'CIT 302',
                'name' => 'Database Systems II',
                'lecturer' => 'Prof. James Ochieng',
                'status' => 'in_progress',
                'progress' => 58,
                'weeks_completed' => 7,
                'total_weeks' => 12,
                'icon' => 'database',
                'color' => 'success'
            ],
            [
                'code' => 'CIT 303',
                'name' => 'Software Engineering',
                'lecturer' => 'Dr. Mary Akinyi',
                'status' => 'completed',
                'progress' => 100,
                'weeks_completed' => 12,
                'total_weeks' => 12,
                'icon' => 'code',
                'color' => 'success'
            ],
            [
                'code' => 'CIT 304',
                'name' => 'Computer Networks',
                'lecturer' => 'Mr. Peter Kamau',
                'status' => 'in_progress',
                'progress' => 25,
                'weeks_completed' => 3,
                'total_weeks' => 12,
                'icon' => 'lan',
                'color' => 'primary-orange'
            ],
            [
                'code' => 'CIT 305',
                'name' => 'Web Development',
                'lecturer' => 'Ms. Grace Wambui',
                'status' => 'completed',
                'progress' => 100,
                'weeks_completed' => 12,
                'total_weeks' => 12,
                'icon' => 'language',
                'color' => 'success'
            ],
            [
                'code' => 'CIT 306',
                'name' => 'Cybersecurity Basics',
                'lecturer' => 'Dr. John Mutiso',
                'status' => 'not_started',
                'progress' => 0,
                'weeks_completed' => 0,
                'total_weeks' => 12,
                'icon' => 'security',
                'color' => 'secondary'
            ]
        ];

        foreach ($units as $unit):
            $statusBadge = '';
            $borderClass = 'border-white border-opacity-10';
            
            switch ($unit['status']) {
                case 'completed':
                    $statusBadge = '<span class="badge bg-success bg-opacity-25 text-success small">Completed</span>';
                    break;
                case 'in_progress':
                    $statusBadge = '<span class="badge bg-primary-orange bg-opacity-25 text-primary-orange small">In Progress</span>';
                    if ($unit['code'] === 'CIT 301') {
                        $borderClass = 'border-primary-orange glow-orange';
                    }
                    break;
                case 'not_started':
                    $statusBadge = '<span class="badge bg-secondary bg-opacity-25 text-secondary small">Not Started</span>';
                    break;
            }
        ?>
        <div class="col-12 col-md-6 col-xl-4">
            <div class="card bg-card-dark border <?php echo $borderClass; ?> rounded-4 p-3 h-100">
                <!-- Unit Header -->
                <div class="d-flex align-items-start gap-3 mb-3">
                    <div class="rounded-3 bg-<?php echo $unit['color']; ?> bg-opacity-25 d-flex align-items-center justify-content-center flex-shrink-0" style="width: 48px; height: 48px;">
                        <span class="material-symbols-outlined text-<?php echo $unit['color']; ?>"><?php echo $unit['icon']; ?></span>
                    </div>
                    <div class="flex-grow-1">
                        <div class="d-flex align-items-center gap-2 mb-1">
                            <span class="badge bg-primary-blue bg-opacity-25 text-primary-blue small fw-bold"><?php echo $unit['code']; ?></span>
                            <?php echo $statusBadge; ?>
                        </div>
                        <h3 class="h6 fw-bold mb-1"><?php echo $unit['name']; ?></h3>
                        <p class="text-secondary small mb-0"><?php echo $unit['lecturer']; ?></p>
                    </div>
                </div>

                <!-- Progress -->
                <div class="mb-3">
                    <div class="d-flex align-items-center justify-content-between mb-1">
                        <span class="small text-secondary">Progress</span>
                        <span class="small fw-bold text-<?php echo $unit['color']; ?>"><?php echo $unit['progress']; ?>%</span>
                    </div>
                    <div class="progress bg-secondary bg-opacity-25" style="height: 6px;">
                        <div class="progress-bar bg-<?php echo $unit['color']; ?>" style="width: <?php echo $unit['progress']; ?>%"></div>
                    </div>
                    <p class="text-secondary small mt-1 mb-0"><?php echo $unit['weeks_completed']; ?> of <?php echo $unit['total_weeks']; ?> weeks</p>
                </div>

                <!-- Actions -->
                <div class="d-flex gap-2 mt-auto">
                    <?php if ($unit['status'] === 'in_progress'): ?>
                    <a href="dashboard.php" class="btn btn-sm bg-primary-orange text-white border-0 rounded-pill px-3 flex-grow-1">
                        Continue
                        <span class="material-symbols-outlined ms-1" style="font-size: 16px;">arrow_forward</span>
                    </a>
                    <?php elseif ($unit['status'] === 'completed'): ?>
                    <a href="certificates.php" class="btn btn-sm btn-outline-success rounded-pill px-3 flex-grow-1">
                        <span class="material-symbols-outlined me-1" style="font-size: 16px;">workspace_premium</span>
                        View Certificate
                    </a>
                    <?php else: ?>
                    <button class="btn btn-sm btn-outline-secondary rounded-pill px-3 flex-grow-1">
                        <span class="material-symbols-outlined me-1" style="font-size: 16px;">play_arrow</span>
                        Start Learning
                    </button>
                    <?php endif; ?>
                    <a href="roadmap.php" class="btn btn-sm btn-outline-secondary rounded-pill px-3">
                        <span class="material-symbols-outlined" style="font-size: 16px;">route</span>
                    </a>
                </div>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
</main>

<?php include 'includes/footer.php'; ?>
