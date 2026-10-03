<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <title>Login Petugas | WebGIS Microwave Tower</title>
    
    <!-- Google Fonts Inter -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Plus+Jakarta+Sans:wght@600;700;800&display=swap" rel="stylesheet">
    
    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- FontAwesome 6 -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    
    <style>
        body {
            font-family: 'Inter', sans-serif;
            background-color: #f1f5f9;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #0f172a;
        }
        .login-card {
            background: #ffffff;
            border: 1px solid #e2e8f0;
            border-radius: 12px;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.08), 0 2px 4px -2px rgba(0, 0, 0, 0.05);
            padding: 2.5rem;
            width: 100%;
            max-width: 440px;
        }
        .brand-icon {
            width: 56px;
            height: 56px;
            background: #1d4ed8;
            border-radius: 12px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 1.6rem;
            color: #ffffff;
            margin-bottom: 1rem;
        }
        .form-control {
            border: 1px solid #cbd5e1;
            border-radius: 8px;
            padding: 0.7rem 0.9rem;
            font-size: 0.92rem;
        }
        .form-control:focus {
            border-color: #1d4ed8;
            box-shadow: 0 0 0 3px rgba(29, 78, 216, 0.15);
        }
        .input-group-text {
            background: #f8fafc;
            border: 1px solid #cbd5e1;
            color: #64748b;
            border-radius: 8px 0 0 8px;
        }
        .input-group .form-control {
            border-radius: 0 8px 8px 0;
        }
        .btn-login {
            background: #1d4ed8;
            color: #ffffff;
            font-weight: 600;
            border: none;
            border-radius: 8px;
            padding: 0.75rem;
            font-size: 0.95rem;
            transition: all 0.2s ease;
        }
        .btn-login:hover {
            background: #1e40af;
            color: #ffffff;
        }
        .quick-role-badge {
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            padding: 0.5rem 0.75rem;
            cursor: pointer;
            transition: all 0.15s ease;
            font-size: 0.82rem;
        }
        .quick-role-badge:hover {
            background: #f1f5f9;
            border-color: #cbd5e1;
        }
    </style>
</head>
<body>

    <div class="login-card">
        <div class="text-center">
            <div class="brand-icon">
                <i class="fa-solid fa-tower-broadcast"></i>
            </div>
            <h4 class="fw-bold mb-1 text-dark" style="font-family: 'Plus Jakarta Sans', sans-serif;">
                WebGIS Microwave
            </h4>
            <p class="text-secondary small mb-4">Sistem Pemetaan Jarak & Radius Tower Antena</p>
        </div>

        <?php if(session()->getFlashdata('error')): ?>
            <div class="alert alert-danger py-2 px-3 small d-flex align-items-center mb-3">
                <i class="fa-solid fa-triangle-exclamation me-2"></i>
                <div><?= session()->getFlashdata('error') ?></div>
            </div>
        <?php endif; ?>

        <?php if(session()->getFlashdata('pesan')): ?>
            <div class="alert alert-success py-2 px-3 small d-flex align-items-center mb-3">
                <i class="fa-solid fa-circle-check me-2"></i>
                <div><?= session()->getFlashdata('pesan') ?></div>
            </div>
        <?php endif; ?>

        <form method="post" action="<?= base_url('login') ?>">
            <?= csrf_field() ?>
            <div class="mb-3">
                <label class="form-label small fw-semibold text-dark">Username</label>
                <div class="input-group">
                    <span class="input-group-text"><i class="fa-solid fa-user"></i></span>
                    <input type="text" name="username" id="usernameInput" class="form-control" placeholder="Masukkan username" required autofocus value="<?= old('username') ?>">
                </div>
            </div>

            <div class="mb-4">
                <label class="form-label small fw-semibold text-dark">Password</label>
                <div class="input-group">
                    <span class="input-group-text"><i class="fa-solid fa-lock"></i></span>
                    <input type="password" name="password" id="passwordInput" class="form-control" placeholder="Masukkan password" required>
                </div>
            </div>

            <button type="submit" class="btn btn-login w-100 mb-3">
                <i class="fa-solid fa-right-to-bracket me-1"></i> Masuk ke Sistem
            </button>

            <!-- Quick Demo Accounts -->
            <div class="border-top pt-3">
                <div class="text-center text-muted small mb-2">Pilih Akun Demo Cepat:</div>
                <div class="d-flex gap-2">
                    <div class="quick-role-badge flex-fill text-center" onclick="fillAccount('admin', 'admin123')">
                        <i class="fa-solid fa-shield-halved text-primary me-1"></i>
                        <span class="fw-bold text-dark">Admin</span>
                        <div class="text-muted" style="font-size: 0.72rem;">Full CRUD & Peta</div>
                    </div>
                    <div class="quick-role-badge flex-fill text-center" onclick="fillAccount('user', 'user123')">
                        <i class="fa-solid fa-user text-success me-1"></i>
                        <span class="fw-bold text-dark">Operator</span>
                        <div class="text-muted" style="font-size: 0.72rem;">Peta & Analisis</div>
                    </div>
                </div>
                <div class="text-center mt-3">
                    <a href="<?= base_url('/') ?>" class="text-decoration-none small text-secondary">
                        <i class="fa-solid fa-arrow-left me-1"></i> Kembali ke Halaman Utama (Tamu)
                    </a>
                </div>
            </div>
        </form>
    </div>

    <script>
        function fillAccount(u, p) {
            document.getElementById('usernameInput').value = u;
            document.getElementById('passwordInput').value = p;
        }
    </script>
</body>
</html>
