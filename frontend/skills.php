<?php include 'includes/header.php'; ?>

<!-- Top App Bar -->
<header class="sticky-top bg-background-dark glass-effect border-bottom border-secondary border-opacity-25 z-3">
    <div class="d-flex align-items-center p-3 justify-content-between">
        <button class="btn btn-link text-secondary p-0 d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
            <span class="material-symbols-outlined">menu</span>
        </button>
        <h1 class="h5 fw-bold text-white m-0 text-center flex-grow-1">Skills Radar</h1>
        <div class="d-flex justify-content-end" style="width: 40px;">
            <button class="btn btn-hover-light text-secondary rounded-circle p-0 d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                <span class="material-symbols-outlined">notifications</span>
            </button>
        </div>
    </div>
</header>

<main class="container px-0 pb-5 mb-5">
    <!-- Radar Chart Visualizer -->
    <section class="p-3 d-flex flex-column align-items-center">
        <div class="position-relative w-100 mt-4 d-flex align-items-center justify-content-center" style="aspect-ratio: 1/1; max-width: 320px;">
            <!-- Hexagonal Background Grid -->
            <!-- Using custom clip-path for radar grid shape -->
            <style>
                .radar-grid { clip-path: polygon(50% 0%, 100% 25%, 100% 75%, 50% 100%, 0% 75%, 0% 25%); }
                .glow-blue-filter { filter: drop-shadow(0 0 8px rgba(17, 115, 212, 0.4)); }
            </style>
            <div class="position-absolute top-0 start-0 w-100 h-100 border border-secondary border-opacity-25 radar-grid opacity-25"></div>
            <div class="position-absolute border border-secondary border-opacity-25 radar-grid opacity-25" style="width: 75%; height: 75%;"></div>
            <div class="position-absolute border border-secondary border-opacity-25 radar-grid opacity-25" style="width: 50%; height: 50%;"></div>
            <div class="position-absolute border border-secondary border-opacity-25 radar-grid opacity-25" style="width: 25%; height: 25%;"></div>

            <!-- Radar Shape -->
            <div class="position-absolute w-100 h-100 glow-blue-filter"
                 style="clip-path: polygon(50% 15%, 85% 30%, 80% 80%, 40% 90%, 15% 70%, 20% 35%); background: rgba(13, 127, 242, 0.3); border: 2px solid var(--primary-blue);"></div>

            <!-- Labels -->
            <span class="position-absolute top-0 text-primary-blue fw-bold text-uppercase tracking-widest small" style="font-size: 10px;">Technical Proficiency</span>
            <span class="position-absolute top-25 end-0 text-secondary fw-bold text-uppercase tracking-widest small" style="transform: rotate(90deg) translateX(50%); font-size: 10px; right: -10px;">Soft Skills</span>
            <span class="position-absolute bottom-25 end-0 text-secondary fw-bold text-uppercase tracking-widest small" style="transform: rotate(90deg) translateX(50%); font-size: 10px; right: -10px;">Practical</span>
            <span class="position-absolute bottom-0 text-secondary fw-bold text-uppercase tracking-widest small" style="font-size: 10px;">Theory Knowledge</span>
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
            <span class="small fw-bold text-primary-blue">View All</span>
        </div>
        <div class="d-flex flex-column gap-3">
            <!-- Skill Item -->
            <div class="bg-card-dark border border-secondary border-opacity-25 p-3 rounded-4 bg-opacity-50">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <span class="fw-semibold small">Python Development</span>
                    <span class="small fw-bold text-primary-blue">88%</span>
                </div>
                <div class="progress bg-secondary bg-opacity-25" style="height: 6px;">
                    <div class="progress-bar bg-primary-blue rounded-pill" role="progressbar" style="width: 88%"></div>
                </div>
            </div>
            <!-- Skill Item -->
            <div class="bg-card-dark border border-secondary border-opacity-25 p-3 rounded-4 bg-opacity-50">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <span class="fw-semibold small">Algorithm Design</span>
                    <span class="small fw-bold text-primary-orange">72%</span>
                </div>
                <div class="progress bg-secondary bg-opacity-25" style="height: 6px;">
                    <div class="progress-bar bg-primary-orange rounded-pill" role="progressbar" style="width: 72%"></div>
                </div>
            </div>
            <!-- Skill Item -->
            <div class="bg-card-dark border border-secondary border-opacity-25 p-3 rounded-4 bg-opacity-50">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <span class="fw-semibold small">Cloud Infrastructure</span>
                    <span class="small fw-bold text-secondary">45%</span>
                </div>
                <div class="progress bg-secondary bg-opacity-25" style="height: 6px;">
                    <div class="progress-bar bg-secondary rounded-pill" role="progressbar" style="width: 45%"></div>
                </div>
            </div>
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
                <p class="small text-secondary mb-3">Based on your proficiency in Technical and Problem Solving areas, you have a high match for:</p>
                <div class="d-flex flex-wrap gap-2">
                    <div class="bg-white bg-opacity-10 backdrop-blur px-3 py-1 rounded-pill d-flex align-items-center gap-2 border border-white border-opacity-10">
                        <span class="small fw-bold">Data Scientist</span>
                        <span class="badge bg-primary-blue text-white rounded px-1 fw-bold" style="font-size: 10px;">94%</span>
                    </div>
                     <div class="bg-white bg-opacity-10 backdrop-blur px-3 py-1 rounded-pill d-flex align-items-center gap-2 border border-white border-opacity-10">
                        <span class="small fw-bold">Backend Dev</span>
                        <span class="badge bg-secondary text-white rounded px-1 fw-bold" style="font-size: 10px;">88%</span>
                    </div>
                </div>
            </div>
            <!-- Abstract Glow -->
            <div class="position-absolute bottom-0 end-0 rounded-circle bg-primary-blue bg-opacity-10 blur-3xl" style="width: 128px; height: 128px; filter: blur(40px); transform: translate(30%, 30%);"></div>
        </div>
    </section>

    <!-- Skill Growth Trend -->
    <section class="mt-4 px-3">
        <h3 class="h5 fw-bold text-white mb-3">Skill Growth Trend</h3>
        <div class="bg-card-dark border border-secondary border-opacity-25 p-3 rounded-4 d-flex flex-column bg-opacity-50" style="height: 192px;">
            <div class="flex-grow-1 d-flex align-items-end gap-1 px-2 pb-4 position-relative">
                <!-- Bars -->
                <div class="flex-fill bg-primary-blue bg-opacity-25 rounded-top-1 position-relative group cursor-pointer" style="height: 25%;"></div>
                <div class="flex-fill bg-primary-blue bg-opacity-25 rounded-top-1" style="height: 33%; opacity: 0.3;"></div>
                <div class="flex-fill bg-primary-blue bg-opacity-25 rounded-top-1" style="height: 40%; opacity: 0.4;"></div>
                <div class="flex-fill bg-primary-blue bg-opacity-25 rounded-top-1 position-relative" style="height: 60%; opacity: 0.6;">
                    <div class="position-absolute top-0 end-0 translate-middle-y bg-primary-orange rounded-circle border border-2 border-dark shadow" style="width: 12px; height: 12px; margin-right: -4px; margin-top: -6px;"></div>
                </div>
                <div class="flex-fill bg-primary-blue bg-opacity-25 rounded-top-1" style="height: 75%; opacity: 0.8;"></div>
                <div class="flex-fill bg-primary-blue rounded-top-1" style="height: 90%;"></div>

                <!-- Horizontal Grid Lines -->
                <div class="position-absolute start-0 end-0 top-0 bottom-0 d-flex flex-column justify-content-between pointer-events-none pb-4 px-2" style="z-index: 0;">
                    <div class="border-top border-secondary border-opacity-10 w-100"></div>
                    <div class="border-top border-secondary border-opacity-10 w-100"></div>
                    <div class="border-top border-secondary border-opacity-10 w-100"></div>
                </div>
            </div>
            <div class="d-flex justify-content-between px-2 pt-2 border-top border-secondary border-opacity-50">
                <span class="text-secondary fw-bold" style="font-size: 10px;">JAN</span>
                <span class="text-secondary fw-bold" style="font-size: 10px;">MAR</span>
                <span class="text-secondary fw-bold" style="font-size: 10px;">MAY</span>
                <span class="text-secondary fw-bold" style="font-size: 10px;">JUN</span>
            </div>
        </div>
    </section>

    <!-- Latest Achievement -->
    <section class="mt-4 px-3 mb-5">
        <h3 class="h5 fw-bold text-white mb-3">Latest Achievement</h3>
        <div class="w-100 rounded-4 overflow-hidden position-relative shadow-sm"
             style="height: 128px; background-image: url('https://lh3.googleusercontent.com/aida-public/AB6AXuA-rgURQ77ai23wi1sEoI2aOwBToL925vxMEsc4iMUX80N3JJ1BShbwexhyowv7ClwgATKuhpEIB2EHyivVmjIXl6gI7lzQCy-c6B-JsWPrsCLPPaxqYlSxcMzeXZVfZzjf_QcfHEbWvFCbgQfQPI1X56ruJwrUVgUn6Abzd3VT2cLmHRkAIcWYI6YmsikDGSH55IDl5xyrPjhMm1-1Bl9_03_PH2-oqNIblG4TVwEi0gz-ECQ99hnk0unyfQln95DeTyBTvM5Inug'); background-size: cover; background-position: center;">
            <div class="position-absolute top-0 start-0 w-100 h-100 d-flex flex-column justify-content-end p-3" style="background: linear-gradient(to top, rgba(16,25,34,0.9), transparent);">
                <p class="small fw-bold text-primary-orange text-uppercase tracking-widest mb-1">Milestone Reached</p>
                <p class="fw-bold text-white mb-0">Full Stack Certification - Tier 1</p>
            </div>
        </div>
    </section>
