<?= $this->extend('layout/admin_layout') ?>

<?= $this->section('title') ?>Item Blacklist Management<?= $this->endSection() ?>

<?= $this->section('content') ?>

    <div class="row mt-4">
        <div class="col-lg-12">
            <div class="card bg-light-info shadow-none position-relative overflow-hidden">
                <div class="card-body px-4 py-3">
                    <div class="row align-items-center">
                        <div class="col-9">
                            <h4 class="fw-semibold mb-8">Item Blacklist Management</h4>
                            <nav aria-label="breadcrumb">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a class="text-muted" href="<?= site_url('/') ?>">Dashboard</a></li>
                                    <li class="breadcrumb-item" aria-current="page">Blacklist</li>
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
        <div class="col-lg-4 mb-4">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title fw-semibold mb-4">Add Item or User to Blacklist</h5>
                    <form>
                        <div class="mb-3">
                            <label for="blacklistType" class="form-label">Blacklist Type</label>
                            <select class="form-select" id="blacklistType">
                                <option selected>Item Name/Type</option>
                                <option>User ID/Badge</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="itemIdentifier" class="form-label">Identifier (e.g., Drone, Student-001)</label>
                            <input type="text" class="form-control" id="itemIdentifier" required>
                        </div>
                        <div class="mb-3">
                            <label for="reason" class="form-label">Reason for Blacklisting</label>
                            <textarea class="form-control" id="reason" rows="3" required></textarea>
                        </div>
                        <button type="submit" class="btn btn-danger w-100">Add to Blacklist</button>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-lg-8 mb-4">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title fw-semibold mb-4">Current Blacklisted Entries</h5>
                    <div class="table-responsive">
                        <table class="table text-nowrap mb-0 align-middle">
                            <thead class="text-dark fs-4">
                            <tr>
                                <th class="border-bottom-0"><h6 class="fw-semibold mb-0">Identifier</h6></th>
                                <th class="border-bottom-0"><h6 class="fw-semibold mb-0">Type</h6></th>
                                <th class="border-bottom-0"><h6 class="fw-semibold mb-0">Reason</h6></th>
                                <th class="border-bottom-0"><h6 class="fw-semibold mb-0">Date Added</h6></th>
                                <th class="border-bottom-0"><h6 class="fw-semibold mb-0">Action</h6></th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr>
                                <td class="border-bottom-0"><h6 class="fw-semibold mb-0">Drone</h6></td>
                                <td class="border-bottom-0"><p class="mb-0 fw-normal">Item</p></td>
                                <td class="border-bottom-0"><p class="mb-0 fw-normal">Security restriction, high risk</p></td>
                                <td class="border-bottom-0"><p class="mb-0 fw-normal">2025-01-15</p></td>
                                <td class="border-bottom-0"><a href="#" class="btn btn-sm btn-success">Remove</a></td>
                            </tr>
                            <tr>
                                <td class="border-bottom-0"><h6 class="fw-semibold mb-0">Student-001</h6></td>
                                <td class="border-bottom-0"><p class="mb-0 fw-normal">User ID</p></td>
                                <td class="border-bottom-0"><p class="mb-0 fw-normal">Repeated gatepass violations</p></td>
                                <td class="border-bottom-0"><p class="mb-0 fw-normal">2025-02-01</p></td>
                                <td class="border-bottom-0"><a href="#" class="btn btn-sm btn-success">Remove</a></td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

<?= $this->endSection() ?>