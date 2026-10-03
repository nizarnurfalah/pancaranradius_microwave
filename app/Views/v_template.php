<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="description" content="Sistem Informasi Geografis Pemetaan Jarak Radius Tower Microwave" />
    <title><?= esc($judul ?? 'WebGIS') ?> | WebGIS Tower Microwave</title>
    
    <!-- Google Fonts: Inter & Plus Jakarta Sans -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Plus+Jakarta+Sans:wght@500;600;700;800&display=swap" rel="stylesheet">
    
    <!-- FontAwesome 6 -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">

    <!-- Leaflet CSS -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="" />
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Simple DataTables CSS -->
    <link href="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/style.min.css" rel="stylesheet" />
    
    <!-- SweetAlert2 -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11.10.0/dist/sweetalert2.min.css">
    
    <!-- SB Admin Base Styles -->
    <link href="<?= base_url('sb_admin/css/styles.css') ?>" rel="stylesheet" />
    
    <!-- Formal & Clean Professional WebGIS Theme -->
    <style>
        :root {
            --formal-bg: #f8fafc;
            --formal-card: #ffffff;
            --formal-sidebar: #0f172a;
            --formal-primary: #1d4ed8;
            --formal-primary-hover: #1e40af;
            --formal-secondary: #475569;
            --formal-border: #e2e8f0;
            --formal-border-dark: #cbd5e1;
            --formal-dark-text: #0f172a;
            --formal-light-text: #64748b;
        }

        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif !important;
            background-color: var(--formal-bg);
            color: var(--formal-dark-text);
        }

        /* Top Navbar Formal */
        .sb-topnav {
            background: #ffffff !important;
            border-bottom: 1px solid var(--formal-border);
            height: 60px;
            padding: 0 1.5rem;
            box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.05);
        }

        .navbar-brand-custom {
            display: flex;
            align-items: center;
            gap: 10px;
            font-family: 'Plus Jakarta Sans', sans-serif;
            font-weight: 700;
            font-size: 1.1rem;
            color: #0f172a !important;
            text-decoration: none;
        }

        .brand-badge-icon {
            width: 34px;
            height: 34px;
            background: #1d4ed8;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #ffffff;
            font-size: 1rem;
        }

        /* Formal Sidebar */
        #layoutSidenav_nav .sb-sidenav {
            background: #0f172a !important;
            border-right: 1px solid #1e293b;
        }

        .sb-sidenav-dark .sb-sidenav-menu-heading {
            color: #94a3b8 !important;
            font-size: 0.72rem !important;
            text-transform: uppercase;
            letter-spacing: 0.75px;
            font-weight: 700;
            padding: 1.25rem 1.25rem 0.4rem;
        }

        .sb-sidenav-dark .sb-sidenav-menu .nav-link {
            color: #cbd5e1 !important;
            font-weight: 500;
            font-size: 0.86rem;
            padding: 0.65rem 1.1rem;
            border-radius: 6px;
            margin: 2px 10px;
            transition: all 0.15s ease-in-out;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .sb-sidenav-dark .sb-sidenav-menu .nav-link:hover {
            color: #ffffff !important;
            background: #1e293b;
        }

        .sb-sidenav-dark .sb-sidenav-menu .nav-link.active {
            color: #ffffff !important;
            background: #1d4ed8 !important;
            font-weight: 600;
        }

        .sb-sidenav-dark .sb-sidenav-menu .nav-link .sb-nav-link-icon {
            color: #94a3b8;
            font-size: 0.95rem;
            width: 20px;
        }

        .sb-sidenav-dark .sb-sidenav-menu .nav-link:hover .sb-nav-link-icon,
        .sb-sidenav-dark .sb-sidenav-menu .nav-link.active .sb-nav-link-icon {
            color: #ffffff;
        }

        .sb-sidenav-footer {
            background: #020617 !important;
            border-top: 1px solid #1e293b;
            padding: 0.85rem 1.25rem;
        }

        /* Content Area */
        #layoutSidenav_content {
            background: #f8fafc;
            min-height: 100vh;
        }

        .page-header-box {
            background: #ffffff;
            border: 1px solid var(--formal-border);
            border-radius: 10px;
            padding: 1.1rem 1.4rem;
            margin-bottom: 1.25rem;
            box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.04);
        }

        .gis-card {
            background: #ffffff;
            border: 1px solid var(--formal-border);
            border-radius: 10px;
            box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.05);
            margin-bottom: 1.25rem;
        }

        .gis-card-header {
            background: #ffffff;
            border-bottom: 1px solid var(--formal-border);
            padding: 0.9rem 1.25rem;
            font-weight: 600;
            color: #0f172a;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        /* Map styling */
        .leaflet-container {
            font-family: inherit !important;
            border-radius: 8px;
            border: 1px solid var(--formal-border);
        }

        /* Custom formal table */
        .table-formal {
            background: #ffffff;
            color: #0f172a;
        }
        .table-formal thead th {
            background: #f1f5f9;
            color: #475569;
            font-weight: 600;
            font-size: 0.8rem;
            border-bottom: 1px solid var(--formal-border);
            text-transform: uppercase;
            letter-spacing: 0.5px;
            padding: 0.75rem 1rem;
        }
        .table-formal tbody td {
            padding: 0.75rem 1rem;
            border-bottom: 1px solid var(--formal-border);
            font-size: 0.88rem;
        }
    </style>
</head>

<body class="sb-nav-fixed">
    <!-- Topbar -->
    <nav class="sb-topnav navbar navbar-expand">
        <a class="navbar-brand-custom" href="<?= base_url('/') ?>">
            <div class="brand-badge-icon">
                <i class="fa-solid fa-tower-broadcast"></i>
            </div>
            <div>
                <span class="text-primary">WebGIS</span> Microwave
            </div>
        </a>

        <!-- Sidebar Toggle-->
        <button class="btn btn-link btn-sm order-1 order-lg-0 me-4 me-lg-0 text-secondary" id="sidebarToggle" href="#!">
            <i class="fas fa-bars fs-6"></i>
        </button>

        <!-- Right Quick Actions -->
        <div class="d-flex align-items-center ms-auto gap-3">
            <a href="<?= base_url('Home/kalkulator') ?>" class="btn btn-sm btn-outline-primary d-none d-md-flex align-items-center gap-2 rounded-2 px-3 py-1">
                <i class="fa-solid fa-calculator"></i>
                <span class="fw-medium small">Kalkulator Fresnel & Link</span>
            </a>

            <!-- User Authentication Status -->
            <?php if(session()->get('logged_in')): ?>
                <div class="dropdown">
                    <button class="btn btn-light btn-sm dropdown-toggle d-flex align-items-center gap-2 border rounded-2 px-3" id="navbarDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                        <div class="rounded-circle d-flex align-items-center justify-content-center bg-primary text-white" style="width: 24px; height: 24px; font-size: 0.75rem;">
                            <i class="fa-solid fa-user"></i>
                        </div>
                        <span class="fw-medium small text-dark"><?= esc(session()->get('username')) ?></span>
                        <span class="badge <?= session()->get('role') === 'admin' ? 'bg-danger' : 'bg-success' ?> rounded-pill" style="font-size: 0.65rem;">
                            <?= esc(strtoupper(session()->get('role'))) ?>
                        </span>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end shadow border-0" aria-labelledby="navbarDropdown">
                        <li class="px-3 py-2 small text-secondary border-bottom">
                            <div>Masuk sebagai:</div>
                            <strong class="text-dark"><?= esc(session()->get('full_name') ?? session()->get('username')) ?></strong>
                        </li>
                        <?php if(session()->get('role') === 'admin'): ?>
                            <li><a class="dropdown-item py-2 small" href="<?= base_url('Lokasi') ?>"><i class="fa-solid fa-database me-2 text-primary"></i> Kelola Data Tower</a></li>
                        <?php endif; ?>
                        <li><a class="dropdown-item py-2 small" href="<?= base_url('Home/kalkulator') ?>"><i class="fa-solid fa-calculator me-2 text-warning"></i> Kalkulator Microwave</a></li>
                        <li><hr class="dropdown-divider" /></li>
                        <li><a class="dropdown-item text-danger py-2 small" href="<?= base_url('logout') ?>"><i class="fas fa-sign-out-alt me-2"></i> Logout</a></li>
                    </ul>
                </div>
            <?php else: ?>
                <a href="<?= base_url('login') ?>" class="btn btn-sm btn-primary rounded-2 px-3 py-1 d-flex align-items-center gap-2">
                    <i class="fa-solid fa-right-to-bracket"></i>
                    <span class="small fw-semibold">Login Petugas</span>
                </a>
            <?php endif; ?>
        </div>
    </nav>

    <!-- Layout Container -->
    <div id="layoutSidenav">
        <div id="layoutSidenav_nav">
            <nav class="sb-sidenav accordion sb-sidenav-dark" id="sidenavAccordion">
                <div class="sb-sidenav-menu">
                    <div class="nav">
                        <!-- CORE SECTION -->
                        <div class="sb-sidenav-menu-heading">Menu Utama</div>
                        <a class="nav-link <?= (uri_string() == '' || uri_string() == 'Home' || uri_string() == 'Home/index') ? 'active' : '' ?>" href="<?= base_url('Home') ?>">
                            <div class="sb-nav-link-icon"><i class="fas fa-chart-pie"></i></div>
                            Dashboard & Statistik
                        </a>
                        <a class="nav-link <?= uri_string() == 'Home/kalkulator' ? 'active' : '' ?>" href="<?= base_url('Home/kalkulator') ?>">
                            <div class="sb-nav-link-icon"><i class="fa-solid fa-calculator"></i></div>
                            Kalkulator Microwave & Fresnel
                        </a>

                        <!-- SIMULASI & RADIUS TOOLS -->
                        <div class="sb-sidenav-menu-heading">Alat Peta & Radius</div>
                        <a class="nav-link <?= uri_string() == 'Home/marker' ? 'active' : '' ?>" href="<?= base_url('Home/marker') ?>">
                            <div class="sb-nav-link-icon"><i class="fas fa-map-pin"></i></div>
                            Marker & Radius Tower
                        </a>
                        <a class="nav-link <?= uri_string() == 'Home/getkordinat2' ? 'active' : '' ?>" href="<?= base_url('Home/getkordinat2') ?>">
                            <div class="sb-nav-link-icon"><i class="fa-solid fa-bullseye"></i></div>
                            Uji Titik Dalam Radius
                        </a>
                        <a class="nav-link <?= uri_string() == 'Home/circle' ? 'active' : '' ?>" href="<?= base_url('Home/circle') ?>">
                            <div class="sb-nav-link-icon"><i class="far fa-circle"></i></div>
                            Multi-Radius Antena
                        </a>
                        <a class="nav-link <?= uri_string() == 'Home/getkordinat' ? 'active' : '' ?>" href="<?= base_url('Home/getkordinat') ?>">
                            <div class="sb-nav-link-icon"><i class="fas fa-crosshairs"></i></div>
                            Inspektor Koordinat Peta
                        </a>
                        <a class="nav-link <?= uri_string() == 'Home/geolocation' ? 'active' : '' ?>" href="<?= base_url('Home/geolocation') ?>">
                            <div class="sb-nav-link-icon"><i class="fas fa-location-arrow"></i></div>
                            Geolokasi & Tower Terdekat
                        </a>
                        <a class="nav-link <?= uri_string() == 'Home/baseMap' ? 'active' : '' ?>" href="<?= base_url('Home/baseMap') ?>">
                            <div class="sb-nav-link-icon"><i class="fas fa-layer-group"></i></div>
                            Pilihan Base Map
                        </a>

                        <!-- MANAJEMEN TOWER (DATABASE) -->
                        <div class="sb-sidenav-menu-heading">Data Master Tower</div>
                        <a class="nav-link <?= uri_string() == 'Lokasi/pemetaanLokasi' ? 'active' : '' ?>" href="<?= base_url('Lokasi/pemetaanLokasi') ?>">
                            <div class="sb-nav-link-icon"><i class="fas fa-map-location-dot"></i></div>
                            Peta Sebaran Tower
                        </a>
                        <a class="nav-link <?= (uri_string() == 'Lokasi' || uri_string() == 'Lokasi/index') ? 'active' : '' ?>" href="<?= base_url('Lokasi') ?>">
                            <div class="sb-nav-link-icon"><i class="fas fa-table"></i></div>
                            Tabel Data Tower
                        </a>

                        <?php if(session()->get('role') === 'admin'): ?>
                            <a class="nav-link <?= uri_string() == 'Lokasi/inputLokasi' ? 'active' : '' ?>" href="<?= base_url('Lokasi/inputLokasi') ?>">
                                <div class="sb-nav-link-icon"><i class="fas fa-plus-circle"></i></div>
                                Tambah Tower Baru
                            </a>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Footer Sidebar -->
                <div class="sb-sidenav-footer">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <div class="small text-secondary" style="font-size: 0.72rem;">Status Login:</div>
                            <span class="fw-semibold text-white small">
                                <?= session()->get('logged_in') ? esc(session()->get('username')) : 'Tamu (Guest)' ?>
                            </span>
                        </div>
                        <span class="badge <?= session()->get('role') === 'admin' ? 'bg-danger' : (session()->get('role') === 'user' ? 'bg-success' : 'bg-secondary') ?> rounded-pill" style="font-size: 0.68rem;">
                            <?= session()->get('logged_in') ? strtoupper(esc(session()->get('role'))) : 'PUBLIC' ?>
                        </span>
                    </div>
                </div>
            </nav>
        </div>

        <!-- Main Content Body -->
        <div id="layoutSidenav_content">
            <main class="py-3">
                <div class="container-fluid px-4">
                    <!-- Page Header Banner -->
                    <div class="page-header-box d-flex flex-wrap justify-content-between align-items-center gap-3">
                        <div>
                            <h4 class="fw-bold mb-1 text-dark">
                                <?= esc($judul ?? 'WebGIS Microwave') ?>
                            </h4>
                            <p class="text-secondary small mb-0">
                                Sistem Informasi Geografis Pemetaan Jarak & Radius Transmisi Antena Tower BTS
                            </p>
                        </div>
                        <div class="d-flex align-items-center gap-2">
                            <span class="badge bg-light border text-secondary px-3 py-2 small d-none d-sm-inline-flex align-items-center gap-2">
                                <i class="fa-solid fa-circle text-success" style="font-size: 0.5rem;"></i>
                                <span>Sistem Aktif & Terhubung</span>
                            </span>
                        </div>
                    </div>

                    <!-- Flash Messages -->
                    <?php if (session()->getFlashdata('pesan')): ?>
                        <div class="alert alert-success alert-dismissible fade show d-flex align-items-center py-2 px-3" role="alert">
                            <i class="fa-solid fa-circle-check fs-5 me-2 text-success"></i>
                            <div class="small"><?= session()->getFlashdata('pesan') ?></div>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    <?php endif; ?>

                    <?php if (session()->getFlashdata('error')): ?>
                        <div class="alert alert-danger alert-dismissible fade show d-flex align-items-center py-2 px-3" role="alert">
                            <i class="fa-solid fa-triangle-exclamation fs-5 me-2 text-danger"></i>
                            <div class="small"><?= session()->getFlashdata('error') ?></div>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    <?php endif; ?>

                    <!-- Dynamic Sub-view Render -->
                    <?php if (isset($page) && $page) {
                        echo view($page);
                    } ?>
                </div>
            </main>

            <!-- Footer -->
            <footer class="py-3 mt-auto bg-white border-top">
                <div class="container-fluid px-4">
                    <div class="d-flex flex-wrap align-items-center justify-content-between small text-secondary">
                        <div>Copyright &copy; <?= date('Y') ?> WebGIS Pemetaan Jarak Radius Antar Tower Microwave.</div>
                        <div class="d-flex gap-3">
                            <span>CodeIgniter 4 + Leaflet GIS</span>
                            <span>&middot;</span>
                            <span class="text-primary">Formal & Corporate Edition</span>
                        </div>
                    </div>
                </div>
            </footer>
        </div>
    </div>

    <!-- Leaflet JS -->
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
    
    <!-- Bootstrap Bundle JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
    
    <!-- Simple DataTables JS -->
    <script src="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/umd/simple-datatables.min.js" crossorigin="anonymous"></script>
    
    <!-- SweetAlert2 JS -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.10.0/dist/sweetalert2.all.min.js"></script>

    <!-- SB Admin Scripts -->
    <script src="<?= base_url('sb_admin/js/scripts.js') ?>"></script>
</body>
</html>
