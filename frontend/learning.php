<?php include 'includes/header.php'; ?>

<!-- TopAppBar -->
<header class="sticky-top bg-background-dark border-bottom border-secondary border-opacity-25 p-3 d-flex align-items-center justify-content-between z-3">
    <a href="index.php" class="btn btn-hover-light text-primary-blue rounded-circle p-2 d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
        <span class="material-symbols-outlined">arrow_back_ios_new</span>
    </a>
    <div class="text-center flex-grow-1">
        <p class="text-primary-blue fw-bold small text-uppercase tracking-widest mb-0" style="font-size: 10px; letter-spacing: 0.2em;">Octal Foundry</p>
        <h2 class="h6 fw-bold text-white m-0">Week 7: AI in Healthcare</h2>
    </div>
    <div class="d-flex justify-content-end" style="width: 40px;">
        <button class="btn btn-light rounded-circle p-0 d-flex align-items-center justify-content-center text-secondary" style="width: 40px; height: 40px;">
            <span class="material-symbols-outlined">schedule</span>
        </button>
    </div>
</header>

<main class="flex-grow-1 d-flex flex-column pb-5 mb-5">
    <!-- MediaPlayer Section -->
    <section class="p-3 pt-2">
        <div class="position-relative d-flex flex-column rounded-4 overflow-hidden shadow-lg group">
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
    </section>

    <!-- Tabs Section -->
    <section class="px-3">
        <div class="d-flex border-bottom border-secondary border-opacity-25 gap-4">
            <button class="btn btn-link text-decoration-none border-bottom border-3 border-primary pb-2 pt-1 text-primary-blue px-0 rounded-0">
                <span class="fw-bold small">Transcript</span>
            </button>
            <button class="btn btn-link text-decoration-none border-bottom border-3 border-transparent pb-2 pt-1 text-secondary px-0 rounded-0">
                <span class="fw-bold small">Notes</span>
            </button>
            <button class="btn btn-link text-decoration-none border-bottom border-3 border-transparent pb-2 pt-1 text-secondary px-0 rounded-0">
                <span class="fw-bold small">Resources</span>
            </button>
        </div>
    </section>

    <!-- Transcript Body -->
    <section class="mx-3 mt-2 bg-background-darker rounded-3 overflow-auto" style="max-height: 192px;">
        <div class="p-3 d-flex flex-column gap-3">
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
    </section>

    <!-- SectionHeader: Foundry Task -->
    <section class="mt-4 border-top border-secondary border-opacity-25 pt-4">
        <div class="d-flex align-items-center justify-content-between px-3 pb-2">
            <div class="d-flex align-items-center gap-2">
                <div class="bg-primary-blue rounded-circle p-1" style="width: 8px; height: 8px;"></div>
                <h2 class="h5 fw-bold text-white m-0">Foundry Task: MRI Analysis</h2>
            </div>
            <span class="badge bg-warning bg-opacity-10 text-warning border border-warning border-opacity-25 small fw-bold text-uppercase tracking-wider">Critical</span>
        </div>
        <p class="text-secondary small px-3 mb-3">
            Complete the CNN architecture to identify glioma tissue in the provided T1 dataset.
        </p>
    </section>

    <!-- IDE / Code Area -->
    <section class="px-3">
        <div class="rounded-4 overflow-hidden border border-secondary border-opacity-50 shadow-lg" style="background-color: #1e1e1e;">
            <div class="d-flex align-items-center justify-content-between px-3 py-2 border-bottom border-secondary border-opacity-50" style="background-color: #2d2d2d;">
                <div class="d-flex gap-2">
                    <div class="rounded-circle bg-danger" style="width: 10px; height: 10px;"></div>
                    <div class="rounded-circle bg-warning" style="width: 10px; height: 10px;"></div>
                    <div class="rounded-circle bg-success" style="width: 10px; height: 10px;"></div>
                </div>
                <p class="text-secondary font-monospace xsmall mb-0" style="font-size: 10px;">segmentation_model.py</p>
                <span class="material-symbols-outlined text-secondary" style="font-size: 14px;">content_copy</span>
            </div>
            <div class="p-3 font-monospace small text-white overflow-auto">
                 <div class="d-flex">
                    <span class="text-secondary me-3 user-select-none">1</span>
                    <div><span style="color: #c586c0">import</span> <span style="color: #9cdcfe">torch.nn</span> <span style="color: #c586c0">as</span> <span style="color: #9cdcfe">nn</span></div>
                </div>
                <div class="d-flex">
                    <span class="text-secondary me-3 user-select-none">2</span>
                    <div><span style="color: #d4d4d4">class</span> <span style="color: #4ec9b0">GliomaNet</span><span style="color: #d4d4d4">(nn.Module):</span></div>
                </div>
                <div class="d-flex">
                    <span class="text-secondary me-3 user-select-none">3</span>
                    <div class="ms-3"><span style="color: #d4d4d4">def __init__(self):</span></div>
                </div>
                <div class="d-flex bg-primary-blue bg-opacity-25 mx-n3 px-3 border-start border-2 border-primary">
                    <span class="text-secondary me-3 user-select-none">4</span>
                    <div class="ms-3"><span class="text-white"># TODO: Add Normalization Layer</span></div>
                </div>
                <div class="d-flex align-items-center" style="min-height: 24px;">
                     <span class="text-secondary me-3 user-select-none">5</span>
                     <span class="text-primary-blue fw-bold ms-3">|</span>
                </div>
            </div>
        </div>

        <div class="mt-3 d-flex gap-3">
            <button class="btn bg-primary-blue text-white fw-bold py-3 rounded-4 flex-grow-1 shadow-lg d-flex align-items-center justify-content-center gap-2 hover-opacity-90">
                <span class="material-symbols-outlined fs-5">rocket_launch</span>
                Submit Task
            </button>
             <button class="btn btn-dark rounded-4 d-flex align-items-center justify-content-center p-0 border-0" style="width: 48px; height: 48px;">
                <span class="material-symbols-outlined text-secondary">help</span>
            </button>
        </div>
    </section>
