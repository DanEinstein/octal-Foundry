<?php include 'includes/header.php'; ?>

<!-- Dashboard Main Content -->
<main class="container-fluid p-3 p-lg-4">
    <!-- Welcome Section -->
    <div class="mb-4">
        <h1 class="h4 fw-bold mb-1">Welcome back, John</h1>
        <p class="text-secondary mb-0">Week 4 of 12 - Introduction to Neural Networks</p>
    </div>

    <!-- Current Unit Progress -->
    <div class="card bg-card-dark border border-white border-opacity-10 rounded-4 p-3 mb-4">
        <div class="d-flex flex-wrap align-items-center justify-content-between gap-3">
            <div>
                <span class="badge bg-primary-blue bg-opacity-25 text-primary-blue small fw-bold mb-2">CIT 301</span>
                <h2 class="h6 fw-bold mb-1">Machine Learning Fundamentals</h2>
                <p class="text-secondary small mb-0">3 of 8 topics completed</p>
            </div>
            <div class="text-end">
                <div class="d-flex align-items-center gap-2 mb-1">
                    <span class="text-primary-orange fw-bold">37%</span>
                    <span class="text-secondary small">complete</span>
                </div>
                <div class="progress bg-secondary bg-opacity-25" style="width: 150px; height: 6px;">
                    <div class="progress-bar bg-primary-orange" style="width: 37%"></div>
                </div>
            </div>
        </div>
    </div>

    <!-- Two Column Layout: Video Player + AI Coach -->
    <div class="row g-4">
        <!-- Left Column: YouTube Video Player -->
        <div class="col-12 col-xl-8">
            <div class="card bg-card-dark border border-white border-opacity-10 rounded-4 overflow-hidden h-100">
                <!-- Video Header -->
                <div class="card-header bg-transparent border-bottom border-secondary border-opacity-25 p-3">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <span class="badge bg-primary-orange bg-opacity-25 text-primary-orange small fw-bold mb-1">Foundry Task 4.1</span>
                            <h3 class="h6 fw-bold mb-0">Building Your First CNN Classifier</h3>
                        </div>
                        <div class="d-flex gap-2">
                            <button class="btn btn-sm btn-outline-secondary rounded-pill px-3">
                                <span class="material-symbols-outlined me-1" style="font-size: 16px;">bookmark</span>
                                Save
                            </button>
                            <button class="btn btn-sm btn-outline-secondary rounded-pill px-3">
                                <span class="material-symbols-outlined me-1" style="font-size: 16px;">share</span>
                                Share
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Video Player (16:9 Responsive) -->
                <div class="ratio ratio-16x9 bg-black">
                    <iframe 
                        src="https://www.youtube.com/embed/aircAruvnKk" 
                        title="Neural Network Tutorial" 
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
                                <span class="text-white fw-bold" style="font-size: 12px;">3B</span>
                            </div>
                            <span class="small fw-medium">3Blue1Brown</span>
                        </div>
                        <span class="text-secondary small">18M views</span>
                        <span class="text-secondary small">19:13 duration</span>
                    </div>
                    <p class="text-secondary small mb-0">
                        But what is a neural network? This video provides a visual introduction to the topic, 
                        walking through the structure of neural networks and how they learn.
                    </p>
                </div>

                <!-- Video Navigation -->
                <div class="card-footer bg-transparent border-top border-secondary border-opacity-25 p-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <button class="btn btn-outline-secondary btn-sm rounded-pill px-3">
                            <span class="material-symbols-outlined me-1" style="font-size: 16px;">arrow_back</span>
                            Previous
                        </button>
                        <div class="text-center">
                            <span class="text-secondary small">Video 1 of 4</span>
                        </div>
                        <button class="btn btn-primary-orange btn-sm rounded-pill px-3 bg-primary-orange border-0">
                            Next
                            <span class="material-symbols-outlined ms-1" style="font-size: 16px;">arrow_forward</span>
                        </button>
                    </div>
                </div>
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
                    <!-- AI Message -->
                    <div class="d-flex gap-2 mb-3">
                        <div class="rounded-circle bg-primary-blue bg-opacity-25 flex-shrink-0 d-flex align-items-center justify-content-center" style="width: 32px; height: 32px;">
                            <span class="material-symbols-outlined text-primary-blue" style="font-size: 18px;">smart_toy</span>
                        </div>
                        <div class="glass-panel rounded-4 p-3 flex-grow-1">
                            <p class="small mb-2">Great progress on today's task! I noticed you're working on CNNs.</p>
                            <p class="small mb-0 text-secondary">Here's a hint for your next step:</p>
                        </div>
                    </div>

                    <!-- Hint Card -->
                    <div class="bg-primary-blue bg-opacity-10 border border-primary-blue border-opacity-25 rounded-4 p-3 mb-3 ms-5">
                        <div class="d-flex align-items-start gap-2">
                            <span class="material-symbols-outlined text-primary-blue" style="font-size: 20px;">lightbulb</span>
                            <div>
                                <p class="small fw-bold text-primary-blue mb-1">Performance Tip</p>
                                <p class="small mb-0">Your code is missing a <code class="text-primary-orange">BatchNorm2d</code> layer after the first convolution. This typically improves training speed by 30%.</p>
                            </div>
                        </div>
                    </div>

                    <!-- AI Follow-up -->
                    <div class="d-flex gap-2 mb-3">
                        <div class="rounded-circle bg-primary-blue bg-opacity-25 flex-shrink-0 d-flex align-items-center justify-content-center" style="width: 32px; height: 32px;">
                            <span class="material-symbols-outlined text-primary-blue" style="font-size: 18px;">smart_toy</span>
                        </div>
                        <div class="glass-panel rounded-4 p-3 flex-grow-1">
                            <p class="small mb-0">Would you like me to explain how BatchNorm works, or should we move on to the pooling layers?</p>
                        </div>
                    </div>

                    <!-- Quick Action Buttons -->
                    <div class="d-flex flex-wrap gap-2 ms-5">
                        <button class="btn btn-sm btn-outline-primary-blue rounded-pill px-3 border-primary border-opacity-50 text-primary-blue">
                            Explain BatchNorm
                        </button>
                        <button class="btn btn-sm btn-outline-secondary rounded-pill px-3">
                            Skip to Pooling
                        </button>
                    </div>
                </div>

                <!-- Skill Progress Section -->
                <div class="border-top border-secondary border-opacity-25 p-3">
                    <h5 class="small fw-bold mb-3">Skills Being Tracked</h5>
                    <div class="d-flex flex-column gap-2">
                        <div class="d-flex align-items-center justify-content-between">
                            <span class="small text-secondary">CNN Architecture</span>
                            <div class="d-flex align-items-center gap-2">
                                <div class="progress bg-secondary bg-opacity-25" style="width: 80px; height: 4px;">
                                    <div class="progress-bar bg-primary-blue" style="width: 65%"></div>
                                </div>
                                <span class="small text-primary-blue">65%</span>
                            </div>
                        </div>
                        <div class="d-flex align-items-center justify-content-between">
                            <span class="small text-secondary">Python/PyTorch</span>
                            <div class="d-flex align-items-center gap-2">
                                <div class="progress bg-secondary bg-opacity-25" style="width: 80px; height: 4px;">
                                    <div class="progress-bar bg-primary-orange" style="width: 78%"></div>
                                </div>
                                <span class="small text-primary-orange">78%</span>
                            </div>
                        </div>
                        <div class="d-flex align-items-center justify-content-between">
                            <span class="small text-secondary">Problem Solving</span>
                            <div class="d-flex align-items-center gap-2">
                                <div class="progress bg-secondary bg-opacity-25" style="width: 80px; height: 4px;">
                                    <div class="progress-bar bg-success" style="width: 52%"></div>
                                </div>
                                <span class="small text-success">52%</span>
                            </div>
                        </div>
                    </div>
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
            <h3 class="h5 fw-bold mb-0">This Week's Foundry Tasks</h3>
            <a href="roadmap.php" class="text-primary-blue text-decoration-none small fw-bold">View Roadmap</a>
        </div>
        <div class="row g-3">
            <!-- Task Card 1 -->
            <div class="col-12 col-md-6 col-lg-3">
                <div class="card bg-card-dark border border-white border-opacity-10 rounded-4 p-3 h-100">
                    <div class="d-flex align-items-center gap-2 mb-2">
                        <div class="rounded-circle bg-success d-flex align-items-center justify-content-center" style="width: 24px; height: 24px;">
                            <span class="material-symbols-outlined text-white" style="font-size: 16px;">check</span>
                        </div>
                        <span class="badge bg-success bg-opacity-25 text-success small">Completed</span>
                    </div>
                    <h4 class="small fw-bold mb-1">Task 4.1: Data Preprocessing</h4>
                    <p class="text-secondary small mb-0">Load and normalize the CIFAR-10 dataset</p>
                </div>
            </div>
            <!-- Task Card 2 -->
            <div class="col-12 col-md-6 col-lg-3">
                <div class="card bg-card-dark border border-primary-orange rounded-4 p-3 h-100 glow-orange">
                    <div class="d-flex align-items-center gap-2 mb-2">
                        <div class="rounded-circle bg-primary-orange d-flex align-items-center justify-content-center" style="width: 24px; height: 24px;">
                            <span class="material-symbols-outlined text-white" style="font-size: 16px;">play_arrow</span>
                        </div>
                        <span class="badge bg-primary-orange bg-opacity-25 text-primary-orange small">In Progress</span>
                    </div>
                    <h4 class="small fw-bold mb-1">Task 4.2: Build CNN Model</h4>
                    <p class="text-secondary small mb-0">Create a convolutional neural network architecture</p>
                </div>
            </div>
            <!-- Task Card 3 -->
            <div class="col-12 col-md-6 col-lg-3">
                <div class="card bg-card-dark border border-white border-opacity-10 rounded-4 p-3 h-100">
                    <div class="d-flex align-items-center gap-2 mb-2">
                        <div class="rounded-circle bg-secondary bg-opacity-50 d-flex align-items-center justify-content-center" style="width: 24px; height: 24px;">
                            <span class="material-symbols-outlined text-white" style="font-size: 16px;">lock</span>
                        </div>
                        <span class="badge bg-secondary bg-opacity-25 text-secondary small">Locked</span>
                    </div>
                    <h4 class="small fw-bold mb-1">Task 4.3: Train Model</h4>
                    <p class="text-secondary small mb-0">Implement training loop with loss tracking</p>
                </div>
            </div>
            <!-- Task Card 4 -->
            <div class="col-12 col-md-6 col-lg-3">
                <div class="card bg-card-dark border border-white border-opacity-10 rounded-4 p-3 h-100">
                    <div class="d-flex align-items-center gap-2 mb-2">
                        <div class="rounded-circle bg-secondary bg-opacity-50 d-flex align-items-center justify-content-center" style="width: 24px; height: 24px;">
                            <span class="material-symbols-outlined text-white" style="font-size: 16px;">lock</span>
                        </div>
                        <span class="badge bg-secondary bg-opacity-25 text-secondary small">Locked</span>
                    </div>
                    <h4 class="small fw-bold mb-1">Task 4.4: Evaluate & Submit</h4>
                    <p class="text-secondary small mb-0">Test accuracy and submit for AI review</p>
                </div>
            </div>
        </div>
    </div>
</main>

<?php include 'includes/footer.php'; ?>
