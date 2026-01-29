<?php include 'includes/header.php'; ?>

<main class="container-fluid p-3 p-lg-4">
    <!-- Page Header -->
    <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-4">
        <div>
            <p class="text-primary-blue fw-bold small text-uppercase tracking-widest mb-1" style="font-size: 10px; letter-spacing: 0.2em;">Octal Foundry</p>
            <h1 class="h4 fw-bold mb-0">Week 7: AI in Healthcare</h1>
        </div>
        <div class="d-flex gap-2">
            <button class="btn btn-outline-secondary rounded-pill px-3 d-flex align-items-center gap-2">
                <span class="material-symbols-outlined" style="font-size: 18px;">schedule</span>
                Progress
            </button>
        </div>
    </div>

    <!-- Two Column Layout -->
    <div class="row g-4">
        <!-- Left Column: Video + Transcript -->
        <div class="col-12 col-xl-8">
            <!-- MediaPlayer Section -->
            <div class="card bg-card-dark border border-white border-opacity-10 rounded-4 overflow-hidden mb-4">
                <div class="position-relative d-flex flex-column">
                    <div class="ratio ratio-16x9 bg-dark" style="background-image: url('https://lh3.googleusercontent.com/aida-public/AB6AXuDYFjNt5hMijJ8I6rLjunvlL2jMGk0RRQcEabmKj3WkKdPoEmvy_bfCxHja-IkAqt6TMV4q02JBHPanjTIWXCRzYDIn0JSEmW3vAV38tTC9xCmZ0G4EJXKHckx-2cOkNjAZ-N34fXedCkfesHiJJKHaBsS_QR1nkHlCI2zXeGn0ZIUhe24X7k9RIDM2OM495C0ikpdtQIYL7iS_L29PXJCRQBnlQqORzu10J50m2WoyJ0t8WHYVx9vOuGS7fY68beOI5EpSTfGMtdc'); background-size: cover; background-position: center;">
                        <div class="position-absolute top-0 start-0 w-100 h-100 bg-black bg-opacity-25 d-flex align-items-center justify-content-center">
                            <button class="btn btn-primary-blue rounded-circle p-0 d-flex align-items-center justify-content-center shadow-lg" style="width: 64px; height: 64px; background-color: rgba(13, 127, 242, 0.9);">
                                <span class="material-symbols-outlined fs-1 text-white filled">play_arrow</span>
                            </button>
                        </div>

                        <div class="position-absolute bottom-0 start-0 w-100 p-3" style="background: linear-gradient(to top, rgba(0,0,0,0.8), transparent);">
                            <div class="d-flex align-items-center mb-2 cursor-pointer">
                                <div class="progress w-100" style="height: 4px; background-color: rgba(255,255,255,0.3);">
                                    <div class="progress-bar bg-primary-blue position-relative" role="progressbar" style="width: 30%">
                                        <div class="position-absolute top-50 start-100 translate-middle bg-primary-blue border border-2 border-white rounded-circle shadow-sm" style="width: 16px; height: 16px;"></div>
                                    </div>
                                </div>
                            </div>
                            <div class="d-flex align-items-center justify-content-between text-white">
                                <span class="small fw-bold tracking-wider" style="font-size: 11px;">12:45 / 24:10</span>
                                <div class="d-flex gap-3">
                                    <span class="material-symbols-outlined small">closed_caption</span>
                                    <span class="material-symbols-outlined small">settings</span>
                                    <span class="material-symbols-outlined small">fullscreen</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tabs Section -->
            <div class="card bg-card-dark border border-white border-opacity-10 rounded-4 overflow-hidden">
                <div class="d-flex border-bottom border-secondary border-opacity-25 gap-4 px-3">
                    <button class="btn btn-link text-decoration-none border-bottom border-3 border-primary pb-2 pt-3 text-primary-blue px-0 rounded-0">
                        <span class="fw-bold small">Transcript</span>
                    </button>
                    <button class="btn btn-link text-decoration-none border-bottom border-3 border-transparent pb-2 pt-3 text-secondary px-0 rounded-0">
                        <span class="fw-bold small">Notes</span>
                    </button>
                    <button class="btn btn-link text-decoration-none border-bottom border-3 border-transparent pb-2 pt-3 text-secondary px-0 rounded-0">
                        <span class="fw-bold small">Resources</span>
                    </button>
                </div>

                <!-- Transcript Body -->
                <div class="p-3 d-flex flex-column gap-3" style="max-height: 250px; overflow-y: auto;">
                    <div>
                        <p class="text-secondary font-monospace xsmall mb-1" style="font-size: 12px;">[12:10]</p>
                        <p class="text-light small mb-0">
                            To accurately segment the glioma boundary, we must first apply a <span class="text-primary-blue fw-semibold">normalization layer</span>. This ensures that variations in MRI scanner intensity don't skew our CNN weights.
                        </p>
                    </div>
                    <div class="bg-primary-blue bg-opacity-10 border-start border-4 border-primary p-2 rounded-end">
                        <p class="text-secondary font-monospace xsmall mb-1" style="font-size: 12px;">[12:45] CURRENT</p>
                        <p class="text-white small fw-medium mb-0">
                            Look at the T1-weighted image on the screen. The hyper-intense regions are what we'll target with the next PyTorch module...
                        </p>
                    </div>
                    <div>
                        <p class="text-secondary font-monospace xsmall mb-1" style="font-size: 12px;">[13:02]</p>
                        <p class="text-light small mb-0">
                            Notice how the loss function stabilizes after we introduce Batch Normalization...
                        </p>
                    </div>
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
                        <span class="badge bg-warning bg-opacity-10 text-warning border border-warning border-opacity-25 small fw-bold text-uppercase">Critical</span>
                    </div>
                </div>
                <div class="card-body p-3">
                    <h3 class="h6 fw-bold mb-2">MRI Analysis</h3>
                    <p class="text-secondary small mb-3">
                        Complete the CNN architecture to identify glioma tissue in the provided T1 dataset.
                    </p>

                    <!-- IDE / Code Area -->
                    <div class="rounded-3 overflow-hidden border border-secondary border-opacity-50" style="background-color: #1e1e1e;">
                        <div class="d-flex align-items-center justify-content-between px-3 py-2 border-bottom border-secondary border-opacity-50" style="background-color: #2d2d2d;">
                            <div class="d-flex gap-2">
                                <div class="rounded-circle bg-danger" style="width: 8px; height: 8px;"></div>
                                <div class="rounded-circle bg-warning" style="width: 8px; height: 8px;"></div>
                                <div class="rounded-circle bg-success" style="width: 8px; height: 8px;"></div>
                            </div>
                            <p class="text-secondary font-monospace mb-0" style="font-size: 9px;">segmentation_model.py</p>
                            <span class="material-symbols-outlined text-secondary" style="font-size: 14px;">content_copy</span>
                        </div>
                        <div class="p-2 font-monospace text-white overflow-auto" style="font-size: 11px;">
                            <div class="d-flex">
                                <span class="text-secondary me-2 user-select-none">1</span>
                                <div><span style="color: #c586c0">import</span> <span style="color: #9cdcfe">torch.nn</span> <span style="color: #c586c0">as</span> <span style="color: #9cdcfe">nn</span></div>
                            </div>
                            <div class="d-flex">
                                <span class="text-secondary me-2 user-select-none">2</span>
                                <div><span style="color: #d4d4d4">class</span> <span style="color: #4ec9b0">GliomaNet</span><span style="color: #d4d4d4">(nn.Module):</span></div>
                            </div>
                            <div class="d-flex bg-primary-blue bg-opacity-25 mx-n2 px-2 border-start border-2 border-primary">
                                <span class="text-secondary me-2 user-select-none">3</span>
                                <div class="ms-2"><span class="text-white"># TODO: Add Normalization</span></div>
                            </div>
                        </div>
                    </div>

                    <div class="mt-3 d-flex gap-2">
                        <button class="btn bg-primary-blue text-white fw-bold py-2 rounded-pill flex-grow-1 d-flex align-items-center justify-content-center gap-2">
                            <span class="material-symbols-outlined" style="font-size: 18px;">rocket_launch</span>
                            Submit
                        </button>
                        <button class="btn btn-outline-secondary rounded-pill px-3">
                            <span class="material-symbols-outlined" style="font-size: 18px;">help</span>
                        </button>
                    </div>
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
                                <p class="small fw-bold text-primary-blue mb-1">AI Suggestion</p>
                                <p class="small mb-0" id="aiCoachText">Loading suggestion...</p>
                            </div>
                        </div>
                    </div>
                    <div class="d-flex flex-wrap gap-2">
                        <button class="btn btn-sm btn-outline-secondary rounded-pill px-3">Show Example</button>
                        <button class="btn btn-sm btn-outline-secondary rounded-pill px-3">Explain More</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>

<script>
    async function fetchAICoach() {
        const textElement = document.getElementById('aiCoachText');

        try {
            const response = await fetch('http://localhost:8000/api/coach/hint');
            if (response.ok) {
                const data = await response.json();
                textElement.innerHTML = data.message;
            } else {
                textElement.innerText = "Coach is offline.";
            }
        } catch (error) {
            console.error('Error fetching AI coach:', error);
            textElement.innerText = "Coach connection failed.";
        }
    }

    document.addEventListener('DOMContentLoaded', function() {
        fetchAICoach();
    });
</script>

<?php include 'includes/footer.php'; ?>
