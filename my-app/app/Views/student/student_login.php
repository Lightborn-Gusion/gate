<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Login</title>
    <!-- Link to your compiled Bootstrap CSS (adjust path as needed) -->
    <link rel="stylesheet" href="<?= base_url('assets/css/styles.min.css') ?>">
    <!-- Basic styling for centering -->
    <style>
        body {
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            background-color: #f8f9fa; /* Light background */
        }
        .login-card {
            max-width: 400px;
            width: 100%;
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
                        <!-- Placeholder for Logo -->
                        <img src="<?= base_url('assets/images/logos/logo.svg') ?>" alt="Logo" width="180" class="mb-3">
                        <h4 class="mb-0">Welcome Back, Student!</h4>
                        <p class="text-muted">Sign in to continue</p>
                    </div>

                    <!-- Display Validation Errors -->
                    <?php if (session()->getFlashdata('errors')): ?>
                        <div class="alert alert-danger" role="alert">
                            <?= session()->getFlashdata('errors') // Adjust if errors is an array ?>
                        </div>
                    <?php endif; ?>

                    <!-- Display Success/Error Messages -->
                    <?php if (session()->getFlashdata('message')): ?>
                        <div class="alert alert-info" role="alert">
                            <?= session()->getFlashdata('message') ?>
                        </div>
                    <?php endif; ?>

                    <form action="<?= base_url('student/login/process') ?>" method="post" id="loginForm">
                        <?= csrf_field() ?>

                        <div class="mb-3"> <!-- Changed margin bottom -->
                            <label for="school_id_suffix" class="form-label">School ID</label>
                            <div class="input-group">
                                <span class="input-group-text" id="school_id_prefix">TUPT-</span>
                                <input type="text"
                                       class="form-control"
                                       id="school_id_suffix"
                                       name="school_id_suffix"
                                       placeholder="XX-XXXX"
                                       aria-describedby="school_id_prefix"
                                       required
                                       pattern="\d{2}-\d{4}"
                                       maxlength="7"
                                       title="Enter your ID in XX-XXXX format (e.g., 21-1234)"
                                       autocomplete="new-password"> <!-- Changed to new-password -->
                            </div>
                            <!-- Hidden input to store the full ID -->
                            <input type="hidden" name="school_id" id="full_school_id">
                        </div>

                        <!-- Password input group - MOVED OUTSIDE the School ID div -->
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
                                <button class="btn btn-outline-secondary" type="button" id="togglePassword" style="border-left: 0; border-color: #ced4da;">
                                    <i class="ti ti-eye-off fs-5"></i> <!-- Tabler Icon -->
                                </button>
                            </div>
                        </div>

                        <div class="d-flex align-items-center justify-content-between mb-4">
                            <div class="form-check">
                                <!-- Basic Remember Me - Needs backend logic -->
                                <!-- <input class="form-check-input primary" type="checkbox" value="" id="rememberMe" name="rememberMe">
                                <label class="form-check-label text-dark" for="rememberMe">
                                    Remember me
                                </label> -->
                            </div>
                            <a class="text-primary fw-bold" href="<?= base_url('student/forgot-password') ?>">Forgot Password ?</a>
                        </div>
                        <button type="submit" class="btn btn-primary w-100 py-2 fs-5 mb-4 rounded">Sign In</button>
                        <div class="d-flex align-items-center justify-content-center">
                            <p class="fs-4 mb-0 fw-bold">New here?</p>
                            <a class="text-primary fw-bold ms-2" href="<?= base_url('student/register') ?>">Create an account</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Link to your JS (optional, if you have separate files) -->
<!-- <script src="<?= base_url('assets/js/app.min.js') ?>"></script> -->
<!-- Bootstrap JS Bundle -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

<script>
    const loginForm = document.getElementById('loginForm');
    const schoolIdSuffixInput = document.getElementById('school_id_suffix');
    const fullSchoolIdInput = document.getElementById('full_school_id');
    const passwordInput = document.getElementById('password'); // Get password input
    const togglePasswordButton = document.getElementById('togglePassword'); // Get toggle button
    const togglePasswordIcon = togglePasswordButton.querySelector('i'); // Get icon inside button

    // Function to combine prefix and suffix
    function updateFullSchoolId() {
        fullSchoolIdInput.value = 'TUPT-' + schoolIdSuffixInput.value;
    }

    // --- School ID Formatting/Masking ---
    schoolIdSuffixInput.addEventListener('input', (e) => {
        let value = e.target.value.replace(/[^0-9]/g, ''); // Remove non-digits
        let formattedValue = '';

        if (value.length > 0) {
            formattedValue += value.substring(0, 2);
        }
        if (value.length > 2) {
            formattedValue += '-' + value.substring(2, 6); // Add hyphen and next 4 digits
        }

        e.target.value = formattedValue;
        updateFullSchoolId(); // Update hidden field on input
    });

    schoolIdSuffixInput.addEventListener('keydown', (e) => {
        const key = e.key;
        const value = e.target.value;
        const selectionStart = e.target.selectionStart;
        const selectionEnd = e.target.selectionEnd;

        // Allow backspace, delete, arrows, tab, home, end, ctrl+a/c/v/x
        if (['Backspace', 'Delete', 'ArrowLeft', 'ArrowRight', 'Tab', 'Home', 'End'].includes(key) ||
            (e.ctrlKey && ['a', 'c', 'v', 'x'].includes(key.toLowerCase()))) {
            return; // Allow these keys
        }

        // Allow numbers only
        if (!/^[0-9]$/.test(key)) {
            e.preventDefault(); // Prevent non-numeric keys
            return;
        }

        // Prevent typing more than allowed format length (XX-XXXX -> 7 chars)
        // unless tpext is selected
        if (value.length >= 7 && selectionStart === selectionEnd) {
            e.preventDefault();
            return;
        }

        // Prevent typing hyphen manually (script adds it)
        if (key === '-') {
            e.preventDefault();
            return;
        }

    });

    // --- Toggle Password Visibility ---
    togglePasswordButton.addEventListener('click', function () {
        // Toggle the type attribute
        const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
        passwordInput.setAttribute('type', type);

        // Toggle the icon
        if (type === 'password') {
            togglePasswordIcon.classList.remove('ti-eye');
            togglePasswordIcon.classList.add('ti-eye-off');
        } else {
            togglePasswordIcon.classList.remove('ti-eye-off');
            togglePasswordIcon.classList.add('ti-eye');
        }
    });


    // Update hidden field before submitting (just in case)
    loginForm.addEventListener('submit', (event) => {
        updateFullSchoolId();
    });

    // Initial update in case the field is autofilled on load (might be needed)
    updateFullSchoolId();

</script>

</body>
</html>