</main>

<!-- AI Performance Coach Widget (Floating) -->
<div class="fixed-bottom z-3 p-4 d-flex flex-column align-items-end" id="aiCoachWidget" style="bottom: 20px; right: 20px; pointer-events: none;">
    <div class="position-relative group mb-3" style="pointer-events: auto;">
        <!-- Tooltip/Prompt Bubble -->
        <div class="bg-white rounded-4 p-3 shadow-lg border border-secondary border-opacity-25 mb-3" style="width: 260px; display: none;" id="aiCoachBubble">
            <div class="d-flex align-items-start gap-2">
                <div class="bg-primary-blue bg-opacity-10 p-2 rounded-3">
                    <span class="material-symbols-outlined text-primary-blue filled">auto_awesome</span>
                </div>
                <div>
                    <p class="fw-bold text-secondary text-uppercase tracking-tight mb-1" style="font-size: 10px;">AI Coach</p>
                    <p class="text-dark small mb-0" id="aiCoachText">Loading suggestion...</p>
                </div>
            </div>
            <!-- Arrow -->
            <div class="position-absolute bg-white border-bottom border-end border-secondary border-opacity-25" style="width: 16px; height: 16px; bottom: -8px; right: 24px; transform: rotate(45deg);"></div>
        </div>

        <!-- Main FAB -->
        <button class="btn btn-dark rounded-circle shadow-lg d-flex align-items-center justify-content-center border-4 border-white position-relative" style="width: 56px; height: 56px; background-color: var(--primary-blue) !important;" onclick="toggleCoach()">
            <span class="material-symbols-outlined fs-3 text-white filled">smart_toy</span>
        </button>
    </div>
</div>

<script>
    async function fetchAICoach() {
        const bubble = document.getElementById('aiCoachBubble');
        const textElement = document.getElementById('aiCoachText');

        try {
            // Using localhost:8000 (FastAPI default)
            // Note: In a real scenario, this would be an environment variable
            const response = await fetch('http://localhost:8000/api/coach/hint');
            if (response.ok) {
                const data = await response.json();
                textElement.innerHTML = `${data.message}`;
            } else {
                textElement.innerText = "Coach is offline.";
            }
        } catch (error) {
            console.error('Error fetching AI coach:', error);
            textElement.innerText = "Coach connection failed.";
        }
    }

    function toggleCoach() {
        const bubble = document.getElementById('aiCoachBubble');
        if (bubble.style.display === 'none') {
            bubble.style.display = 'block';
            fetchAICoach(); // Fetch when opened
        } else {
            bubble.style.display = 'none';
        }
    }

    // Auto open for demo
    setTimeout(() => {
        toggleCoach();
    }, 1000);
</script>

<?php include 'includes/footer.php'; ?>
