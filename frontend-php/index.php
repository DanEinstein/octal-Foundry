<?php include 'includes/header.php'; ?>

<main class="container-fluid p-3 p-lg-4">
    <!-- Page Header -->
    <div class="d-flex flex-wrap align-items-center justify-content-between gap-2 mb-4">
        <h1 class="h5 h4-md fw-bold mb-0">My Portfolio</h1>
        <div class="d-flex gap-2">
            <button class="btn btn-outline-secondary rounded-pill px-2 px-sm-3 d-flex align-items-center gap-1 gap-sm-2">
                <span class="material-symbols-outlined" style="font-size: 18px;">share</span>
                <span class="d-none d-sm-inline">Share</span>
            </button>
            <button class="btn btn-outline-secondary rounded-pill px-2 px-sm-3 d-flex align-items-center">
                <span class="material-symbols-outlined" style="font-size: 18px;">settings</span>
            </button>
        </div>
    </div>

    <!-- Profile Header -->
    <section class="card bg-card-dark border border-white border-opacity-10 rounded-4 p-3 p-md-4 mb-4">
        <div class="d-flex flex-column align-items-center text-center">
            <!-- Avatar -->
            <div class="position-relative mb-3">
                <div class="rounded-circle p-1 border border-3 border-md-4 border-primary border-opacity-25" style="width: 100px; height: 100px; border-color: var(--primary-blue) !important;">
                    <div class="w-100 h-100 rounded-circle bg-center bg-cover"
                         style="background-image: url('https://lh3.googleusercontent.com/aida-public/AB6AXuAZyv-4M8luo_vV1FKRrd3SyGLT2dU1N05pkOoELRorl3z0JhNs_wIu3qHqRbAuhkpTYqS0YVPZ_AxrkKy1wSTmwCpn0JCNPeSGludtA5jH1C6SUM0zosrp2rQ4qDhfi0mI96grL5mcYoDPHokMnCftZvmqmu12gmi19Jex7dltdVcoeJw9q4zUm6OI1mxGj66z85hF99o2J36vtvBHuhZRCaDzVal_AFMQANb7ngJesC-M5wPo_m1lDJBDWGc8MqK_omIpZ9S0RUk'); background-size: cover; background-position: center;">
                    </div>
                </div>
                <div class="position-absolute bottom-0 end-0 bg-primary-blue text-white p-1 rounded-circle border border-2 border-dark d-flex align-items-center justify-content-center">
                    <span class="material-symbols-outlined" style="font-size: 0.875rem;">verified</span>
                </div>
            </div>

            <!-- User Info -->
            <h1 class="h4 fw-bold mb-1">John Kamau</h1>
            <p class="text-secondary fw-medium mb-3 small">Aspiring AI Engineer at UoN</p>

            <!-- Verified Badge -->
            <div class="d-inline-flex align-items-center gap-2 bg-primary-blue bg-opacity-10 border border-primary border-opacity-25 px-2 px-sm-3 py-1 rounded-pill mb-3 verified-glow" style="border-color: var(--primary-blue) !important;">
                <span class="material-symbols-outlined text-primary-blue" style="font-size: 1.25rem;">workspace_premium</span>
                <span class="text-primary-blue small fw-bold text-uppercase tracking-wider" style="font-size: 0.65rem;">Verified by Octal AI</span>
            </div>

            <!-- Action Buttons -->
            <div class="d-flex flex-column flex-sm-row gap-2 w-100 justify-content-center">
                <button class="btn bg-primary-orange text-white py-2 px-3 px-sm-4 rounded-pill fw-bold d-flex align-items-center justify-content-center gap-2 border-0 glow-orange">
                    <span class="material-symbols-outlined" style="font-size: 1.25rem;">download</span>
                    <span>Download CV</span>
                </button>
                <a href="certificates.php" class="btn btn-outline-secondary py-2 px-3 px-sm-4 rounded-pill fw-bold d-flex align-items-center justify-content-center gap-2">
                    <span class="material-symbols-outlined" style="font-size: 1.25rem;">workspace_premium</span>
                    <span>Certificates</span>
                </a>
            </div>
        </div>
    </section>

    <!-- Tabs Navigation -->
    <nav class="bg-card-dark border border-white border-opacity-10 rounded-4 mb-4 overflow-hidden">
        <div class="d-flex">
            <a href="index.php" class="text-decoration-none py-2 py-sm-3 border-bottom border-2 border-primary text-primary-blue fw-bold small flex-fill text-center" style="border-color: var(--primary-blue) !important;">Projects</a>
            <a href="certificates.php" class="text-decoration-none py-2 py-sm-3 border-bottom border-2 border-transparent text-secondary fw-bold small flex-fill text-center">Certificates</a>
            <a href="skills.php" class="text-decoration-none py-2 py-sm-3 border-bottom border-2 border-transparent text-secondary fw-bold small flex-fill text-center">Skills</a>
        </div>
    </nav>

    <!-- Section Header -->
    <div class="d-flex align-items-center justify-content-between mb-3">
        <h3 class="h6 h5-sm fw-bold m-0">Practical Work</h3>
        <span class="text-primary-blue small fw-bold">24 Projects</span>
    </div>

    <!-- Project Cards Grid -->
    <div class="row g-3 g-lg-4 mb-4">
        <!-- Project Card 1 -->
        <div class="col-12 col-md-6">
            <div class="card bg-card-dark border border-white border-opacity-10 rounded-4 overflow-hidden shadow h-100">
                <div class="ratio ratio-16x9" style="background-image: url('https://lh3.googleusercontent.com/aida-public/AB6AXuBk1OIguadp_ULrYAnccJkNLXg0er-Fb7AviBX7jvXnSi3yhe1wzs3zCb848PCzwT2g6Lzp5b8tHI-1ZVbXBZbdeHwBKtxwtmTTCPeruSYIoJENGlKbbOEwye0xOSmuwyBzBb-xsR_en0j5FEhq-ovO9fX54H0gf4ZVbdSMyNaykbqzIV6z4J-zvJZXAeafaaYfeBD1s3C0jZUP07KEFOUZyYBXgByNdojs36q05CXLops42T5oCFSLza5HQGHvFvlHbUgcZCSzuvc'); background-size: cover; background-position: center;"></div>
                <div class="card-body p-3">
                    <div class="d-flex justify-content-between align-items-start mb-2">
                        <h4 class="h6 fw-bold text-white m-0">AI Diagnosis Tool</h4>
                        <a href="learning.php" class="text-primary-blue flex-shrink-0 ms-2"><span class="material-symbols-outlined">open_in_new</span></a>
                    </div>
                    <p class="text-secondary small mb-3">Automated diagnostic system for agricultural health monitoring in rural Kenya.</p>
                    <div class="d-flex flex-wrap gap-1 gap-sm-2 mb-3">
                        <span class="badge bg-primary-blue bg-opacity-10 text-primary-blue fw-bold text-uppercase px-2 py-1" style="font-size: 0.65rem;">Python</span>
                        <span class="badge bg-primary-blue bg-opacity-10 text-primary-blue fw-bold text-uppercase px-2 py-1" style="font-size: 0.65rem;">TensorFlow</span>
                        <span class="badge bg-primary-blue bg-opacity-10 text-primary-blue fw-bold text-uppercase px-2 py-1" style="font-size: 0.65rem;">CNN</span>
                    </div>
                    <button class="btn btn-dark w-100 py-2 fw-bold small border border-secondary border-opacity-25">View Source Code</button>
                </div>
            </div>
        </div>

        <!-- Project Card 2 -->
        <div class="col-12 col-md-6">
            <div class="card bg-card-dark border border-white border-opacity-10 rounded-4 overflow-hidden shadow h-100">
                <div class="ratio ratio-16x9" style="background-image: url('https://lh3.googleusercontent.com/aida-public/AB6AXuBtOOLYWh0On8yqhXQyGEvAkN7E2N1MJjNEOND8MnFbtc50qzTf3KtYBAYIY__Yss3NGpKwox6sGAkvtjD0bjxAvtET62sIm74tN7Zfpo-gRsidQLgDqJyT-kSefutBKPPMmWPY88wNtspwIzgKehsafuZ2jbtOOwqADj8xMuwTdVsMEqJQkickgPUfY4ob4fEBbMR80gYi36Y71ARJRLquncEGZkZtifdOvW5yJnLYJmD5X7WKPFGf_zSomZyAEqI2Dpxgwv25cEw'); background-size: cover; background-position: center;"></div>
                <div class="card-body p-3">
                    <div class="d-flex justify-content-between align-items-start mb-2">
                        <h4 class="h6 fw-bold text-white m-0">Bridge Structural Analysis</h4>
                        <a href="#" class="text-primary-blue flex-shrink-0 ms-2"><span class="material-symbols-outlined">open_in_new</span></a>
                    </div>
                    <p class="text-secondary small mb-3">Finite element analysis of suspension bridges for sustainable urban mobility.</p>
                    <div class="d-flex flex-wrap gap-1 gap-sm-2 mb-3">
                        <span class="badge bg-primary-blue bg-opacity-10 text-primary-blue fw-bold text-uppercase px-2 py-1" style="font-size: 0.65rem;">MATLAB</span>
                        <span class="badge bg-primary-blue bg-opacity-10 text-primary-blue fw-bold text-uppercase px-2 py-1" style="font-size: 0.65rem;">Civil3D</span>
                    </div>
                    <button class="btn btn-dark w-100 py-2 fw-bold small border border-secondary border-opacity-25">View Source Code</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Earned Certifications Section -->
    <section class="mb-4">
        <div class="d-flex align-items-center justify-content-between mb-3">
            <h3 class="h6 h5-sm fw-bold m-0">Earned Certifications</h3>
            <a href="certificates.php" class="text-primary-blue text-decoration-none small fw-bold">View All</a>
        </div>
        <div class="d-flex overflow-auto gap-3 pb-2 no-scrollbar">
            <!-- Cert 1 -->
            <a href="certificates.php" class="flex-shrink-0 card bg-card-dark border border-white border-opacity-10 rounded-4 p-3 text-center text-decoration-none" style="min-width: 140px; max-width: 160px;">
                <div class="rounded-circle bg-primary-blue bg-opacity-25 d-flex align-items-center justify-content-center mx-auto mb-2" style="width: 56px; height: 56px;">
                    <span class="material-symbols-outlined text-primary-blue" style="font-size: 1.75rem;">psychology</span>
                </div>
                <p class="small fw-bold text-white mb-1" style="font-size: 0.8rem;">Machine Learning Level 1</p>
                <p class="text-secondary mb-0" style="font-size: 0.65rem;">Octal Foundry</p>
            </a>
            <!-- Cert 2 -->
            <a href="certificates.php" class="flex-shrink-0 card bg-card-dark border border-white border-opacity-10 rounded-4 p-3 text-center text-decoration-none" style="min-width: 140px; max-width: 160px;">
                <div class="rounded-circle bg-primary-blue bg-opacity-25 d-flex align-items-center justify-content-center mx-auto mb-2" style="width: 56px; height: 56px;">
                    <span class="material-symbols-outlined text-primary-blue" style="font-size: 1.75rem;">data_object</span>
                </div>
                <p class="small fw-bold text-white mb-1" style="font-size: 0.8rem;">Advanced Python Dev</p>
                <p class="text-secondary mb-0" style="font-size: 0.65rem;">Octal Foundry</p>
            </a>
            <!-- Cert 3 -->
            <a href="certificates.php" class="flex-shrink-0 card bg-card-dark border border-white border-opacity-10 rounded-4 p-3 text-center text-decoration-none" style="min-width: 140px; max-width: 160px;">
                <div class="rounded-circle bg-primary-blue bg-opacity-25 d-flex align-items-center justify-content-center mx-auto mb-2" style="width: 56px; height: 56px;">
                    <span class="material-symbols-outlined text-primary-blue" style="font-size: 1.75rem;">cloud_done</span>
                </div>
                <p class="small fw-bold text-white mb-1" style="font-size: 0.8rem;">Cloud Architecture</p>
                <p class="text-secondary mb-0" style="font-size: 0.65rem;">AWS Training</p>
            </a>
        </div>
    </section>

    <!-- Endorsements -->
    <section>
        <h3 class="h6 h5-sm fw-bold mb-3">Industry Endorsements</h3>
        <div class="card bg-primary-blue bg-opacity-10 border border-primary-blue border-opacity-10 rounded-4 p-3">
            <div class="d-flex align-items-center gap-2 gap-sm-3 mb-2">
                <div class="rounded-circle bg-secondary bg-cover bg-center flex-shrink-0" style="width: 32px; height: 32px; background-image: url('https://lh3.googleusercontent.com/aida-public/AB6AXuAqbKLYqHHv_M5UuvHmRpO_bGAUHGd0ZddEeBTHLNM3rkvNH1_1Yk_ARMOn5XFpKzlWpOkSqW1wtWn2op9HQpsHBecf_Z4-0XsoMqmzXComTRmgbs264UFsyPu2zxYKojIjVtx5EDFSYhFWjGSLWlUr9si7P35h3j8l-tmAgQCD_n_ggxrCZuO24EEQ-U1P6RO6BHQ-377vdDnroP8FdW6mI1c38bkdn1KtDsPtSgSBUzN4Bw83MZn7qs-LOu3zbwIwVNry8T15mQk'); background-size: cover;"></div>
                <div class="min-width-0">
                    <p class="small fw-bold mb-0">Sarah Mwangi</p>
                    <p class="text-secondary mb-0 text-truncate" style="font-size: 0.65rem;">Senior Developer, Safaricom PLC</p>
                </div>
            </div>
            <p class="text-secondary fst-italic small mb-0">"John's work on the AI diagnosis tool showed exceptional understanding of neural network optimization. Highly recommended for data roles."</p>
        </div>
    </section>
</main>

<?php include 'includes/footer.php'; ?>
