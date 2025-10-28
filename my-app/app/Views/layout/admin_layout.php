<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title><?= $this->renderSection('title') ?? 'Gate Project Admin' ?></title>

    <link rel="shortcut icon" type="image/png" href="<?= base_url('assets/images/logos/favicon.png') ?>" />
    <link rel="stylesheet" href="<?= base_url('assets/css/styles.min.css') ?>" />
    <link rel="stylesheet" href="<?= base_url('assets/css/custom-styles.css') ?>" />

    <link rel="stylesheet" href="<?= base_url('assets/css/admin.css') ?>" />

    <?= $this->renderSection('styles') ?>
</head>

<body>

<div class="fixed-top-banner"><div class="py-2 px-6 text-center"></div></div>

<div class="page-wrapper" id="main-wrapper" data-layout="vertical" data-navbarbg="skin6" data-sidebartype="full"
     data-sidebar-position="fixed" data-header-position="fixed">

    <?= $this->include('partials/admin_sidebar') ?>

    <div class="body-wrapper">

        <?= $this->include('partials/admin_header') ?>

        <div class="container-fluid">

            <?= $this->renderSection('content') ?>

            <div class="py-6 px-6 text-center">
                <p class="mb-0 fs-4">Design and Developed by Theme</p>
            </div>
        </div>
    </div>
</div>
<script>
    const BASE_URL = '<?= base_url() ?>';
</script>

<script src="<?= base_url('assets/libs/jquery/dist/jquery.min.js') ?>"></script>
<script src="<?= base_url('assets/libs/bootstrap/dist/js/bootstrap.bundle.min.js') ?>"></script>

<script src="<?= base_url('assets/js/sidebarmenu.js') ?>"></script>
<script src="<?= base_url('assets/js/app.min.js') ?>"></script>
<script src="<?= base_url('assets/libs/apexcharts/dist/apexcharts.min.js') ?>"></script>
<script src="<?= base_url('assets/js/dashboard.js') ?>"></script>

<?= $this->renderSection('scripts') ?>
</body>
</html>