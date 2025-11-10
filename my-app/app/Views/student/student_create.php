<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Registration</title>
    <!-- Link to your compiled Bootstrap CSS (adjust path as needed) -->
    <link rel="stylesheet" href="<?= base_url('assets/css/styles.min.css') ?>">
    <style>
        body { display: flex; align-items: center; justify-content: center; min-height: 100vh; background-color: #f8f9fa; }
        .register-card { max-width: 400px; width: 100%; }
    </style>
</head>
<body>

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-6 col-lg-5">
            <div class="card shadow border-0 rounded register-card">
                <div class="card-body p-4 p-md-5">
                    <?php echo view('partials/flash_messages'); ?>
                    <div class="text-center mb-4">
                        <img src="<?= base_url('assets/images/logos/logo.svg') ?>" alt="Logo" width="180" class="mb-3">
                        <h4 class="mb-0">Create Student Account</h4>
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

                    <form action="<?= base_url('student/register/process') ?>" method="post" id="registerForm">
                        <?= csrf_field() ?>

                        <!-- --- NEW: Full Name --- -->
                        <div class="mb-3">
                            <label for="name" class="form-label">Full Name</label>
                            <input type="text"
                                   class="form-control"
                                   id="name"
                                   name="name"
                                   placeholder="Juan Dela Cruz"
                                   required
                                   autocomplete="name"
                                   value="<?= set_value('name') ?>">
                        </div>

                        <!-- School ID -->
                        <div class="mb-3">
                            <label for="school_id_suffix" class="form-label">School ID</label>
                            <div class="input-group">
                                <span class="input-group-text">TUPT-</span>
                                <input type="text"
                                       class="form-control"
                                       id="school_id_suffix"
                                       name="school_id_suffix"
                                       placeholder="XX-XXXX"
                                       required
                                       pattern="\d{2}-\d{4}"
                                       maxlength="7"
                                       title="Enter ID in XX-XXXX format"
                                       autocomplete="off"
                                       value="<?= set_value('school_id_suffix') ?>">
                            </div>
                            <input type="hidden" name="school_id" id="full_school_id">
                        </div>

                        <!-- --- NEW: School Email (Optional) --- -->
                        <div class="mb-3">
                            <label for="school_email" class="form-label">School Email (Optional)</label>
                            <input type="email"
                                   class="form-control"
                                   id="school_email"
                                   name="school_email"
                                   placeholder="tup-email@tup.edu.ph"
                                   autocomplete="email"
                                   value="<?= set_value('school_email') ?>">
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
                                <button class="btn btn-outline-secondary" type="button" id="togglePassword" style="border-left: 0; border-color: #ced4da;">
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
                                <button class="btn btn-outline-secondary" type="button" id="togglePasswordConfirm" style="border-left: 0; border-color: #ced4da;">
                                    <i class="ti ti-eye-off fs-5"></i>
                                </button>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-primary w-100 py-2 fs-5 mb-4 rounded">Register</button>
                        <div class="d-flex align-items-center justify-content-center">
                            <p class="fs-4 mb-0 fw-bold">Already have an Account?</p>
                            <a class="text-primary fw-bold ms-2" href="<?= base_url('student/login') ?>">Sign In</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script>
    // --- School ID Handling (Same as login page) ---
    const registerForm = document.getElementById('registerForm');
    const schoolIdSuffixInput = document.getElementById('school_id_suffix');
    const fullSchoolIdInput = document.getElementById('full_school_id');

    function updateFullSchoolId() {
        fullSchoolIdInput.value = 'TUPT-' + schoolIdSuffixInput.value;
    }

    schoolIdSuffixInput.addEventListener('input', (e) => {
        let value = e.target.value.replace(/[^0-9]/g, '');
        let formattedValue = '';
        if (value.length > 0) formattedValue += value.substring(0, 2);
        if (value.length > 2) formattedValue += '-' + value.substring(2, 6);
        e.target.value = formattedValue;
        updateFullSchoolId();
    });
    schoolIdSuffixInput.addEventListener('keydown', (e) => {
        const key = e.key;
        const value = e.target.value;
        const selectionStart = e.target.selectionStart;
        const selectionEnd = e.target.selectionEnd;
        if (['Backspace', 'Delete', 'ArrowLeft', 'ArrowRight', 'Tab', 'Home', 'End'].includes(key) || (e.ctrlKey && ['a', 'c', 'v', 'x'].includes(key.toLowerCase()))) return;
        if (!/^[0-9]$/.test(key)) e.preventDefault();
        if (value.length >= 7 && selectionStart === selectionEnd) e.preventDefault();
        if (key === '-') e.preventDefault();
    });

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

    registerForm.addEventListener('submit', (event) => {
        updateFullSchoolId();
    });

    updateFullSchoolId();
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
