<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Guard Login</title>
    <!-- Link to your compiled Bootstrap CSS (adjust path as needed) -->
    <link rel="stylesheet" href="<?= base_url('assets/css/styles.min.css') ?>">
    <link rel="stylesheet" href="<?= base_url('assets/css/icons/tabler-icons/tabler-icons.css') ?>">
    <style>
        body {
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            background-color: #f8f9fa;
        }
        .login-card {
            max-width: 400px;
            width: 100%;
        }
        .btn-toggle-password {
            border-left: 0;
            border-color: #ced4da;
        }
    </style>
</head>
<body>

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-6 col-lg-5">
            <div class="card shadow border-0 rounded login-card">
                <div class="card-body p-4 p-md-5">
                    <div class="text-center mb-4">
                        <img src="<?= base_url('assets/images/logos/logo.svg') ?>" alt="Logo" width="180" class="mb-3">
                        <h4 class="mb-0">Guard Portal</h4>
                        <p class="text-muted">Sign in to continue</p>
                    </div>

                    <!-- Display Validation Errors -->
                    <?php if (session()->getFlashdata('errors')): ?>
                        <div class="alert alert-danger" role="alert">
                            <?= implode('<br>', session()->getFlashdata('errors')) ?>
                        </div>
                    <?php endif; ?>

                    <!-- Display Success/Error Messages -->
                    <?php if (session()->getFlashdata('message')): ?>
                        <div class="alert alert-info" role="alert">
                            <?= session()->getFlashdata('message') ?>
                        </div>
                    <?php endif; ?>
                    <?php if (session()->getFlashdata('error')): ?>
                        <div class="alert alert-danger" role="alert">
                            <?= session()->getFlashdata('error') ?>
                        </div>
                    <?php endif; ?>

                    <form action="<?= route_to('guard_login_process') ?>" method="post" id="loginForm">
                        <?= csrf_field() ?>

                        <!-- --- UPDATED --- -->
                        <div class="mb-3">
                            <label for="badge_number" class="form-label">Guard ID</label>
                            <input type="text"
                                   class="form-control"
                                   id="badge_number"
                                   name="badge_number"
                                   placeholder="Enter your Guard ID"
                                   required
                                   autocomplete="off"
                                   value="<?= set_value('badge_number') ?>">
                        </div>
                        <!-- --- END UPDATE --- -->

                        <div class="mb-4">
                            <label for="password" class="form-label">Password</label>
                            <div class="input-group">
                                <input type="password"
                                       class="form-control"
                                       id="password"
                                       name="password"
                                       required
                                       minlength="4"
                                       autocomplete="current-password">
                                <button class="btn btn-outline-secondary btn-toggle-password" type="button" id="togglePassword">
                                    <i class="ti ti-eye-off fs-5"></i>
                                </button>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-primary w-100 py-2 fs-5 mb-4 rounded">Sign In</button>

                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="<?= base_url('assets/libs/bootstrap/dist/js/bootstrap.bundle.min.js') ?>"></script>
<script>
    // --- Toggle Password Visibility ---
    const passwordInput = document.getElementById('password');
    const togglePasswordButton = document.getElementById('togglePassword');

    if (togglePasswordButton) {
        const togglePasswordIcon = togglePasswordButton.querySelector('i');

        togglePasswordButton.addEventListener('click', function () {
            const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
            passwordInput.setAttribute('type', type);

            if (type === 'password') {
                togglePasswordIcon.className = 'ti ti-eye-off fs-5';
            } else {
                togglePasswordIcon.className = 'ti ti-eye fs-5';
            }
        });
    }
</script>

</body>
</html>

