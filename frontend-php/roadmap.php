<?php include 'includes/header.php'; ?>

<!-- Roadmap Main Content -->
<main class="container-fluid p-3 p-lg-4">
    <!-- Page Header -->
    <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-4">
        <div>
            <h1 class="h4 fw-bold mb-1">12-Week Learning Roadmap</h1>
            <p class="text-secondary mb-0">CIT 301 - Machine Learning Fundamentals</p>
        </div>
        <div class="d-flex align-items-center gap-3">
            <div class="text-end">
                <span class="text-primary-orange fw-bold h5 mb-0">Week 4</span>
                <p class="text-secondary small mb-0">of 12 weeks</p>
            </div>
            <div class="rounded-circle bg-primary-orange bg-opacity-25 d-flex align-items-center justify-content-center" style="width: 48px; height: 48px;">
                <span class="text-primary-orange fw-bold">33%</span>
            </div>
        </div>
    </div>

    <!-- Overall Progress Bar -->
    <div class="card bg-card-dark border border-white border-opacity-10 rounded-4 p-3 mb-4">
        <div class="d-flex align-items-center justify-content-between mb-2">
            <span class="small text-secondary">Overall Progress</span>
            <span class="small text-primary-blue fw-bold">4 of 12 weeks</span>
        </div>
        <div class="progress bg-secondary bg-opacity-25" style="height: 8px;">
            <div class="progress-bar bg-primary-blue" style="width: 33%"></div>
        </div>
    </div>

    <!-- Roadmap Timeline -->
    <div class="roadmap-timeline">
        <?php
        // Dummy roadmap data
        $weeks = [
            [
                'week' => 1,
                'title' => 'Introduction to Machine Learning',
                'status' => 'completed',
                'topics' => ['What is ML?', 'Types of Learning', 'Python Setup'],
                'tasks_completed' => 4,
                'tasks_total' => 4
            ],
            [
                'week' => 2,
                'title' => 'Data Preprocessing & Exploration',
                'status' => 'completed',
                'topics' => ['Pandas Basics', 'Data Cleaning', 'Visualization with Matplotlib'],
                'tasks_completed' => 4,
                'tasks_total' => 4
            ],
            [
                'week' => 3,
                'title' => 'Supervised Learning: Regression',
                'status' => 'completed',
                'topics' => ['Linear Regression', 'Polynomial Regression', 'Model Evaluation'],
                'tasks_completed' => 4,
                'tasks_total' => 4
            ],
            [
                'week' => 4,
                'title' => 'Introduction to Neural Networks',
                'status' => 'current',
                'topics' => ['Perceptrons', 'Activation Functions', 'Building CNNs'],
                'tasks_completed' => 1,
                'tasks_total' => 4
            ],
            [
                'week' => 5,
                'title' => 'Deep Learning with PyTorch',
                'status' => 'locked',
                'topics' => ['Tensors', 'Autograd', 'Training Loops'],
                'tasks_completed' => 0,
                'tasks_total' => 4
            ],
            [
                'week' => 6,
                'title' => 'Convolutional Neural Networks',
                'status' => 'locked',
                'topics' => ['Convolution Layers', 'Pooling', 'Image Classification'],
                'tasks_completed' => 0,
                'tasks_total' => 4
            ],
            [
                'week' => 7,
                'title' => 'Recurrent Neural Networks',
                'status' => 'locked',
                'topics' => ['Sequence Data', 'LSTM', 'Text Generation'],
                'tasks_completed' => 0,
                'tasks_total' => 4
            ],
            [
                'week' => 8,
                'title' => 'Natural Language Processing',
                'status' => 'locked',
                'topics' => ['Tokenization', 'Word Embeddings', 'Sentiment Analysis'],
                'tasks_completed' => 0,
                'tasks_total' => 4
            ],
            [
                'week' => 9,
                'title' => 'Transfer Learning',
                'status' => 'locked',
                'topics' => ['Pre-trained Models', 'Fine-tuning', 'Feature Extraction'],
                'tasks_completed' => 0,
                'tasks_total' => 4
            ],
            [
                'week' => 10,
                'title' => 'Model Deployment Basics',
                'status' => 'locked',
                'topics' => ['Flask APIs', 'Model Serialization', 'Docker Intro'],
                'tasks_completed' => 0,
                'tasks_total' => 4
            ],
            [
                'week' => 11,
                'title' => 'Capstone Project: Part 1',
                'status' => 'locked',
                'topics' => ['Problem Definition', 'Data Collection', 'Model Selection'],
                'tasks_completed' => 0,
                'tasks_total' => 4
            ],
            [
                'week' => 12,
                'title' => 'Capstone Project: Part 2',
                'status' => 'locked',
                'topics' => ['Training', 'Evaluation', 'Presentation'],
                'tasks_completed' => 0,
                'tasks_total' => 4
            ]
        ];

        foreach ($weeks as $week):
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
                case 'locked':
                    $statusClass = 'bg-secondary bg-opacity-50';
                    $statusIcon = 'lock';
                    $statusBadge = '<span class="badge bg-secondary bg-opacity-25 text-secondary small">Locked</span>';
                    break;
            }
            
            $progress = $week['tasks_total'] > 0 ? ($week['tasks_completed'] / $week['tasks_total']) * 100 : 0;
        ?>
        
        <div class="d-flex gap-3 mb-3">
            <!-- Timeline Indicator -->
            <div class="d-flex flex-column align-items-center">
                <div class="rounded-circle <?php echo $statusClass; ?> d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                    <span class="material-symbols-outlined text-white" style="font-size: 20px;"><?php echo $statusIcon; ?></span>
                </div>
                <?php if ($week['week'] < 12): ?>
                <div class="flex-grow-1 bg-secondary bg-opacity-25" style="width: 2px; min-height: 20px;"></div>
                <?php endif; ?>
            </div>

            <!-- Week Card -->
            <div class="card bg-card-dark border <?php echo $borderClass; ?> rounded-4 p-3 flex-grow-1 mb-2">
                <div class="d-flex flex-wrap align-items-start justify-content-between gap-2 mb-2">
                    <div>
                        <div class="d-flex align-items-center gap-2 mb-1">
                            <span class="badge bg-primary-blue bg-opacity-25 text-primary-blue small fw-bold">Week <?php echo $week['week']; ?></span>
                            <?php echo $statusBadge; ?>
                        </div>
                        <h3 class="h6 fw-bold mb-0"><?php echo $week['title']; ?></h3>
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
                <div class="d-flex flex-wrap gap-2">
                    <?php foreach ($week['topics'] as $topic): ?>
                    <span class="badge bg-white bg-opacity-5 text-secondary small fw-normal px-2 py-1"><?php echo $topic; ?></span>
                    <?php endforeach; ?>
                </div>

                <?php if ($week['status'] === 'current'): ?>
                <div class="mt-3">
                    <a href="dashboard.php" class="btn btn-sm bg-primary-orange text-white border-0 rounded-pill px-3">
                        Continue Learning
                        <span class="material-symbols-outlined ms-1" style="font-size: 16px;">arrow_forward</span>
                    </a>
                </div>
                <?php endif; ?>
            </div>
        </div>
        
        <?php endforeach; ?>
    </div>
</main>

<?php include 'includes/footer.php'; ?>
