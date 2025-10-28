<?= $this->extend('layout/admin_layout') ?>

<?= $this->section('title') ?>Daily Gatepass Reports<?= $this->endSection() ?>

<?= $this->section('content') ?>

    <div class="row mt-4">
        <div class="col-lg-12">
            <div class="card bg-light-info shadow-none position-relative overflow-hidden">
                <div class="card-body px-4 py-3">
                    <div class="row align-items-center">
                        <div class="col-9">
                            <h4 class="fw-semibold mb-8">Daily Gatepass Reports</h4>
                            <nav aria-label="breadcrumb">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a class="text-muted" href="<?= site_url('/') ?>">Dashboard</a></li>
                                    <li class="breadcrumb-item" aria-current="page">Daily Reports</li>
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

    <div class="row">
        <div class="col-lg-12 mb-4">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title fw-semibold mb-4">Filter Report</h5>
                    <form class="row g-3">
                        <div class="col-md-3">
                            <label for="reportDate" class="form-label">Select Date</label>
                            <input type="date" class="form-control" id="reportDate" value="<?= date('Y-m-d') ?>">
                        </div>
                        <div class="col-md-3">
                            <label for="passType" class="form-label">Pass Type</label>
                            <select id="passType" class="form-select">
                                <option selected>All</option>
                                <option>Student Item</option>
                                <option>Visitor</option>
                            </select>
                        </div>
                        <div class="col-md-3 align-self-end">
                            <button type="submit" class="btn btn-primary">Generate Report</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-sm-6 col-lg-3">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title fw-semibold mb-2">Total Check-Ins</h5>
                    <p class="fs-6">Placeholder: 850</p>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-lg-3">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title fw-semibold mb-2">Total Items Checked Out</h5>
                    <p class="fs-6">Placeholder: 120</p>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-lg-3">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title fw-semibold mb-2">Pending Returns</h5>
                    <p class="fs-6">Placeholder: 5</p>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-lg-3">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title fw-semibold mb-2">Unique Users</h5>
                    <p class="fs-6">Placeholder: 780</p>
                </div>
            </div>
        </div>

        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title fw-semibold mb-4">Daily Pass Activity Table</h5>
                    <div class="table-responsive">
                        <table class="table text-nowrap mb-0 align-middle">
                            <thead class="text-dark fs-4">
                            <tr>
                                <th class="border-bottom-0"><h6 class="fw-semibold mb-0">Time</h6></th>
                                <th class="border-bottom-0"><h6 class="fw-semibold mb-0">Pass ID</h6></th>
                                <th class="border-bottom-0"><h6 class="fw-semibold mb-0">User</h6></th>
                                <th class="border-bottom-0"><h6 class="fw-semibold mb-0">Action</h6></th>
                                <th class="border-bottom-0"><h6 class="fw-semibold mb-0">Items</h6></th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr>
                                <td class="border-bottom-0"><h6 class="fw-semibold mb-0">08:00 AM</h6></td>
                                <td class="border-bottom-0"><p class="mb-0 fw-normal">G20250001</p></td>
                                <td class="border-bottom-0"><p class="mb-0 fw-normal">Jane Doe (Student)</p></td>
                                <td class="border-bottom-0"><div class="d-flex align-items-center gap-2"><span class="badge bg-primary rounded-3 fw-semibold">Check-In</span></div></td>
                                <td class="border-bottom-0"><p class="mb-0 fw-normal">Laptop, Book</p></td>
                            </tr>
                            <tr>
                                <td class="border-bottom-0"><h6 class="fw-semibold mb-0">08:30 AM</h6></td>
                                <td class="border-bottom-0"><p class="mb-0 fw-normal">G20250002</p></td>
                                <td class="border-bottom-0"><p class="mb-0 fw-normal">John Smith (Visitor)</p></td>
                                <td class="border-bottom-0"><div class="d-flex align-items-center gap-2"><span class="badge bg-danger rounded-3 fw-semibold">Check-Out</span></div></td>
                                <td class="border-bottom-0"><p class="mb-0 fw-normal">ID Pass Only</p></td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

<?= $this->endSection() ?>