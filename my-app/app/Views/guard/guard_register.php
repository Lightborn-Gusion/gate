<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Guard Registration</title>
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
        .register-card {
            max-width: 500px; /* Wider card for more fields */
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
        <div class="col-md-8 col-lg-6">
            <div class="card shadow border-0 rounded register-card">
                <div class="card-body p-4 p-md-5">
                    <div class="text-center mb-4">
                        <img src="<?= base_url('assets/images/logos/logo.svg') ?>" alt="Logo" width="180" class="mb-3">
                        <h4 class="mb-0">Create Guard Account</h4>
                        <p class="text-muted">Fill in the details below</p>
                    </div>

                    <!-- Display Validation Errors -->
                    <?php if (session()->getFlashdata('validation')): ?>
                        <div class="alert alert-danger" role="alert">
                            <?= session()->getFlashdata('validation')->listErrors() ?>
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

                    <form action="<?= route_to('guard_register_process') ?>" method="post" id="registerForm">
                        <?= csrf_field() ?>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="firstname" class="form-label">First Name</label>
                                <input type="text" class="form-control" id="firstname" name="firstname" value="<?= set_value('firstname') ?>" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="surname" class="form-label">Surname</label>
                                <input type="text" class="form-control" id="surname" name="surname" value="<?= set_value('surname') ?>" required>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="middlename" class="form-label">Middle Name / Initial (Optional)</label>
                            <input type="text" class="form-control" id="middlename" name="middlename" value="<?= set_value('middlename') ?>">
                        </div>

                        <div class="mb-3">
                            <label for="badge_number" class="form-label">Guard ID</label>
                            <input type="text" class="form-control" id="badge_number" name="badge_number" value="<?= set_value('badge_number') ?>" placeholder="This will be your login ID" required>
                        </div>

                        <!-- Password -->
                        <div class="mb-3">
                            <label for="password" class="form-label">Password</label>
                            <div class="input-group">
                                <input type="password"
                                       class="form-control"
                                       id="password"
                                       name="password"
                                       required
                                       minlength="4">
                                <button class="btn btn-outline-secondary btn-toggle-password" type="button" id="togglePassword">
                                    <i class="ti ti-eye-off fs-5"></i>
                                </button>
                            </div>
                        </div>

                        <!-- Confirm Password -->
                        <div class="mb-4">
                            <label for="password_confirm" class="form-label">Confirm Password</label>
                            <div class="input-group">
                                <input type="password"
                                       class="form-control"
                                       id="password_confirm"
                                       name="password_confirm"
                                       required
                                       minlength="4">
                                <button class="btn btn-outline-secondary btn-toggle-password" type="button" id="togglePasswordConfirm">
                                    <i class="ti ti-eye-off fs-5"></i>
                                </button>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-primary w-100 py-2 fs-5 mb-4 rounded">Register</button>
                        <div class="d-flex align-items-center justify-content-center">
                            <p class="fs-4 mb-0 fw-bold">Already have an Account?</p>
                            <a class="text-primary fw-bold ms-2" href="<?= route_to('guard_login') ?>">Sign In</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="<?= base_url('assets/libs/bootstrap/dist/js/bootstrap.bundle.min.js') ?>"></script>
<script>
    // --- Password Toggle ---
    function setupPasswordToggle(inputId, buttonId) {
        const passwordInput = document.getElementById(inputId);
        if (!passwordInput) return;
        const toggleButton = document.getElementById(buttonId);
        if (!toggleButton) return;
        const icon = toggleButton.querySelector('i');
        if (!icon) return;

        toggleButton.addEventListener('click', function () {
            const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
            passwordInput.setAttribute('type', type);
            icon.className = type === 'password' ? 'ti ti-eye-off fs-5' : 'ti ti-eye fs-5';
        });
    }
    setupPasswordToggle('password', 'togglePassword');
    setupPasswordToggle('password_confirm', 'togglePasswordConfirm');
</script>
</body>
</html>

