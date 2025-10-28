<?= $this->extend('layout/admin_layout') ?>

<?= $this->section('title') ?>Gatepass Admin Dashboard<?= $this->endSection() ?>

<?= $this->section('content') ?>

    <div class="row mt-4">
        <div class="col-lg-12">
            <div class="card bg-light-info shadow-none position-relative overflow-hidden">
                <div class="card-body px-4 py-3">
                    <div class="row align-items-center">
                        <div class="col-9">
                            <h4 class="fw-semibold mb-8">Gatepass Management Dashboard</h4>
                            <nav aria-label="breadcrumb">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a class="text-muted" href="<?= site_url('/') ?>">Home</a></li>
                                    <li class="breadcrumb-item" aria-current="page">Dashboard</li>
                                </ol>
                            </nav>
                        </div>
                        <div class="col-3">
                            <div class="text-center mb-n5">
                                <img src="<?= base_url('assets/images/logos/logo.svg') ?>" alt="" class="img-fluid mb-n4">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row mb-4">

        <div class="col-sm-6 col-lg-4">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title fw-semibold mb-4">Real-Time Headcount Inside Campus</h5>
                    <p class="fs-4">580</p>
                </div>
            </div>
        </div>

        <div class="col-sm-6 col-lg-4">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title fw-semibold mb-4">Total Registered Items Inside Campus</h5>
                    <p class="fs-4">945</p>
                </div>
            </div>
        </div>

        <div class="col-sm-6 col-lg-4">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title fw-semibold mb-4">Total Passes Issued (All Time)</h5>
                    <p class="fs-4">12,500</p>
                </div>
            </div>
        </div>

    </div>

    <div class="row">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title fw-semibold mb-4">Pass Activity Log (Placeholder)</h5>
                    <p>Recent check-in and check-out data.</p>
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title fw-semibold mb-4">Total Registered Staff/Visitors (Placeholder)</h5>
                    <p>Count of authorized personnel.</p>
                </div>
            </div>
        </div>
    </div>

<?= $this->endSection() ?>