<?php include 'includes/header.php'; ?>

<!-- Top Navigation Bar -->
<header class="sticky-top bg-background-dark glass-effect border-bottom border-secondary border-opacity-25 z-3">
    <div class="d-flex align-items-center p-3 justify-content-between">
        <div class="d-flex align-items-center gap-2">
            <a href="login.php" class="text-primary-blue text-decoration-none d-flex align-items-center">
                <span class="material-symbols-outlined">arrow_back_ios_new</span>
            </a>
            <h2 class="h5 fw-bold m-0 tracking-tight">Portfolio</h2>
        </div>
        <div class="d-flex gap-3">
            <button class="btn btn-dark rounded-circle p-2 d-flex align-items-center justify-content-center bg-transparent border-0 hover-bg-secondary">
                <span class="material-symbols-outlined">share</span>
            </button>
            <button class="btn btn-dark rounded-circle p-2 d-flex align-items-center justify-content-center bg-transparent border-0 hover-bg-secondary">
                <span class="material-symbols-outlined">settings</span>
            </button>
        </div>
    </div>
</header>

<main class="container px-0 pb-5 mb-5">
    <!-- Profile Header -->
    <section class="p-4 d-flex flex-column align-items-center text-center">
        <div class="position-relative mb-3">
            <div class="rounded-circle p-1 border border-4 border-primary border-opacity-25" style="width: 128px; height: 128px; border-color: var(--primary-blue) !important;">
                <div class="w-100 h-100 rounded-circle bg-center bg-cover"
                     style="background-image: url('https://lh3.googleusercontent.com/aida-public/AB6AXuAZyv-4M8luo_vV1FKRrd3SyGLT2dU1N05pkOoELRorl3z0JhNs_wIu3qHqRbAuhkpTYqS0YVPZ_AxrkKy1wSTmwCpn0JCNPeSGludtA5jH1C6SUM0zosrp2rQ4qDhfi0mI96grL5mcYoDPHokMnCftZvmqmu12gmi19Jex7dltdVcoeJw9q4zUm6OI1mxGj66z85hF99o2J36vtvBHuhZRCaDzVal_AFMQANb7ngJesC-M5wPo_m1lDJBDWGc8MqK_omIpZ9S0RUk'); background-size: cover; background-position: center;">
                </div>
            </div>
            <div class="position-absolute bottom-0 end-0 bg-primary-blue text-white p-1 rounded-circle border border-2 border-dark d-flex align-items-center justify-content-center">
                <span class="material-symbols-outlined" style="font-size: 1rem;">verified</span>
            </div>
        </div>

        <h1 class="h3 fw-bold mb-1">John Kamau</h1>
        <p class="text-secondary fw-medium mb-3">Aspiring AI Engineer at UoN</p>

        <div class="d-flex align-items-center gap-2 bg-primary-blue bg-opacity-10 border border-primary border-opacity-25 px-3 py-1 rounded-pill mb-4 verified-glow" style="border-color: var(--primary-blue) !important;">
            <span class="material-symbols-outlined text-primary-blue fs-5">workspace_premium</span>
            <span class="text-primary-blue small fw-bold text-uppercase tracking-wider" style="font-size: 0.75rem;">Verified by Octal AI</span>
        </div>

        <button class="btn bg-primary-orange text-white w-100 py-3 rounded-4 fw-bold d-flex align-items-center justify-content-center gap-2 shadow-lg glow-orange border-0">
            <span class="material-symbols-outlined">download</span>
            Download CV
        </button>
    </section>

    <!-- Tabs Navigation -->
    <nav class="sticky-top bg-background-dark border-bottom border-secondary border-opacity-25 z-2" style="top: 70px;">
        <div class="d-flex justify-content-around px-2">
            <a href="#" class="text-decoration-none py-3 border-bottom border-2 border-primary text-primary-blue fw-bold small flex-fill text-center" style="border-color: var(--primary-blue) !important;">Projects</a>
            <a href="#" class="text-decoration-none py-3 border-bottom border-2 border-transparent text-secondary fw-bold small flex-fill text-center">Certificates</a>
            <a href="skills.php" class="text-decoration-none py-3 border-bottom border-2 border-transparent text-secondary fw-bold small flex-fill text-center">Skills</a>
        </div>
    </nav>

    <!-- Section Header -->
    <div class="px-3 pt-4 d-flex align-items-center justify-content-between">
        <h3 class="h5 fw-bold m-0">Practical Work</h3>
        <span class="text-primary-blue small fw-bold">24 Projects</span>
    </div>

    <!-- Project Cards Grid -->
    <section class="p-3 d-flex flex-column gap-4">
        <!-- Project Card 1 -->
        <div class="card bg-card-dark border border-white border-opacity-10 rounded-4 overflow-hidden shadow">
            <div class="ratio ratio-16x9" style="background-image: url('https://lh3.googleusercontent.com/aida-public/AB6AXuBk1OIguadp_ULrYAnccJkNLXg0er-Fb7AviBX7jvXnSi3yhe1wzs3zCb848PCzwT2g6Lzp5b8tHI-1ZVbXBZbdeHwBKtxwtmTTCPeruSYIoJENGlKbbOEwye0xOSmuwyBzBb-xsR_en0j5FEhq-ovO9fX54H0gf4ZVbdSMyNaykbqzIV6z4J-zvJZXAeafaaYfeBD1s3C0jZUP07KEFOUZyYBXgByNdojs36q05CXLops42T5oCFSLza5HQGHvFvlHbUgcZCSzuvc'); background-size: cover; background-position: center;"></div>
            <div class="card-body p-3">
                <div class="d-flex justify-content-between align-items-start mb-2">
                    <h4 class="h6 fw-bold text-white m-0">AI Diagnosis Tool</h4>
                    <a href="learning.php" class="text-primary-blue"><span class="material-symbols-outlined">open_in_new</span></a>
                </div>
                <p class="text-secondary small mb-3">Automated diagnostic system for agricultural health monitoring in rural Kenya.</p>
                <div class="d-flex flex-wrap gap-2 mb-3">
                    <span class="badge bg-primary-blue bg-opacity-10 text-primary-blue fw-bold text-uppercase p-2">Python</span>
                    <span class="badge bg-primary-blue bg-opacity-10 text-primary-blue fw-bold text-uppercase p-2">TensorFlow</span>
                    <span class="badge bg-primary-blue bg-opacity-10 text-primary-blue fw-bold text-uppercase p-2">CNN</span>
                </div>
                <button class="btn btn-dark w-100 py-2 fw-bold small border border-secondary border-opacity-25">View Source Code</button>
            </div>
        </div>

        <!-- Project Card 2 -->
        <div class="card bg-card-dark border border-white border-opacity-10 rounded-4 overflow-hidden shadow">
            <div class="ratio ratio-16x9" style="background-image: url('https://lh3.googleusercontent.com/aida-public/AB6AXuBtOOLYWh0On8yqhXQyGEvAkN7E2N1MJjNEOND8MnFbtc50qzTf3KtYBAYIY__Yss3NGpKwox6sGAkvtjD0bjxAvtET62sIm74tN7Zfpo-gRsidQLgDqJyT-kSefutBKPPMmWPY88wNtspwIzgKehsafuZ2jbtOOwqADj8xMuwTdVsMEqJQkickgPUfY4ob4fEBbMR80gYi36Y71ARJRLquncEGZkZtifdOvW5yJnLYJmD5X7WKPFGf_zSomZyAEqI2Dpxgwv25cEw'); background-size: cover; background-position: center;"></div>
            <div class="card-body p-3">
                <div class="d-flex justify-content-between align-items-start mb-2">
                    <h4 class="h6 fw-bold text-white m-0">Bridge Structural Analysis</h4>
                    <a href="#" class="text-primary-blue"><span class="material-symbols-outlined">open_in_new</span></a>
                </div>
                <p class="text-secondary small mb-3">Finite element analysis of suspension bridges for sustainable urban mobility.</p>
                <div class="d-flex flex-wrap gap-2 mb-3">
                    <span class="badge bg-primary-blue bg-opacity-10 text-primary-blue fw-bold text-uppercase p-2">MATLAB</span>
                    <span class="badge bg-primary-blue bg-opacity-10 text-primary-blue fw-bold text-uppercase p-2">Civil3D</span>
                </div>
                <button class="btn btn-dark w-100 py-2 fw-bold small border border-secondary border-opacity-25">View Source Code</button>
            </div>
        </div>
    </section>

    <!-- Horizontal Sections -->
    <section class="pt-2">
        <div class="px-3 pb-3">
            <h3 class="h5 fw-bold m-0">Earned Certifications</h3>
        </div>
        <div class="d-flex overflow-auto gap-3 px-3 pb-4 no-scrollbar">
            <!-- Cert 1 -->
            <div class="flex-shrink-0 card bg-card-dark border border-white border-opacity-10 rounded-4 p-3 text-center" style="width: 160px;">
                <div class="rounded-circle bg-primary-blue bg-opacity-25 d-flex align-items-center justify-content-center mx-auto mb-3" style="width: 64px; height: 64px;">
                    <span class="material-symbols-outlined text-primary-blue fs-1">psychology</span>
                </div>
                <p class="small fw-bold text-white mb-1">Machine Learning Level 1</p>
                <p class="text-secondary" style="font-size: 10px;">Octal Foundry</p>
            </div>
            <!-- Cert 2 -->
             <div class="flex-shrink-0 card bg-card-dark border border-white border-opacity-10 rounded-4 p-3 text-center" style="width: 160px;">
                <div class="rounded-circle bg-primary-blue bg-opacity-25 d-flex align-items-center justify-content-center mx-auto mb-3" style="width: 64px; height: 64px;">
                    <span class="material-symbols-outlined text-primary-blue fs-1">data_object</span>
                </div>
                <p class="small fw-bold text-white mb-1">Advanced Python Dev</p>
                <p class="text-secondary" style="font-size: 10px;">Octal Foundry</p>
            </div>
             <!-- Cert 3 -->
             <div class="flex-shrink-0 card bg-card-dark border border-white border-opacity-10 rounded-4 p-3 text-center" style="width: 160px;">
                <div class="rounded-circle bg-primary-blue bg-opacity-25 d-flex align-items-center justify-content-center mx-auto mb-3" style="width: 64px; height: 64px;">
                    <span class="material-symbols-outlined text-primary-blue fs-1">cloud_done</span>
                </div>
                <p class="small fw-bold text-white mb-1">Cloud Architecture</p>
                <p class="text-secondary" style="font-size: 10px;">AWS Training</p>
            </div>
        </div>
    </section>

     <!-- Endorsements -->
    <section class="px-3 pb-5 mb-5">
        <h3 class="h5 fw-bold mb-3">Industry Endorsements</h3>
        <div class="card bg-primary-blue bg-opacity-10 border border-primary-blue border-opacity-10 rounded-4 p-3">
            <div class="d-flex align-items-center gap-3 mb-2">
                 <div class="rounded-circle bg-secondary bg-cover bg-center" style="width: 32px; height: 32px; background-image: url('https://lh3.googleusercontent.com/aida-public/AB6AXuAqbKLYqHHv_M5UuvHmRpO_bGAUHGd0ZddEeBTHLNM3rkvNH1_1Yk_ARMOn5XFpKzlWpOkSqW1wtWn2op9HQpsHBecf_Z4-0XsoMqmzXComTRmgbs264UFsyPu2zxYKojIjVtx5EDFSYhFWjGSLWlUr9si7P35h3j8l-tmAgQCD_n_ggxrCZuO24EEQ-U1P6RO6BHQ-377vdDnroP8FdW6mI1c38bkdn1KtDsPtSgSBUzN4Bw83MZn7qs-LOu3zbwIwVNry8T15mQk'); background-size: cover;"></div>
                 <div>
                    <p class="small fw-bold mb-0">Sarah Mwangi</p>
                    <p class="text-secondary mb-0" style="font-size: 10px;">Senior Developer, Safaricom PLC</p>
                 </div>
            </div>
            <p class="text-secondary fst-italic small mb-0">"John's work on the AI diagnosis tool showed exceptional understanding of neural network optimization. Highly recommended for data roles."</p>
        </div>
    </section>
