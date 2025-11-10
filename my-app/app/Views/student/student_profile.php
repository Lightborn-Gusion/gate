<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Profile</title>
    <!-- Link to your compiled Bootstrap CSS & Icons (adjust path as needed) -->
    <link rel="stylesheet" href="<?= base_url('assets/css/styles.min.css') ?>">
    <link rel="stylesheet" href="<?= base_url('assets/css/icons/tabler-icons/tabler-icons.css') ?>">
    <style>
        body {
            display: flex;
            flex-direction: column;
            min-height: 100vh;
            padding-top: 56px; /* Header height */
            background-color: #f8f9fa;
        }
        .main-content {
            flex-grow: 1;
        }
        .profile-card {
            box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
            border: none;
        }
        /* Style for read-only fields */
        .form-control[readonly] {
            background-color: #e9ecef;
            opacity: 1;
        }
        /* --- NEW: Style for password toggle button --- */
        .btn-toggle-password {
            border-left: 0;
            border-color: #ced4da;
        }
    </style>
</head>
<body>
<!-- Load CodeIgniter helper for form functions -->
<?php helper('form'); ?>

<!-- Header (Fixed Top) - Simplified for Profile Page -->
<header class="navbar navbar-expand navbar-light bg-white fixed-top shadow-sm">
    <div class="container-fluid">
        <?php echo view('partials/flash_messages'); ?>
        <!-- Back Button -->
        <a class="btn btn-link p-1 me-1" href="<?= route_to('student_dashboard') ?>">
            <i class="ti ti-arrow-left fs-2"></i>
        </a>

        <!-- Logo -->
        <a class="navbar-brand" href="<?= base_url('student/dashboard') ?>">
            <img src="<?= base_url('assets/images/logos/logo.svg') ?>" alt="Logo" height="30">
        </a>

        <!-- Centered Title -->
        <div class="header-title mx-auto">
            MY PROFILE
        </div>

        <!-- Right side placeholder to balance title -->
        <div style="width: 50px;"></div>
    </div>
</header>

<!-- Main Content Area -->
<main class="main-content container mt-4 mb-4">

    <!-- Card 1: Update Profile Details -->
    <div class="card profile-card mb-4">
        <div class="card-body p-4">
            <h5 class="card-title">Profile Details</h5>
            <p class="card-subtitle mb-3 text-muted">Update your personal information.</p>

            <div id="detailsFormMessage" class="mt-3"></div>

            <?= form_open(route_to('student_profile_update_details'), ['id' => 'updateDetailsForm']) ?>
            <?= csrf_field() ?>

            <div class="mb-3">
                <label for="school_id" class="form-label">School ID</label>
                <input type="text" class="form-control" id="school_id" value="<?= esc($student->school_id) ?>" readonly>
            </div>
            <div class="mb-3">
                <label for="name" class="form-label">Full Name</label>
                <input type="text" class="form-control" id="name" name="name" value="<?= esc($student->name) ?>" required>
            </div>
            <div class="mb-3">
                <label for="school_email" class="form-label">School Email (Optional)</label>
                <input type="email" class="form-control" id="school_email" name="school_email" value="<?= esc($student->school_email) ?>" placeholder="e.g., your-email@tup.edu.ph">
            </div>

            <div class="d-grid">
                <button type="submit" class="btn btn-primary" id="updateDetailsSubmitBtn">
                    <span class="spinner-border spinner-border-sm d-none" role="status" aria-hidden="true"></span>
                    Save Changes
                </button>
            </div>
            <?= form_close() ?>
        </div>
    </div>

    <!-- Card 2: Change Password -->
    <div class="card profile-card">
        <div class="card-body p-4">
            <h5 class="card-title">Change Password</h5>
            <p class="card-subtitle mb-3 text-muted">Update your account password.</p>

            <div id="passwordFormMessage" class="mt-3"></div>

            <?= form_open(route_to('student_profile_update_password'), ['id' => 'updatePasswordForm']) ?>
            <?= csrf_field() ?>

            <!-- --- UPDATED: Current Password --- -->
            <div class="mb-3">
                <label for="current_password" class="form-label">Current Password</label>
                <div class="input-group">
                    <input type="password" class="form-control" id="current_password" name="current_password" required>
                    <button class="btn btn-outline-secondary btn-toggle-password" type="button" id="toggleCurrentPassword">
                        <i class="ti ti-eye-off fs-5"></i>
                    </button>
                </div>
            </div>
            <!-- --- UPDATED: New Password --- -->
            <div class="mb-3">
                <label for="new_password" class="form-label">New Password</label>
                <div class="input-group">
                    <input type="password" class="form-control" id="new_password" name="new_password" required>
                    <button class="btn btn-outline-secondary btn-toggle-password" type="button" id="toggleNewPassword">
                        <i class="ti ti-eye-off fs-5"></i>
                    </button>
                </div>
            </div>
            <!-- --- UPDATED: Confirm New Password --- -->
            <div class="mb-3">
                <label for="confirm_new_password" class="form-label">Confirm New Password</label>
                <div class="input-group">
                    <input type="password" class="form-control" id="confirm_new_password" name="confirm_new_password" required>
                    <button class="btn btn-outline-secondary btn-toggle-password" type="button" id="toggleConfirmNewPassword">
                        <i class="ti ti-eye-off fs-5"></i>
                    </button>
                </div>
            </div>

            <div class="d-grid">
                <button type="submit" class="btn btn-primary" id="updatePasswordSubmitBtn">
                    <span class="spinner-border spinner-border-sm d-none" role="status" aria-hidden="true"></span>
                    Change Password
                </button>
            </div>
            <?= form_close() ?>
        </div>
    </div>