</main>

<!-- Bottom Navigation Bar (Matching Screen 6) -->
<nav class="fixed-bottom bg-background-dark glass-effect border-top border-secondary border-opacity-25 px-4 py-3 d-flex justify-content-between align-items-center z-3">
    <a href="index.php" class="btn btn-link text-secondary text-decoration-none p-0 d-flex flex-column align-items-center gap-1">
        <span class="material-symbols-outlined">home</span>
        <span class="small fw-medium" style="font-size: 10px;">Home</span>
    </a>
    <button class="btn btn-link text-primary-blue text-decoration-none p-0 d-flex flex-column align-items-center gap-1">
        <span class="material-symbols-outlined filled">analytics</span>
        <span class="small fw-medium" style="font-size: 10px;">Radar</span>
    </button>
    <button class="btn btn-link text-secondary text-decoration-none p-0 d-flex flex-column align-items-center gap-1">
        <span class="material-symbols-outlined">explore</span>
        <span class="small fw-medium" style="font-size: 10px;">Jobs</span>
    </button>
    <button class="btn btn-link text-secondary text-decoration-none p-0 d-flex flex-column align-items-center gap-1">
        <span class="material-symbols-outlined">person</span>
        <span class="small fw-medium" style="font-size: 10px;">Profile</span>
    </button>
</nav>

<?php include 'includes/footer.php'; ?>