</main>

<!-- Bottom Navigation Bar (iOS Style) -->
<nav class="fixed-bottom glass-effect border-top border-white border-opacity-10 px-4 py-3 d-flex justify-content-between align-items-center">
    <button class="btn btn-link text-primary-blue text-decoration-none p-0 d-flex flex-column align-items-center gap-1">
        <span class="material-symbols-outlined filled">person</span>
        <span class="small fw-medium" style="font-size: 10px;">Profile</span>
    </button>
    <button class="btn btn-link text-secondary text-decoration-none p-0 d-flex flex-column align-items-center gap-1">
        <span class="material-symbols-outlined">explore</span>
        <span class="small fw-medium" style="font-size: 10px;">Explore</span>
    </button>
    <a href="learning.php" class="btn btn-link text-secondary text-decoration-none p-0 d-flex flex-column align-items-center gap-1">
        <span class="material-symbols-outlined">school</span>
        <span class="small fw-medium" style="font-size: 10px;">Learning</span>
    </a>
    <button class="btn btn-link text-secondary text-decoration-none p-0 d-flex flex-column align-items-center gap-1">
        <span class="material-symbols-outlined">notifications</span>
        <span class="small fw-medium" style="font-size: 10px;">Alerts</span>
    </button>
</nav>

<?php include 'includes/footer.php'; ?>
