<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password</title>
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
        }
    </style>
</head>
<body>

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-6 col-lg-5">
            <div class="card shadow border-0 rounded form-card">
                <div class="card-body p-4 p-md-5">
                    <div class="text-center mb-4">
                        <img src="<?= base_url('assets/images/logos/logo.svg') ?>" alt="Logo" width="180" class="mb-3">
                        <h4 class="mb-0">Forgot Your Password?</h4>
                        <!-- --- UPDATED Text --- -->
                        <p class="text-muted">Enter your school email to receive a reset code.</p>
                    </div>

                    <?php if (session()->getFlashdata('error')): ?>
                        <div class="alert alert-danger" role="alert">
                            <?= session()->getFlashdata('error') ?>
                        </div>
                    <?php endif; ?>
                    <?php if (session()->getFlashdata('message')): ?>
                        <div class="alert alert-info" role="alert">
                            <?= session()->getFlashdata('message') ?>
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


                    <form action="<?= base_url('student/password/forgot-process') ?>" method="post">
                        <?= csrf_field() ?>

                        <div class="mb-4">
                            <label for="school_email" class="form-label">School Email</label>
                            <input type="email"
                                   class="form-control"
                                   id="school_email"
                                   name="school_email"
                                   placeholder="e.g., john.doe@tup.edu.ph"
                                   required
                                   value="<?= old('school_email') ?>">
                        </div>

                        <!-- --- UPDATED Text --- -->
                        <button type="submit" class="btn btn-primary w-100 py-2 fs-5 mb-4 rounded">Send Reset Code</button>

                        <div class="d-flex align-items-center justify-content-center">
                            <a class="text-primary fw-bold" href="<?= base_url('student/login') ?>">Back to Login</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>