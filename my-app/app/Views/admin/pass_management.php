<?= $this->extend('layout/admin_layout') ?>

<?= $this->section('title') ?>Visitor Pass Management<?= $this->endSection() ?>

<?= $this->section('content') ?>

    <div class="row mt-4">
        <div class="col-lg-12">
            <div class="card bg-light-info shadow-none position-relative overflow-hidden">
                <div class="card-body px-4 py-3">
                    <div class="row align-items-center">
                        <div class="col-9">
                            <h4 class="fw-semibold mb-8">Visitor Pass Management</h4>
                            <nav aria-label="breadcrumb">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a class="text-muted" href="<?= site_url('/') ?>">Dashboard</a></li>
                                    <li class="breadcrumb-item" aria-current="page">Pass Management</li>
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
        <div class="col-lg-5 mb-4">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title fw-semibold mb-4">Issue New Visitor Pass</h5>
                    <form>
                        <div class="mb-3">
                            <label for="visitorName" class="form-label">Visitor Name</label>
                            <input type="text" class="form-control" id="visitorName" required>
                        </div>
                        <div class="mb-3">
                            <label for="visitPurpose" class="form-label">Purpose of Visit</label>
                            <textarea class="form-control" id="visitPurpose" rows="2" required></textarea>
                        </div>
                        <div class="mb-3">
                            <label for="contactPerson" class="form-label">Contact Person (Host)</label>
                            <input type="text" class="form-control" id="contactPerson">
                        </div>
                        <div class="mb-3">
                            <label for="expiryTime" class="form-label">Pass Expiry Time</label>
                            <input type="datetime-local" class="form-control" id="expiryTime">
                        </div>
                        <button type="submit" class="btn btn-primary w-100">Issue Pass & Print</button>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-lg-7 mb-4">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title fw-semibold mb-4">Active & Pending Visitor Passes</h5>
                    <div class="table-responsive">
                        <table class="table text-nowrap mb-0 align-middle">
                            <thead class="text-dark fs-4">
                            <tr>
                                <th class="border-bottom-0"><h6 class="fw-semibold mb-0">ID</h6></th>
                                <th class="border-bottom-0"><h6 class="fw-semibold mb-0">Visitor</h6></th>
                                <th class="border-bottom-0"><h6 class="fw-semibold mb-0">Host</h6></th>
                                <th class="border-bottom-0"><h6 class="fw-semibold mb-0">Status</h6></th>
                                <th class="border-bottom-0"><h6 class="fw-semibold mb-0">Action</h6></th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr>
                                <td class="border-bottom-0"><h6 class="fw-semibold mb-0">V250001</h6></td>
                                <td class="border-bottom-0"><p class="mb-0 fw-normal">Alice Johnson</p></td>
                                <td class="border-bottom-0"><p class="mb-0 fw-normal">Dr. B. Cruz</p></td>
                                <td class="border-bottom-0"><div class="d-flex align-items-center gap-2"><span class="badge bg-success rounded-3 fw-semibold">CHECKED-IN</span></div></td>
                                <td class="border-bottom-0"><a href="#" class="btn btn-sm btn-danger">Check-Out</a></td>
                            </tr>
                            <tr>
                                <td class="border-bottom-0"><h6 class="fw-semibold mb-0">V250002</h6></td>
                                <td class="border-bottom-0"><p class="mb-0 fw-normal">Robert Garcia</p></td>
                                <td class="border-bottom-0"><p class="mb-0 fw-normal">Ms. D. Reyes</p></td>
                                <td class="border-bottom-0"><div class="d-flex align-items-center gap-2"><span class="badge bg-warning rounded-3 fw-semibold">PENDING</span></div></td>
                                <td class="border-bottom-0"><a href="#" class="btn btn-sm btn-primary">Approve</a></td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

<?= $this->endSection() ?>