</main>

<!-- Bootstrap JS Bundle -->
<script src="<?= base_url('assets/libs/bootstrap/dist/js/bootstrap.bundle.min.js') ?>"></script>

<!-- AJAX JavaScript for both forms -->
<script>
    document.addEventListener('DOMContentLoaded', function () {

        // --- NEW: Password Toggle Helper Function ---
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

        // --- NEW: Call the toggle function for all 3 fields ---
        setupPasswordToggle('current_password', 'toggleCurrentPassword');
        setupPasswordToggle('new_password', 'toggleNewPassword');
        setupPasswordToggle('confirm_new_password', 'toggleConfirmNewPassword');


        // --- Get CSRF Token Name and Hash from PHP ---
        const csrfTokenName = "<?= config('Security')->tokenName ?>";
        let csrfInputDetails = document.querySelector('#updateDetailsForm input[name="' + csrfTokenName + '"]');
        let csrfInputPassword = document.querySelector('#updatePasswordForm input[name="' + csrfTokenName + '"]');

        // Function to update all CSRF tokens on the page
        function updateCsrfTokens(newHash) {
            if (newHash) {
                if (csrfInputDetails) csrfInputDetails.value = newHash;
                if (csrfInputPassword) csrfInputPassword.value = newHash;
            }
        }

        // --- Handle "Update Details" Form ---
        const detailsForm = document.getElementById('updateDetailsForm');
        const detailsSubmitBtn = document.getElementById('updateDetailsSubmitBtn');
        const detailsMsgDiv = document.getElementById('detailsFormMessage');

        if (detailsForm) {
            detailsForm.addEventListener('submit', async function(e) {
                e.preventDefault();
                const btnSpinner = detailsSubmitBtn.querySelector('.spinner-border');
                detailsSubmitBtn.disabled = true;
                btnSpinner.classList.remove('d-none');
                detailsMsgDiv.innerHTML = '';

                const formData = new FormData(detailsForm);

                try {
                    const response = await fetch(detailsForm.action, {
                        method: 'POST',
                        body: formData,
                        headers: { 'X-Requested-With': 'XMLHttpRequest' }
                    });

                    updateCsrfTokens(response.headers.get('X-CSRF-TOKEN'));
                    const result = await response.json();

                    if (response.ok) {
                        detailsMsgDiv.innerHTML = `<div class="alert alert-success">${result.message}</div>`;
                    } else {
                        let errorHtml = '<strong>Error:</strong><ul>';
                        for (const key in result.errors) {
                            errorHtml += `<li>${result.errors[key]}</li>`;
                        }
                        errorHtml += '</ul>';
                        detailsMsgDiv.innerHTML = `<div class="alert alert-danger">${errorHtml}</div>`;
                    }
                } catch (error) {
                    detailsMsgDiv.innerHTML = `<div class="alert alert-danger">An unexpected network error occurred.</div>`;
                } finally {
                    detailsSubmitBtn.disabled = false;
                    btnSpinner.classList.add('d-none');
                }
            });
        }

        // --- Handle "Update Password" Form ---
        const passwordForm = document.getElementById('updatePasswordForm');
        const passwordSubmitBtn = document.getElementById('updatePasswordSubmitBtn');
        const passwordMsgDiv = document.getElementById('passwordFormMessage');

        if (passwordForm) {
            passwordForm.addEventListener('submit', async function(e) {
                e.preventDefault();
                const btnSpinner = passwordSubmitBtn.querySelector('.spinner-border');
                passwordSubmitBtn.disabled = true;
                btnSpinner.classList.remove('d-none');
                passwordMsgDiv.innerHTML = '';

                const formData = new FormData(passwordForm);

                try {
                    const response = await fetch(passwordForm.action, {
                        method: 'POST',
                        body: formData,
                        headers: { 'X-Requested-With': 'XMLHttpRequest' }
                    });

                    updateCsrfTokens(response.headers.get('X-CSRF-TOKEN'));
                    const result = await response.json();

                    if (response.ok) {
                        passwordMsgDiv.innerHTML = `<div class="alert alert-success">${result.message}</div>`;
                        passwordForm.reset(); // Clear the form on success
                    } else {
                        let errorHtml = '<strong>Error:</strong><ul>';
                        for (const key in result.errors) {
                            errorHtml += `<li>${result.errors[key]}</li>`;
                        }
                        errorHtml += '</ul>';
                        passwordMsgDiv.innerHTML = `<div class="alert alert-danger">${errorHtml}</div>`;
                    }
                } catch (error) {
                    passwordMsgDiv.innerHTML = `<div class="alert alert-danger">An unexpected network error occurred.</div>`;
                } finally {
                    passwordSubmitBtn.disabled = false;
                    btnSpinner.classList.add('d-none');
                }
            });
        }
    });
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

