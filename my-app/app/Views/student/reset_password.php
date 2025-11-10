<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password</title>
    <!-- We need Tabler icons for the "eye" button -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@latest/tabler-icons.min.css">
    <link rel="stylesheet" href="<?= base_url('assets/css/styles.min.css') ?>">
    <style>
        body {
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            background-color: #f8f9fa;
        }
        .form-card {
            max-width: 400px;
            width: 100%;
        }
        /* Style for the show/hide password button */
        .password-toggle-btn {
            border-left: 0;
            border-color: #ced4da;
            background-color: white; /* Match form input */
        }
    </style>
</head>
<body>

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-6 col-lg-5">
            <div class="card shadow border-0 rounded form-card">
                <div class="card-body p-4 p-md-5">
                    <?php echo view('partials/flash_messages'); ?>
                    <div class="text-center mb-4">
                        <img src="<?= base_url('assets/images/logos/logo.svg') ?>" alt="Logo" width="180" class="mb-3">
                        <h4 class="mb-0">Set a New Password</h4>
                        <!-- --- UPDATED Text --- -->
                        <p class="text-muted">Please enter the code from your email and your new password.</p>
                    </div>

                    <!-- Display Messages -->
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

                    <?php if (session()->getFlashdata('errors')): ?>
                        <div class="alert alert-danger" role="alert">
                            <?php if (is_array(session()->getFlashdata('errors'))): ?>
                                <ul>
                                    <?php foreach (session()->getFlashdata('errors') as $error): ?>
                                        <li><?= esc($error) ?></li>
                                    <?php endforeach ?>
                                </ul>
                            <?php else: ?>
                                <?= session()->getFlashdata('errors') ?>
                            <?php endif; ?>
                        </div>
                    <?php endif; ?>

                    <form action="<?= base_url('student/password/reset-process') ?>" method="post">
                        <?= csrf_field() ?>

                        <!-- --- UPDATED: Changed from hidden to text input --- -->
                        <div class="mb-3">
                            <label for="token" class="form-label">Reset Code</label>
                            <input type="text"
                                   class="form-control"
                                   id="token"
                                   name="token"
                                   placeholder="Enter the code from your email"
                                   required
                                   value="<?= old('token') ?>">
                        </div>

                        <!-- --- UPDATED: Added show/hide button --- -->
                        <div class="mb-3">
                            <label for="password" class="form-label">New Password</label>
                            <div class="input-group">
                                <input type="password"
                                       class="form-control"
                                       id="password"
                                       name="password"
                                       required
                                       minlength="4"> <!-- --- UPDATED minlength --- -->
                                <button class="btn btn-outline-secondary password-toggle-btn" type="button" id="togglePassword">
                                    <i class="ti ti-eye-off"></i>
                                </button>
                            </div>
                        </div>

                        <!-- --- UPDATED: Added show/hide button --- -->
                        <div class="mb-4">
                            <label for="password_confirm" class="form-label">Confirm New Password</label>
                            <div class="input-group">
                                <input type="password"
                                       class="form-control"
                                       id="password_confirm"
                                       name="password_confirm"
                                       required
                                       minlength="4"> <!-- --- UPDATED minlength --- -->
                                <button class="btn btn-outline-secondary password-toggle-btn" type="button" id="togglePasswordConfirm">
                                    <i class="ti ti-eye-off"></i>
                                </button>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-primary w-100 py-2 fs-5 mb-4 rounded">Reset Password</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

<!-- --- NEW: JavaScript for Show/Hide Password --- -->
<script>
    function setupPasswordToggle(inputId, buttonId) {
        const passwordInput = document.getElementById(inputId);
        const toggleButton = document.getElementById(buttonId);
        const toggleIcon = toggleButton.querySelector('i');

        if (toggleButton) {
            toggleButton.addEventListener('click', function () {
                // Toggle the type attribute
                const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
                passwordInput.setAttribute('type', type);

                // Toggle the icon
                if (type === 'password') {
                    toggleIcon.classList.remove('ti-eye');
                    toggleIcon.classList.add('ti-eye-off');
                } else {
                    toggleIcon.classList.remove('ti-eye-off');
                    toggleIcon.classList.add('ti-eye');
                }
            });
        }
    }

    // Setup for the first password field
    setupPasswordToggle('password', 'togglePassword');
    // Setup for the password confirmation field
    setupPasswordToggle('password_confirm', 'togglePasswordConfirm');
    // Wait for the document to be fully loaded
    document.addEventListener('DOMContentLoaded', function() {
        // Set a timeout to run after 5 seconds
        window.setTimeout(function() {
            // Find all alerts that can be dismissed
            var allAlerts = document.querySelectorAll('.alert-dismissible');

            allAlerts.forEach(function(alert) {
                // Use Bootstrap's Alert instance to close it
                new bootstrap.Alert(alert).close();
            });
        }, 5000); // 5000 milliseconds = 5 seconds
    });
</script>

</body>
</html>