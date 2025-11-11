<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Dashboard</title>
    <!-- Link to your compiled Bootstrap CSS & Icons (adjust path as needed) -->
    <link rel="stylesheet" href="<?= base_url('assets/css/styles.min.css') ?>">
    <link rel="stylesheet" href="<?= base_url('assets/css/icons/tabler-icons/tabler-icons.css') ?>">

    <!-- We'll add all custom styles here -->
    <style>
        .sidebar-nav .nav-link {
            display: flex;
            align-items: center;
            font-size: 1.1rem;
            font-weight: 500;
            color: #333;
            padding: 0.75rem 1.5rem;
            border-bottom: 1px solid #f0f0f0;
        }
        .sidebar-nav .nav-link i {
            font-size: 1.5rem;
            margin-right: 1rem;
            color: #555;
        }
        .sidebar-nav .nav-link.active {
            background-color: rgba(var(--bs-primary-rgb), 0.1); /* <-- THIS IS THE FIX */
            color: var(--bs-primary);
        }
        .sidebar-nav .nav-link.active i {
            color: var(--bs-primary);
        }
        /* Minimal styles for layout */
        body {
            display: flex;
            flex-direction: column;
            min-height: 100vh;
            padding-top: 56px; /* Adjust if header height changes */
            background-color: #f8f9fa; /* Light background */
        }
        .main-content {
            flex-grow: 1;
        }

        /* --- Styles for Modal Pass Containers --- */
        .pass-code-container {
            margin: 0 auto 1rem auto;
            display: flex;
            justify-content: center;
            align-items: center;
            background-color: #fff;
            padding: 1rem;
            border-radius: 0.5rem;
        }
        .pass-code-container.qr-container {
            max-width: 270px; /* 250px image + padding */
        }
        .pass-code-container.barcode-container {
            max-width: 100%;
        }
        .pass-code-container img {
            display: block;
            max-width: 100%;
            height: auto;
        }
        /* Style for centered header text */
        .header-title {
            font-weight: 500;
            color: #333; /* Adjust color as needed */
            display: none;
        }
        @media (min-width: 576px) { /* Small screens and up */
            .header-title {
                display: block;
            }
        }
        /* Style adjustments for tabs */
        .main-content .nav-tabs {
            margin-bottom: 1.5rem; /* Add space below tabs */
        }
        .tab-pane {
            padding-top: 1rem; /* Add some padding inside tab panes */
        }
        /* Styles for empty state */
        .empty-state {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 3rem 1rem;
            text-align: center;
            color: #6c757d;
        }
        .empty-state i {
            font-size: 4rem;
            margin-bottom: 1rem;
        }

        /* --- STYLES FOR ITEM GRID (Dashboard) --- */
        .item-card {
            background-color: #fff;
            border: none;
            border-radius: 0.5rem;
            box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
            text-align: left;
            width: 100%;
            padding: 0;
            line-height: inherit;
        }
        .item-card-img-container {
            width: 100%;
            height: 120px;
            background-color: #eee;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
            border-top-left-radius: 0.5rem;
            border-top-right-radius: 0.5rem;
        }
        .item-card-img-container img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
        .item-card-body {
            padding: 0.75rem;
        }
        .item-card-title {
            font-weight: 600;
            font-size: 0.9rem;
            color: #333;
            margin-bottom: 0.25rem;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }
        .item-card-serial {
            font-size: 0.75rem;
            color: #6c757d;
            margin-bottom: 0.5rem;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }
        .item-card-status {
            font-size: 0.75rem;
            font-weight: 500;
        }
        .item-card-status.status-inside {
            color: var(--bs-success);
        }
        .item-card-status.status-outside {
            color: var(--bs-secondary);
        }
        /* --- NEW: Status styles for table --- */
        .status-badge {
            font-size: 0.75rem;
            font-weight: 600;
            padding: 0.25em 0.5em;
            border-radius: 0.25rem;
            text-transform: uppercase;
        }
        .status-badge-inside {
            color: var(--bs-success);
            background-color: rgba(var(--bs-success-rgb), 0.1);
        }
        .status-badge-outside {
            color: var(--bs-secondary);
            background-color: rgba(var(--bs-secondary-rgb), 0.1);
        }
        .status-badge-missing {
            color: var(--bs-warning);
            background-color: rgba(var(--bs-warning-rgb), 0.1);
        }
        .status-badge-stolen {
            color: var(--bs-danger);
            background-color: rgba(var(--bs-danger-rgb), 0.1);
        }


        /* Style for image preview */
        .image-preview-container {
            display: none;
            width: 150px;
            height: 150px;
            border: 2px dashed #ddd;
            border-radius: 0.5rem;
            overflow: hidden;
            margin-top: 1rem;
            background-color: #f8f9fa;
        }
        .image-preview-container img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
        .image-preview-container-text {
            display: flex;
            align-items: center;
            justify-content: center;
            height: 100%;
            color: #6c757d;
            font-size: 0.9rem;
        }

        /* --- STYLES FOR REMOVE/REPORT LIST --- */
        .remove-item-list .list-group-item {
            display: flex;
            align-items: center;
            justify-content: space-between;
        }
        .remove-item-list img {
            width: 45px;
            height: 45px;
            object-fit: cover;
            border-radius: 0.25rem;
            margin-right: 0.75rem;
        }
        .remove-item-info {
            flex-grow: 1;
            min-width: 0;
        }
        .remove-item-name {
            font-weight: 500;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            margin-bottom: 0;
            color: #333;
        }
        .remove-item-serial {
            font-size: 0.8rem;
            color: #6c757d;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        /* --- STYLES FOR ITEM TABLE ('My Items' tab) --- */
        .item-table {
            background-color: #fff;
            border-radius: 0.5rem;
            box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
        }
        .item-table th {
            font-weight: 600;
            color: #333;
        }
        .item-table td {
            vertical-align: middle;
        }
        .item-table-img {
            width: 40px;
            height: 40px;
            background-color: #eee;
            border-radius: 50%;
            display: inline-block;
            margin-right: 0.75rem;
            vertical-align: middle;
            object-fit: cover; /* This makes the image fit the circle */
        }
        .item-table-img-fallback {
            object-fit: contain; /* For the 'No Img' placeholder */
        }
        .item-table .item-name-cell {
            display: flex;
            align-items: center;
            font-weight: 500;
        }
        .action-buttons .btn {
            font-size: 0.75rem;
            padding: 0.25rem 0.5rem;
            margin-right: 0.25rem;
        }
        .action-buttons .btn-view {
            background-color: #28a745;
            color: #fff;
            border: none;
        }
        .action-buttons .btn-edit {
            background-color: #007bff;
            color: #fff;
            border: none;
        }
        .action-buttons .btn-delete {
            background-color: #dc3545;
            color: #fff;
            border: none;
        }

        /* --- STYLES FOR EDIT MODAL --- */
        #editItemModal .modal-body-loading {
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 200px;
        }
        #editItemModal .modal-body-form {
            display: none; /* Hide by default */
        }
        .edit-image-preview-container {
            /* Using the same styles as .image-preview-container */
            display: block; /* Show by default */
            width: 150px;
            height: 150px;
            border: 2px dashed #ddd;
            border-radius: 0.5rem;
            overflow: hidden;
            margin-top: 1rem;
            background-color: #f8f9fa;
        }
        .edit-image-preview-container img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
        .edit-image-preview-container-text {
            display: flex;
            align-items: center;
            justify-content: center;
            height: 100%;
            color: #6c757d;
            font-size: 0.9rem;
        }

        /* --- NEW: STYLES FOR REPORT MODAL --- */
        .report-item-container {
            display: flex;
            align-items: flex-start;
            gap: 1rem;
            background-color: #f8f9fa;
            padding: 1rem;
            border-radius: 0.5rem;
        }
        .report-item-container img {
            width: 80px;
            height: 80px;
            object-fit: cover;
            border-radius: 0.25rem;
        }
        .report-item-details h6 {
            margin-bottom: 0.25rem;
            font-weight: 600;
        }
        .report-item-details span {
            font-size: 0.9rem;
            color: #6c757d;
            line-height: 1.4;
            display: block; /* Make them stack */
        }

    </style>
</head>
<body>
<!-- Load CodeIgniter helper for form functions -->
<?php helper('form'); ?>

<!-- Header (Fixed Top) -->
<header class="navbar navbar-expand navbar-light bg-white fixed-top shadow-sm">
    <div class="container-fluid">
        <!-- NEW Hamburger Menu Button -->
        <button class="btn btn-link p-1 me-1" type="button" data-bs-toggle="offcanvas" data-bs-target="#studentSidebar" aria-controls="studentSidebar">
            <i class="ti ti-menu-2 fs-2"></i>
        </button>

        <!-- Logo -->
        <a class="navbar-brand" id="logo-brand-link" href="<?= base_url('student/dashboard') ?>">
            <img src="<?= base_url('assets/images/logos/logo.svg') ?>" alt="Logo" height="30">
        </a>
        <!-- Centered Title -->
        <div class="header-title mx-auto">
            TUP TAGUIG GATE PASS
        </div>
        <!-- Right side elements - Profile Dropdown -->
        <ul class="navbar-nav ms-auto">
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                    <i class="ti ti-user-circle fs-3"></i>
                </a>
                <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                    <li class="dropdown-item-text">Hi, <?= esc(session()->get('student_name')) ?>!</li>
                    <li><hr class="dropdown-divider"></li>
                    <li><a class="dropdown-item" href="<?= route_to('student_profile') ?>">Profile</a></li>
                    <li><a class="dropdown-item" href="<?= base_url('student/logout') ?>">Logout</a></li>
                </ul>
            </li>
        </ul>
    </div>
</header>

<!-- Main Content Area -->
<main class="main-content container mt-4">

    <!-- Tab Navigation -->
    <!-- === NEW OFF-CANVAS SIDEBAR === -->
    <div class="offcanvas offcanvas-start" tabindex="-1" id="studentSidebar" aria-labelledby="studentSidebarLabel">
        <div class="offcanvas-header">
            <h5 class="offcanvas-title" id="studentSidebarLabel">Menu</h5>
            <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
        </div>
        <div class="offcanvas-body p-0">

            <!-- Navigation links (re-styled tabs) -->
            <ul class="nav flex-column sidebar-nav" id="sidebarNav" role="tablist">
                <li class="nav-item" role="presentation">
                    <a class="nav-link active" id="sidebar-dashboard-tab" data-bs-toggle="tab" href="#dashboard-pane" role="tab" aria-controls="dashboard-pane" aria-selected="true">
                        <i class="ti ti-home"></i>
                        Dashboard
                    </a>
                </li>
                <li class="nav-item" role="presentation">
                    <a class="nav-link" id="sidebar-register-item-tab" data-bs-toggle="tab" href="#register-item-pane" role="tab" aria-controls="register-item-pane" aria-selected="false">
                        <i class="ti ti-plus"></i>
                        Register Item
                    </a>
                </li>
                <li class="nav-item" role="presentation">
                    <a class="nav-link" id="sidebar-registered-items-tab" data-bs-toggle="tab" href="#registered-items-pane" role="tab" aria-controls="registered-items-pane" aria-selected="false">
                        <i class="ti ti-list-check"></i>
                        My Items
                    </a>
                </li>
                <li class="nav-item" role="presentation">
                    <a class="nav-link" id="sidebar-remove-item-tab" data-bs-toggle="tab" href="#remove-item-pane" role="tab" aria-controls="remove-item-pane" aria-selected="false">
                        <i class="ti ti-trash"></i>
                        Remove Item
                    </a>
                </li>
                <!-- === NEW TAB LINK === -->
                <li class="nav-item" role="presentation">
                    <a class="nav-link" id="sidebar-report-item-tab" data-bs-toggle="tab" href="#report-item-pane" role="tab" aria-controls="report-item-pane" aria-selected="false">
                        <i class="ti ti-alert-triangle"></i>
                        Report Item
                    </a>
                </li>
            </ul>

        </div>
    </div>
    <!-- === END OFF-CANVAS SIDEBAR === -->

    <!-- Tab Content -->
    <div class="tab-content" id="itemTabsContent">

        <!-- =================================== -->
        <!-- Dashboard Pane (Item Grid) -->
        <!-- =================================== -->
        <div class="tab-pane fade show active" id="dashboard-pane" role="tabpanel" aria-labelledby="sidebar-dashboard-tab" tabindex="0">
            <?php if (! empty($activeItems)): // <-- UPDATED: Use $activeItems ?>
                <!-- If items exist, show them as a grid -->
                <h5 class="mb-3">Select an item to generate QR Pass</h5>
                <div class="row row-cols-2 g-3 item-grid">
                    <?php foreach ($activeItems as $item): // <-- UPDATED: Use $activeItems ?>
                        <div class="col">
                            <!-- This button holds the card and triggers the modal -->
                            <button type="button" class="btn item-card"
                                    data-bs-toggle="modal"
                                    data-bs-target="#itemQrModal"
                                    data-item-name="<?= esc($item['item_name']) ?>"
                                    data-item-serial="<?= esc($item['serial_number']) ?>"
                                    data-item-id="<?= esc($item['id']) ?>">
                                <!-- Item Image -->
                                <div class="item-card-img-container">
                                    <img src="<?= base_url('uploads/item_images/' . esc($item['image_path'])) ?>"
                                         alt="<?= esc($item['item_name']) ?>"
                                         onerror="this.src='https://placehold.co/300x200/eee/ccc?text=No+Image';">
                                </div>
                                <!-- Item Details -->
                                <div class="item-card-body">
                                    <div class="item-card-title"><?= esc($item['item_name']) ?></div>
                                    <div class="item-card-serial">SN: <?= esc($item['serial_number']) ?></div>
                                    <?php if ($item['status'] === 'inside'): ?>
                                        <span class="item-card-status status-inside"><i class="ti ti-building-check"></i> Inside Campus</span>
                                    <?php else: ?>
                                        <span class="item-card-status status-outside"><i class="ti ti-run"></i> Outside Campus</span>
                                    <?php endif; ?>
                                </div>
                            </button>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php else: ?>
                <!-- If no items, show empty state -->
                <div class="empty-state">
                    <i class="ti ti-mood-empty"></i>
                    <h5 class="mb-3">No Active Items Yet</h5>
                    <p>You have no active items yet. Register an item to generate a pass.</p>
                    <button type="button" class="btn btn-primary" id="addNewItemButton_dashboard">
                        <i class="ti ti-plus me-1"></i> Add New Item
                    </button>
                </div>
            <?php endif; ?>

            <!-- Latest Logs Section -->
            <div class="card shadow-sm my-4">
                <div class="card-header">
                    Latest Activity
                </div>
                <ul class="list-group list-group-flush">
                    <?php if (! empty($latestLogs)): ?>
                        <?php foreach ($latestLogs as $log): ?>
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <span>
                                    <?php if ($log['log_type'] === 'entry'): ?>
                                        <i class="ti ti-arrow-right text-success me-2"></i> Entered Campus
                                    <?php else: ?>
                                        <i class="ti ti-arrow-left text-danger me-2"></i> Exited Campus
                                    <?php endif; ?>
                                </span>
                                <small class="text-muted"><?= date('M d, Y - h:i A', strtotime(esc($log['timestamp']))) ?></small>
                            </li>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <li class="list-group-item text-muted">No recent logs found.</li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>

        <!-- =================================== -->
        <!-- Item Registration Pane -->
        <!-- =================================== -->
        <div class="tab-pane fade" id="register-item-pane" role="tabpanel" aria-labelledby="sidebar-register-item-tab" tabindex="0">            <h5>Register New Item</h5>
            <p>Fill out the form below to register a new item.</p>

            <?= form_open_multipart(route_to('student_item_create'), ['id' => 'registerItemForm']) ?>
            <?= csrf_field() ?>
            <div id="itemFormMessage" class="mt-3"></div>

            <div class="mb-3">
                <label for="item_name" class="form-label">Item Name</label>
                <input type="text" class="form-control" id="item_name" name="item_name" placeholder="e.g., Dell XPS 15 Laptop" required>
            </div>
            <div class="mb-3">
                <label for="serial_number" class="form-label">Serial Number</label>
                <input type="text" class="form-control" id="serial_number" name="serial_number" placeholder="e.g., ABC123456789" required>
                <div class="form-text">Enter the unique serial number of your device.</div>
            </div>
            <div class="mb-3">
                <label for="category" class="form-label">Category</label>
                <select class="form-select" id="category" name="category" required>
                    <option value="" selected disabled>Select a category...</option>
                    <option value="Personal Computing & Mobile">Personal Computing & Mobile</option>
                    <option value="Photography & Videography">Photography & Videography</option>
                    <option value="Audio & Music Equipment">Audio & Music Equipment</option>
                    <option value="Technical & Engineering Gear">Technical & Engineering Gear</option>
                    <option value="Art & Design Supplies">Art & Design Supplies</option>
                    <option value="Sporting & Fitness Equipment">Sporting & Fitness Equipment</option>
                    <option value="Large Portable Storage">Large Portable Storage</option>
                    <option value="Bulky/Household Items">Bulky/Household Items</option>
                    <option value="Personal Mobility Devices">Personal Mobility Devices</option>
                    <option value="Administrative/Office Use">Administrative/Office Use</option>
                </select>
            </div>
            <div class="mb-3">
                <label for="description" class="form-label">Description (Optional)</label>
                <textarea class="form-control" id="description" name="description" rows="3" placeholder="e.g., Black color, has a small sticker on the corner."></textarea>
            </div>
            <div class="mb-3">
                <label for="item_image" class="form-label">Item Image</label>
                <input class="form-control" type="file" id="item_image" name="item_image" accept="image/png, image/jpeg, image/jpg" required>
                <div class="form-text">Upload a clear photo. It will be compressed automatically.</div>
            </div>
            <div class="image-preview-container" id="imagePreview">
                <img src="" alt="Image Preview" class="d-none" id="imagePreviewImg">
                <span class="image-preview-container-text" id="imagePreviewText">Image Preview</span>
            </div>
            <div class="d-grid mt-4">
                <button type="submit" class="btn btn-primary" id="registerItemSubmitBtn">
                    <span class="spinner-border spinner-border-sm d-none" role="status" aria-hidden="true"></span>
                    Register Item
                </button>
            </div>
            <?= form_close() ?>
        </div>

        <!-- =================================== -->
        <!-- Registered Items Pane (NEW TABLE) -->
        <!-- =================================== -->
        <div class="tab-pane fade" id="registered-items-pane" role="tabpanel" aria-labelledby="sidebar-registered-items-tab" tabindex="0">            <h5 class="mb-3">My Registered Items</h5>
            <div id="editFormMessage" class="mt-3"></div> <!-- For edit success/error -->

            <?php if (! empty($registeredItems)): // <-- UPDATED: Use $registeredItems (all) ?>
                <div class="table-responsive">
                    <table class="table item-table align-middle">
                        <thead>
                        <tr>
                            <th scope="col">Name</th>
                            <th scope="col">Category</th>
                            <th scope="col">Serial Number</th>
                            <th scope="col">Description</th>
                            <th scope="col">Status</th> <!-- <-- NEW COLUMN -->
                            <th scope="col">Action</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php foreach ($registeredItems as $item): // <-- UPDATED: Use $registeredItems (all) ?>
                            <?php $status = $item['status']; // Get status for quick checks ?>
                            <tr>
                                <td class="item-name-cell">
                                    <img src="<?= base_url('uploads/item_images/' . esc($item['image_path'])) ?>"
                                         class="item-table-img"
                                         alt="<?= esc($item['item_name']) ?>"
                                         onerror="this.src='https://placehold.co/40x40/eee/ccc?text=X'; this.classList.add('item-table-img-fallback');">
                                    <span><?= esc($item['item_name']) ?></span>
                                </td>
                                <td><?= esc($item['category']) ?></td>
                                <td><?= esc($item['serial_number']) ?></td>
                                <td><?= esc($item['description']) ?></td>
                                <td> <!-- <-- NEW COLUMN DATA -->
                                    <?php
                                    $badgeClass = '';
                                    if ($status === 'inside') {
                                        $badgeClass = 'status-badge-inside';
                                    } elseif ($status === 'outside') {
                                        $badgeClass = 'status-badge-outside';
                                    } elseif ($status === 'missing') {
                                        $badgeClass = 'status-badge-missing';
                                    } elseif ($status === 'stolen') {
                                        $badgeClass = 'status-badge-stolen';
                                    }
                                    ?>
                                    <span class="status-badge <?= $badgeClass ?>"><?= esc(strtoupper($status)) ?></span>
                                </td>
                                <td class="action-buttons">
                                    <!-- UPDATED: VIEW and DELETE are always visible -->
                                    <button type="button" class="btn btn-view"
                                            data-bs-toggle="modal"
                                            data-bs-target="#itemQrModal"
                                            data-item-name="<?= esc($item['item_name']) ?>"
                                            data-item-serial="<?= esc($item['serial_number']) ?>"
                                            data-item-id="<?= esc($item['id']) ?>">
                                        VIEW
                                    </button>

                                    <button type="button" class="btn btn-delete"
                                            data-bs-toggle="modal"
                                            data-bs-target="#removeItemModal"
                                            data-item-id="<?= esc($item['id']) ?>"
                                            data-item-name="<?= esc($item['item_name']) ?>">
                                        DELETE
                                    </button>

                                    <!-- UPDATED: "EDIT" button is now always "EDIT" but targets the same modal -->
                                    <button type="button" class="btn btn-edit"
                                            data-bs-toggle="modal"
                                            data-bs-target="#editItemModal"
                                            data-item-id="<?= esc($item['id']) ?>">
                                        EDIT
                                    </button>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php else: ?>
                <!-- If no items, show empty state -->
                <div class="empty-state">
                    <i class="ti ti-mood-empty"></i>
                    <h5 class="mb-3">No Items Yet</h5>
                    <p>You have no registered items yet.</p>
                    <button type="button" class="btn btn-primary" id="addNewItemButton_myItems">
                        <i class="ti ti-plus me-1"></i> Add New Item
                    </button>
                </div>
            <?php endif; ?>
        </div>

        <!-- =================================== -->
        <!-- Remove Item Pane (List View) -->
        <!-- =================================== -->
        <div class="tab-pane fade" id="remove-item-pane" role="tabpanel" aria-labelledby="sidebar-remove-item-tab" tabindex="0">            <h5 class="mb-3">Remove Registered Item</h5>
            <div id="removeFormMessage"></div> <!-- For success/error messages -->

            <?php if (! empty($activeItems)): // <-- UPDATED: Use $activeItems ?>
                <p>Select an active item to remove.</p>
                <ul class="list-group remove-item-list">
                    <?php foreach ($activeItems as $item): // <-- UPDATED: Use $activeItems ?>
                        <li class="list-group-item" id="item-row-<?= esc($item['id']) ?>">
                            <img src="<?= base_url('uploads/item_images/' . esc($item['image_path'])) ?>"
                                 alt="<?= esc($item['item_name']) ?>"
                                 onerror="this.src='https://placehold.co/100x100/eee/ccc?text=No+Img';">
                            <div class="remove-item-info">
                                <p class="remove-item-name"><?= esc($item['item_name']) ?></p>
                                <span class="remove-item-serial">SN: <?= esc($item['serial_number']) ?></span>
                            </div>
                            <button type="button" class="btn btn-danger btn-sm"
                                    data-bs-toggle="modal"
                                    data-bs-target="#removeItemModal"
                                    data-item-id="<?= esc($item['id']) ?>"
                                    data-item-name="<?= esc($item['item_name']) ?>">
                                <i class="ti ti-trash"></i>
                            </button>
                        </li>
                    <?php endforeach; ?>
                </ul>
            <?php else: ?>
                <div class="empty-state">
                    <i class="ti ti-mood-empty"></i>
                    <h5 class="mb-3">No Items to Remove</h5>
                    <p>You have no active registered items.</p>
                </div>
            <?php endif; ?>
        </div>

        <!-- =================================== -->
        <!-- NEW: Report Item Pane (List View) -->
        <!-- =================================== -->
        <div class="tab-pane fade" id="report-item-pane" role="tabpanel" aria-labelledby="sidebar-report-item-tab" tabindex="0">
            <h5 class="mb-3">Report Lost Item</h5>
            <div id="reportFormMessage"></div> <!-- For success/error messages -->

            <?php if (! empty($itemsInside)): // <-- This is still correct ?>
                <p>Select an item that was lost or stolen **inside** the campus.</p>
                <ul class="list-group remove-item-list">
                    <?php foreach ($itemsInside as $item): // <-- This is still correct ?>
                        <li class="list-group-item" id="item-report-row-<?= esc($item['id']) ?>">
                            <img src="<?= base_url('uploads/item_images/' . esc($item['image_path'])) ?>"
                                 alt="<?= esc($item['item_name']) ?>"
                                 onerror="this.src='https://placehold.co/100x100/eee/ccc?text=No+Img';">
                            <div class="remove-item-info">
                                <p class="remove-item-name"><?= esc($item['item_name']) ?></p>
                                <span class="remove-item-serial">SN: <?= esc($item['serial_number']) ?></span>
                            </div>
                            <button type="button" class="btn btn-warning btn-sm"
                                    data-bs-toggle="modal"
                                    data-bs-target="#reportItemModal"
                                    data-item-id="<?= esc($item['id']) ?>"
                                    data-item-name="<?= esc($item['item_name']) ?>"
                                    data-item-serial="<?= esc($item['serial_number']) ?>"
                                    data-item-category="<?= esc($item['category']) ?>"
                                    data-item-image="<?= base_url('uploads/item_images/' . esc($item['image_path'])) ?>">
                                <i class="ti ti-alert-triangle me-1"></i> Report
                            </button>
                        </li>
                    <?php endforeach; ?>
                </ul>
            <?php else: ?>
                <div class="empty-state">
                    <i class="ti ti-check"></i>
                    <h5 class="mb-3">No Items Inside Campus</h5>
                    <p>You have no items currently marked as 'Inside Campus' to report.</p>
                </div>
            <?php endif; ?>
        </div>


    </div> <!-- End Tab Content -->

</main>

<!-- === Item Pass Modal (Shows Both QR and Barcode) === -->
<div class="modal fade" id="itemQrModal" tabindex="-1" aria-labelledby="itemQrModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="itemQrModalLabel">Item Pass</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-center">
                <p class="mb-2"><strong>QR Code</strong></p>
                <div class="pass-code-container qr-container">
                    <img id="modalQrImage" src="" alt="Item QR Code">
                </div>
                <p class="mt-4 mb-2"><strong>Barcode</strong></p>
                <div class="pass-code-container barcode-container">
                    <img id="modalBarcodeImage" src="" alt="Item Barcode" style="height: 80px;">
                </div>
                <h6 id="modalItemName" class="mt-4">Item Name</h6>
                <p class="small text-muted" id="modalItemSerial">Serial: ...</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
<!-- End Pass Modal -->


<!-- --- Remove Item Confirmation Modal --- -->
<div class="modal fade" id="removeItemModal" tabindex="-1" aria-labelledby="removeItemModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="removeItemModalLabel">Confirm Removal</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to remove this item?</p>
                <h6 id="modalRemoveItemName" class="mb-3">Item Name Here</h6>
                <div class="mb-3">
                    <label for="removal_reason" class="form-label">Reason for removal (Required):</label>
                    <textarea class="form-control" id="removal_reason" rows="3" placeholder="e.g., Sold, lost, no longer using..." required></textarea>
                    <div id="removeReasonError" class="text-danger small mt-2 d-none">Please provide a reason.</div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-danger" id="confirmRemoveItemBtn">
                    <span class="spinner-border spinner-border-sm d-none" role="status" aria-hidden="true"></span>
                    Yes, Remove
                </button>
            </div>
        </div>
    </div>
</div>
<!-- End Remove Modal -->


<!-- --- NEW: Report Item Form Modal --- -->
<div class="modal fade" id="reportItemModal" tabindex="-1" aria-labelledby="reportItemModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="reportItemModalLabel">Report Item</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <!-- Item Info Display -->
                <div id="modalReportItemInfo" class="report-item-container mb-3">
                    <img id="modalReportItemImg" src="" alt="Item Image" onerror="this.src='https://placehold.co/100x100/eee/ccc?text=No+Img';">
                    <div class="report-item-details">
                        <h6 id="modalReportItemName"></h6>
                        <span id="modalReportItemSerial"></span>
                        <span id="modalReportItemCategory"></span>
                    </div>
                </div>

                <!-- Report Form -->
                <div class="mb-3">
                    <label for="report_type" class="form-label">Report Type</label>
                    <select class="form-select" id="report_type" name="report_type" required>
                        <option value="missing">Missing</option>
                        <option value="stolen">Stolen</option>
                    </select>
                </div>
                <div class="mb-3">
                    <label for="report_reason" class="form-label">Reason For Report (Required):</label>
                    <textarea class="form-control" id="report_reason" rows="3" placeholder="Briefly explain what happened, where, and when." required></textarea>
                </div>

                <!-- Error Message Div -->
                <div id="reportModalError" class="text-danger small mt-2"></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-warning" id="confirmReportItemBtn">
                    <span class="spinner-border spinner-border-sm d-none" role="status" aria-hidden="true"></span>
                    Submit Report
                </button>
            </div>
        </div>
    </div>
</div>
<!-- End Report Modal -->


<!-- --- Mark as Found Modal (REMOVED) --- -->


<!-- --- Edit Item Modal (HEAVILY UPDATED) --- -->
<div class="modal fade" id="editItemModal" tabindex="-1" aria-labelledby="editItemModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editItemModalLabel">Edit Item</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <!-- Modal Body: Shows loading spinner first, then form -->
            <div class="modal-body">

                <!-- Loading Spinner -->
                <div class="modal-body-loading text-center" id="editModalLoading">
                    <div class="spinner-border" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                </div>

                <!-- Edit Form (hidden by default) -->
                <div class="modal-body-form" id="editModalFormContainer">
                    <?= form_open_multipart(route_to('student_item_update'), ['id' => 'editItemForm']) ?>
                    <?= csrf_field() ?>
                    <input type="hidden" id="edit_item_id" name="item_id">
                    <input type="hidden" id="edit_current_image_path" name="current_image_path">
                    <input type="hidden" id="edit_current_status" name="current_status"> <!-- To check status in JS -->

                    <div id="editFormMessageModal" class="mt-3"></div>

                    <!-- NEW: Status Update Dropdown (hidden by default) -->
                    <div class="mb-3 d-none" id="editStatusUpdateContainer">
                        <label for="edit_status_update" class="form-label">Update Status</label>
                        <select class="form-select" id="edit_status_update" name="status_update">
                            <option value="">-- Select Action --</option>
                            <option value="found">I have found this item</option>
                        </select>
                        <hr>
                    </div>

                    <div class="mb-3">
                        <label for="edit_item_name" class="form-label">Item Name</label>
                        <input type="text" class="form-control" id="edit_item_name" name="item_name" required>
                    </div>
                    <div class="mb-3">
                        <label for="edit_serial_number" class="form-label">Serial Number</label>
                        <input type="text" class="form-control" id="edit_serial_number" name="serial_number" required>
                    </div>
                    <div class="mb-3">
                        <label for="edit_category" class="form-label">Category</label>
                        <select class="form-select" id="edit_category" name="category" required>
                            <option value="" selected disabled>Select a category...</option>
                            <option value="Personal Computing & Mobile">Personal Computing & Mobile</option>
                            <option value="Photography & Videography">Photography & Videography</option>
                            <option value="Audio & Music Equipment">Audio & Music Equipment</option>
                            <option value="Technical & Engineering Gear">Technical & Engineering Gear</option>
                            <option value="Art & Design Supplies">Art & Design Supplies</option>
                            <option value="Sporting & Fitness Equipment">Sporting & Fitness Equipment</option>
                            <option value="Large Portable Storage">Large Portable Storage</option>
                            <option value_a"Bulky/Household Items">Bulky/Household Items</option>
                            <option value="Personal Mobility Devices">Personal Mobility Devices</option>
                            <option value="Administrative/Office Use">Administrative/Office Use</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="edit_description" class="form-label">Description (Optional)</label>
                        <textarea class="form-control" id="edit_description" name="description" rows="3"></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="edit_item_image" class="form-label">Upload New Image (Optional)</label>
                        <input class="form-control" type="file" id="edit_item_image" name="item_image" accept="image/png, image/jpeg, image/jpg">
                        <div class="form-text">Leave blank to keep the current image.</div>
                    </div>

                    <label class="form-label">Current Image:</label>
                    <div class="edit-image-preview-container" id="editImagePreview">
                        <img src="" alt="Image Preview" class="d-none" id="editImagePreviewImg">
                        <span class="edit-image-preview-container-text" id="editImagePreviewText">Image Preview</span>
                    </div>

                    <?= form_close() ?>
                </div>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="submit" class="btn btn-primary" id="saveEditItemBtn" form="editItemForm">
                    <span class="spinner-border spinner-border-sm d-none" role="status" aria-hidden="true"></span>
                    Save Changes
                </button>
            </div>
        </div>
    </div>
</div>
<!-- End Edit Modal -->


<!-- Link to your compiled Bootstrap JS (and Popper.js if needed) -->
<script src="<?= base_url('assets/libs/jquery/dist/jquery.min.js') ?>"></script>
<script src="<?= base_url('assets/libs/bootstrap/dist/js/bootstrap.bundle.min.js') ?>"></script>

<!-- JavaScript to handle "Add New Item" button click, Modal, and AJAX Form Submission -->
<script>
    // --- Blob storage for image compression ---
    let processedRegisterImageBlob = null;
    let processedEditImageBlob = null; // Separate blob for the edit form

    /**
     * Compresses an image file using HTML Canvas.
     * @param {File} file - The original image file.
     * @returns {Promise<Blob>} A Promise that resolves with the compressed image Blob.
     */
    function compressImage(file) {
        return new Promise((resolve, reject) => {
            const MAX_WIDTH = 1280;
            const MAX_HEIGHT = 1280;
            const QUALITY = 0.8;
            const MIME_TYPE = 'image/jpeg';
            const reader = new FileReader();
            reader.onload = function(e) {
                const img = new Image();
                img.onload = function() {
                    let width = img.width;
                    let height = img.height;
                    if (width > height) {
                        if (width > MAX_WIDTH) {
                            height *= MAX_WIDTH / width;
                            width = MAX_WIDTH;
                        }
                    } else {
                        if (height > MAX_HEIGHT) {
                            width *= MAX_HEIGHT / height;
                            height = MAX_HEIGHT;
                        }
                    }
                    const canvas = document.createElement('canvas');
                    canvas.width = width;
                    canvas.height = height;
                    const ctx = canvas.getContext('2d');
                    ctx.drawImage(img, 0, 0, width, height);
                    canvas.toBlob(
                        (blob) => {
                            if (blob) {
                                resolve(blob);
                            } else {
                                reject(new Error('Canvas toBlob failed.'));
                            }
                        },
                        MIME_TYPE,
                        QUALITY
                    );
                };
                img.onerror = reject;
                img.src = e.target.result;
            };
            reader.onerror = reject;
            reader.readAsDataURL(file);
        });
    }


    document.addEventListener('DOMContentLoaded', function () {

        // Function to handle switching to register tab
        function switchToRegisterTab() {
            const registerTabButton = document.getElementById('sidebar-register-item-tab'); // <-- THIS ID IS NOW CORRECT
            if (registerTabButton) {
                const tab = new bootstrap.Tab(registerTabButton);
                tab.show();
            }
        }

        // Find the buttons for switching tabs
        const addNewItemButtonDash = document.getElementById('addNewItemButton_dashboard');
        const addNewItemButtonItems = document.getElementById('addNewItemButton_myItems');
        if (addNewItemButtonDash) {
            addNewItemButtonDash.addEventListener('click', switchToRegisterTab);
        }
        if (addNewItemButtonItems) {
            addNewItemButtonItems.addEventListener('click', switchToRegisterTab);
        }

        // --- NEW: Auto-close sidebar on link click ---
        const studentSidebar = document.getElementById('studentSidebar');
        if (studentSidebar) {
            const sidebarLinks = studentSidebar.querySelectorAll('.nav-link[data-bs-toggle="tab"]');
            const offcanvasInstance = new bootstrap.Offcanvas(studentSidebar);

            sidebarLinks.forEach(link => {
                link.addEventListener('click', () => {
                    offcanvasInstance.hide();
                });
            });
        }

        // --- NEW: Make logo open sidebar ---
        const logoBrandLink = document.getElementById('logo-brand-link');
        const studentSidebarEl = document.getElementById('studentSidebar');

        if (logoBrandLink && studentSidebarEl) {
            // Get the Bootstrap offcanvas instance
            const sidebarInstance = bootstrap.Offcanvas.getInstance(studentSidebarEl) || new bootstrap.Offcanvas(studentSidebarEl);

            logoBrandLink.addEventListener('click', function(e) {
                e.preventDefault(); // Stop the link from navigating to the dashboard
                sidebarInstance.show(); // Manually show the sidebar
            });
        }

        // --- JavaScript for Item Pass Modal (Both Codes) ---

        // --- JavaScript for Item Pass Modal (Both Codes) ---
        const itemQrModal = document.getElementById('itemQrModal');
        const modalQrImage = itemQrModal.querySelector('#modalQrImage');
        const modalBarcodeImage = itemQrModal.querySelector('#modalBarcodeImage');
        if(itemQrModal) {
            itemQrModal.addEventListener('show.bs.modal', function (event) {
                const button = event.relatedTarget;
                const itemName = button.getAttribute('data-item-name');
                const itemSerial = button.getAttribute('data-item-serial');
                const itemId = button.getAttribute('data-item-id');
                const passData = `ITEM_ID:${itemId};SERIAL:${itemSerial}`;
                const modalTitle = itemQrModal.querySelector('#itemQrModalLabel');
                const modalItemName = itemQrModal.querySelector('#modalItemName');
                const modalItemSerial = itemQrModal.querySelector('#modalItemSerial');
                modalTitle.textContent = itemName + ' Pass';
                modalItemName.textContent = itemName;
                modalItemSerial.textContent = 'Serial: ' + itemSerial;
                const qrUrl = `https://api.qrserver.com/v1/create-qr-code/?size=250x250&data=${encodeURIComponent(passData)}`;
                const barcodeUrl = `https://barcode.tec-it.com/barcode.ashx?data=${encodeURIComponent(passData)}&code=Code128&dpi=96&showhrt=false`;
                modalQrImage.src = qrUrl;
                modalBarcodeImage.src = barcodeUrl;
            });
        }


        // --- Get CSRF Token Name and Hash from PHP ---
        const csrfTokenName = "<?= config('Security')->tokenName ?>";
        let csrfInputRegister = document.querySelector('#registerItemForm input[name="' + csrfTokenName + '"]');
        let csrfInputEdit = document.querySelector('#editItemForm input[name="' + csrfTokenName + '"]');

        // Function to update all CSRF tokens on the page
        function updateCsrfTokens(newHash) {
            if (newHash) {
                if (csrfInputRegister) csrfInputRegister.value = newHash;
                if (csrfInputEdit) csrfInputEdit.value = newHash;
            }
        }

        // --- AJAX Form Submission for Register Item ---
        const itemForm = document.getElementById('registerItemForm');
        const submitButton = document.getElementById('registerItemSubmitBtn');
        const messageDiv = document.getElementById('itemFormMessage');
        const submitButtonSpinner = submitButton.querySelector('.spinner-border');
        if (itemForm) {
            itemForm.addEventListener('submit', async function(e) {
                e.preventDefault();
                if (!processedRegisterImageBlob) {
                    if (document.getElementById('item_image').files.length === 0) {
                        messageDiv.innerHTML = `<div class="alert alert-danger">Please select an image file.</div>`;
                        return;
                    }
                    messageDiv.innerHTML = `<div class="alert alert-warning">Image is still processing, please wait...</div>`;
                    return;
                }
                submitButton.disabled = true;
                submitButtonSpinner.classList.remove('d-none');
                messageDiv.innerHTML = '';
                const formData = new FormData();
                formData.append('item_name', document.getElementById('item_name').value);
                formData.append('serial_number', document.getElementById('serial_number').value);
                formData.append('category', document.getElementById('category').value);
                formData.append('description', document.getElementById('description').value);
                formData.append(csrfInputRegister.name, csrfInputRegister.value);
                formData.append('item_image', processedRegisterImageBlob, 'item_image.jpg');
                try {
                    const response = await fetch(itemForm.action, {
                        method: 'POST',
                        body: formData,
                        headers: { 'X-Requested-With': 'XMLHttpRequest' }
                    });
                    updateCsrfTokens(response.headers.get('X-CSRF-TOKEN'));
                    const result = await response.json();
                    if (response.ok) {
                        messageDiv.innerHTML = `<div class="alert alert-success alert-dismissible fade show" role="alert">
                                ${result.message}
                                <button type."button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>`;
                        itemForm.reset();
                        document.getElementById('imagePreview').style.display = 'none';
                        processedRegisterImageBlob = null;
                        setTimeout(() => { location.reload(); }, 1500);
                    } else {
                        let errorHtml = '<strong>Please correct the following errors:</strong><ul>';
                        if (result.errors) {
                            for (const key in result.errors) {
                                errorHtml += `<li>${result.errors[key]}</li>`;
                            }
                        } else {
                            errorHtml += `<li>An unknown error occurred.</li>`;
                        }
                        errorHtml += '</ul>';
                        messageDiv.innerHTML = `<div class="alert alert-danger alert-dismissible fade show" role="alert">
                                ${errorHtml}
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>`;
                    }
                } catch (error) {
                    console.error('Form submission error:', error);
                    messageDiv.innerHTML = `<div class="alert alert-danger alert-dismissible fade show" role="alert">
                            An error occurred while submitting the form. Please check your network and try again.
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>`;
                } finally {
                    submitButton.disabled = false;
                    submitButtonSpinner.classList.add('d-none');
                }
            });
        }

        // --- Image Preview and Compression (REGISTER FORM) ---
        const imageInput = document.getElementById('item_image');
        const imagePreview = document.getElementById('imagePreview');
        const imagePreviewImg = document.getElementById('imagePreviewImg');
        const imagePreviewText = document.getElementById('imagePreviewText');
        if (imageInput) {
            imageInput.addEventListener('change', async function() {
                const file = this.files[0];
                if (file) {
                    // --- UPDATED: Check file size ---
                    const fileSizeMB = file.size / 1024 / 1024;
                    if (fileSizeMB > 30) { // 30MB limit
                        messageDiv.innerHTML = `<div class="alert alert-warning">File is very large (${fileSizeMB.toFixed(1)} MB). Compression may be slow or fail.</div>`;
                    }
                    // --- END UPDATE ---

                    const reader = new FileReader();
                    imagePreview.style.display = 'block';
                    imagePreviewImg.classList.add('d-none');
                    imagePreviewText.textContent = 'Compressing...';
                    imagePreviewText.classList.remove('d-none');
                    reader.onload = function(e) {
                        imagePreviewImg.src = e.target.result;
                        imagePreviewImg.classList.remove('d-none');
                        imagePreviewText.classList.add('d-none');
                    }
                    reader.readAsDataURL(file);
                    try {
                        processedRegisterImageBlob = await compressImage(file);
                        console.log('Register Image compressed:', processedRegisterImageBlob.size / 1024 + ' KB');
                    } catch (error) {
                        console.error('Image compression error:', error);
                        messageDiv.innerHTML = `<div class="alert alert-danger">Error compressing image. Please try a different file.</div>`;
                        processedRegisterImageBlob = null;
                    }
                } else {
                    imagePreview.style.display = 'none';
                    processedRegisterImageBlob = null;
                }
            });
        }

        // --- JavaScript for Remove Item Modal & AJAX ---
        const removeItemModal = document.getElementById('removeItemModal');
        const confirmRemoveBtn = document.getElementById('confirmRemoveItemBtn');
        const removeMsgDiv = document.getElementById('removeFormMessage');
        const reasonTextArea = document.getElementById('removal_reason');
        let itemIdToRemove = null;
        if (removeItemModal) {
            removeItemModal.addEventListener('show.bs.modal', function (event) {
                const button = event.relatedTarget;
                itemIdToRemove = button.getAttribute('data-item-id');
                const itemName = button.getAttribute('data-item-name');
                const modalItemName = removeItemModal.querySelector('#modalRemoveItemName');
                modalItemName.textContent = itemName;
                reasonTextArea.value = '';
                document.getElementById('removeReasonError').classList.add('d-none'); // <-- ADD THIS LINE
                confirmRemoveBtn.disabled = false;
                confirmRemoveBtn.querySelector('.spinner-border').classList.add('d-none');
            });
            confirmRemoveBtn.addEventListener('click', async function() {
                if (!itemIdToRemove) return;

                const reason = reasonTextArea.value.trim();
                const errorDiv = document.getElementById('removeReasonError');

                // --- NEW: Validation Check ---
                if (reason === '') {
                    errorDiv.classList.remove('d-none');
                    return; // Stop if reason is empty
                }
                // --- END: Validation Check ---

                errorDiv.classList.add('d-none'); // Hide error if valid
                confirmRemoveBtn.disabled = true;
                confirmRemoveBtn.querySelector('.spinner-border').classList.remove('d-none');
                removeMsgDiv.innerHTML = '';

                const formData = new FormData();
                formData.append('item_id', itemIdToRemove);
                formData.append('reason', reason);
                formData.append(csrfInputRegister.name, csrfInputRegister.value); // Use any valid token
                try {
                    const response = await fetch("<?= route_to('student_item_delete') ?>", {
                        method: 'POST',
                        body: formData,
                        headers: { 'X-Requested-With': 'XMLHttpRequest' }
                    });
                    updateCsrfTokens(response.headers.get('X-CSRF-TOKEN'));
                    const result = await response.json();
                    if (response.ok) {
                        removeMsgDiv.innerHTML = `<div class="alert alert-success alert-dismissible fade show" role="alert">
                            ${result.message}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>`;
                        bootstrap.Modal.getInstance(removeItemModal).hide();
                        setTimeout(() => { location.reload(); }, 1000);
                    } else {
                        let errorHtml = result.errors ? Object.values(result.errors).join('<br>') : 'An unknown error occurred.';
                        removeMsgDiv.innerHTML = `<div class="alert alert-danger alert-dismissible fade show" role="alert">
                            ${errorHtml}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>`;
                        bootstrap.Modal.getInstance(removeItemModal).hide();
                    }
                } catch (error) {
                    console.error('Delete error:', error);
                    removeMsgDiv.innerHTML = `<div class="alert alert-danger">Network error. Please try again.</div>`;
                    bootstrap.Modal.getInstance(removeItemModal).hide();
                } finally {
                    itemIdToRemove = null;
                }
            });
        }


        // --- === NEW: JavaScript for Report Item Modal === ---
        const reportItemModal = document.getElementById('reportItemModal');
        const confirmReportBtn = document.getElementById('confirmReportItemBtn');
        const reportMsgDiv = document.getElementById('reportFormMessage'); // On main page
        const reportModalErrorDiv = document.getElementById('reportModalError'); // In modal
        const reportReasonText = document.getElementById('report_reason');
        const reportTypeSelect = document.getElementById('report_type');
        let itemIdToReport = null;

        if (reportItemModal) {
            // 1. Populate Modal on Show
            reportItemModal.addEventListener('show.bs.modal', function (event) {
                const button = event.relatedTarget;
                itemIdToReport = button.getAttribute('data-item-id');

                // Populate item info
                document.getElementById('modalReportItemName').textContent = button.getAttribute('data-item-name');
                document.getElementById('modalReportItemSerial').textContent = 'SN: ' + button.getAttribute('data-item-serial');
                document.getElementById('modalReportItemCategory').textContent = button.getAttribute('data-item-category');
                document.getElementById('modalReportItemImg').src = button.getAttribute('data-item-image');

                // Reset form
                reportReasonText.value = '';
                reportTypeSelect.value = 'missing';
                reportModalErrorDiv.innerHTML = '';
                confirmReportBtn.disabled = false;
                confirmReportBtn.querySelector('.spinner-border').classList.add('d-none');
            });

            // 2. Handle AJAX Submission on Click
            confirmReportBtn.addEventListener('click', async function() {
                if (!itemIdToReport) return;

                const reason = reportReasonText.value.trim();
                const type = reportTypeSelect.value;

                // Client-side validation
                if (reason === '') {
                    reportModalErrorDiv.innerHTML = 'Please provide a reason for the report.';
                    return;
                }

                reportModalErrorDiv.innerHTML = '';
                confirmReportBtn.disabled = true;
                confirmReportBtn.querySelector('.spinner-border').classList.remove('d-none');
                reportMsgDiv.innerHTML = ''; // Clear main page message

                const formData = new FormData();
                formData.append('item_id', itemIdToReport);
                formData.append('report_type', type);
                formData.append('report_reason', reason);
                formData.append(csrfInputRegister.name, csrfInputRegister.value); // Use any valid token

                try {
                    const response = await fetch("<?= route_to('student_item_report') ?>", {
                        method: 'POST',
                        body: formData,
                        headers: { 'X-Requested-With': 'XMLHttpRequest' }
                    });

                    updateCsrfTokens(response.headers.get('X-CSRF-TOKEN'));
                    const result = await response.json();

                    if (response.ok) {
                        // Success: Show message on main page, hide modal, reload
                        reportMsgDiv.innerHTML = `<div class="alert alert-success alert-dismissible fade show" role="alert">
                            ${result.message}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>`;
                        bootstrap.Modal.getInstance(reportItemModal).hide();
                        setTimeout(() => { location.reload(); }, 1000);
                    } else {
                        // Error: Show message inside modal
                        let errorHtml = '';
                        if (result.errors) {
                            errorHtml = Object.values(result.errors).join('<br>');
                        } else {
                            errorHtml = result.error || 'An unknown error occurred.';
                        }
                        reportModalErrorDiv.innerHTML = errorHtml;
                    }
                } catch (error) {
                    console.error('Report error:', error);
                    reportModalErrorDiv.innerHTML = 'Network error. Please try again.';
                } finally {
                    // Re-enable button
                    confirmReportBtn.disabled = false;
                    confirmReportBtn.querySelector('.spinner-border').classList.add('d-none');
                }
            });
        }
        // --- === END: Report Item Modal JS === ---


        // --- === Mark as Found Modal JS (REMOVED) === ---


        // --- === JavaScript for Edit Item Modal (HEAVILY UPDATED) === ---

        // --- Image Preview and Compression (EDIT FORM) ---
        const editImageInput = document.getElementById('edit_item_image');
        const editImagePreview = document.getElementById('editImagePreview');
        const editImagePreviewImg = document.getElementById('editImagePreviewImg');
        const editImagePreviewText = document.getElementById('editImagePreviewText');
        const editFormMsgModal = document.getElementById('editFormMessageModal');

        if (editImageInput) {
            editImageInput.addEventListener('change', async function() {
                const file = this.files[0];
                if (file) {
                    // --- UPDATED: Check file size ---
                    const fileSizeMB = file.size / 1024 / 1024;
                    if (fileSizeMB > 30) { // 30MB limit
                        editFormMsgModal.innerHTML = `<div class="alert alert-warning">File is very large (${fileSizeMB.toFixed(1)} MB). Compression may be slow or fail.</div>`;
                    } else {
                        editFormMsgModal.innerHTML = ''; // Clear warning
                    }
                    // --- END UPDATE ---

                    const reader = new FileReader();
                    editImagePreview.style.display = 'block';
                    editImagePreviewImg.classList.add('d-none');
                    editImagePreviewText.textContent = 'Compressing...';
                    editImagePreviewText.classList.remove('d-none');
                    reader.onload = function(e) {
                        editImagePreviewImg.src = e.target.result;
                        imagePreviewImg.classList.remove('d-none');
                        imagePreviewText.classList.add('d-none');
                    }
                    reader.readAsDataURL(file);
                    try {
                        processedEditImageBlob = await compressImage(file);
                        console.log('Edit Image compressed:', processedEditImageBlob.size / 1024 + ' KB');
                    } catch (error) {
                        console.error('Image compression error:', error);
                        editFormMsgModal.innerHTML = `<div class="alert alert-danger">Error compressing image. Please try a different file.</div>`;
                        processedEditImageBlob = null;
                    }
                } else {
                    // If file input is cleared, reset to show the current image
                    const currentImagePath = document.getElementById('edit_current_image_path').value;
                    if(currentImagePath) {
                        editImagePreviewImg.src = "<?= base_url('uploads/item_images') ?>/" + currentImagePath;
                        editImagePreviewImg.classList.remove('d-none');
                        editImagePreviewText.classList.add('d-none');
                    } else {
                        editImagePreviewImg.classList.add('d-none');
                        editImagePreviewText.textContent = 'No Image';
                        editImagePreviewText.classList.remove('d-none');
                    }
                    processedEditImageBlob = null;
                }
            });
        }

        // --- Edit Modal Show Event (Fetch Data) ---
        const editItemModal = document.getElementById('editItemModal');
        const editModalLoading = document.getElementById('editModalLoading');
        const editModalFormContainer = document.getElementById('editModalFormContainer');
        const saveEditItemBtn = document.getElementById('saveEditItemBtn');

        // Form fields
        const editItemId = document.getElementById('edit_item_id');
        const editItemName = document.getElementById('edit_item_name');
        const editSerialNumber = document.getElementById('edit_serial_number');
        const editCategory = document.getElementById('edit_category');
        const editDescription = document.getElementById('edit_description');
        const editCurrentImagePath = document.getElementById('edit_current_image_path');
        const editCurrentStatus = document.getElementById('edit_current_status');
        const editItemImageInput = document.getElementById('edit_item_image');

        // The new "Found" dropdown
        const editStatusUpdateContainer = document.getElementById('editStatusUpdateContainer');
        const editStatusUpdateSelect = document.getElementById('edit_status_update');

        if (editItemModal) {
            editItemModal.addEventListener('show.bs.modal', async function (event) {
                const button = event.relatedTarget;
                const itemId = button.getAttribute('data-item-id');

                // Reset form state
                editModalLoading.style.display = 'flex';
                editModalFormContainer.style.display = 'none';
                editFormMsgModal.innerHTML = '';
                processedEditImageBlob = null;
                document.getElementById('editItemForm').reset();

                // Fetch item data
                let url = "<?= base_url('student/items/get') ?>/" + itemId;
                try {
                    const response = await fetch(url, {
                        method: 'GET',
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest',
                            'X-CSRF-TOKEN': csrfInputRegister.value // Use any valid token
                        }
                    });

                    updateCsrfTokens(response.headers.get('X-CSRF-TOKEN'));
                    const result = await response.json();

                    if (response.ok && result.success) {
                        // Populate the form
                        const item = result.item;
                        editItemId.value = item.id;
                        editItemName.value = item.item_name;
                        editSerialNumber.value = item.serial_number;
                        editCategory.value = item.category;
                        editDescription.value = item.description || '';
                        editCurrentImagePath.value = item.image_path;
                        editCurrentStatus.value = item.status; // Store current status

                        // Set current image preview
                        if(item.image_path) {
                            editImagePreviewImg.src = "<?= base_url('uploads/item_images') ?>/" + item.image_path;
                            editImagePreviewImg.classList.remove('d-none');
                            editImagePreviewText.classList.add('d-none');
                        } else {
                            editImagePreviewImg.classList.add('d-none');
                            editImagePreviewText.textContent = 'No Image';
                            editImagePreviewText.classList.remove('d-none');
                        }

                        // --- NEW LOGIC: Configure modal based on status ---
                        const isReported = (item.status === 'missing' || item.status === 'stolen');

                        // Toggle disabled state for all fields
                        editItemName.disabled = isReported;
                        editSerialNumber.disabled = isReported;
                        editCategory.disabled = isReported;
                        editDescription.disabled = isReported;
                        editItemImageInput.disabled = isReported;

                        if (isReported) {
                            // Item is "Missing" or "Stolen"
                            editStatusUpdateContainer.classList.remove('d-none'); // Show "Found" dropdown
                            editStatusUpdateSelect.value = ''; // Reset dropdown
                            saveEditItemBtn.textContent = 'Update Status'; // Change button text
                        } else {
                            // Item is "Inside" or "Outside"
                            editStatusUpdateContainer.classList.add('d-none'); // Hide "Found" dropdown
                            saveEditItemBtn.textContent = 'Save Changes'; // Set button text to normal
                        }
                        // --- END NEW LOGIC ---

                        // Show form, hide spinner
                        editModalLoading.style.display = 'none';
                        editModalFormContainer.style.display = 'block';
                    } else {
                        // Show error
                        editModalLoading.style.display = 'none';
                        editFormMsgModal.innerHTML = `<div class="alert alert-danger">${result.error || 'Could not load item data.'}</div>`;
                    }
                } catch (error) {
                    console.error('Fetch item error:', error);
                    editModalLoading.style.display = 'none';
                    editFormMsgModal.innerHTML = `<div class="alert alert-danger">Network error. Please try again.</div>`;
                }
            });
        }

        // --- Edit Form Submission (AJAX POST) ---
        const editItemForm = document.getElementById('editItemForm');

        if (editItemForm) {
            editItemForm.addEventListener('submit', async function(e) {
                e.preventDefault();

                const submitBtnSpinner = saveEditItemBtn.querySelector('.spinner-border');
                saveEditItemBtn.disabled = true;
                submitBtnSpinner.classList.remove('d-none');
                editFormMsgModal.innerHTML = '';

                // --- NEW LOGIC: Check if we are in "Edit Mode" or "Found Mode" ---
                const currentStatus = editCurrentStatus.value;
                const isReported = (currentStatus === 'missing' || currentStatus === 'stolen');

                if (isReported) {
                    // --- We are in "FOUND" mode ---
                    const action = editStatusUpdateSelect.value;
                    if (action === 'found') {
                        // User selected "I have found this item"
                        const formData = new FormData();
                        formData.append('item_id', editItemId.value);
                        formData.append(csrfInputEdit.name, csrfInputEdit.value);

                        try {
                            const response = await fetch("<?= route_to('student_item_found') ?>", {
                                method: 'POST',
                                body: formData,
                                headers: { 'X-Requested-With': 'XMLHttpRequest' }
                            });

                            updateCsrfTokens(response.headers.get('X-CSRF-TOKEN'));
                            const result = await response.json();

                            if (response.ok) {
                                // Success! Show message in the main page (not modal)
                                document.getElementById('editFormMessage').innerHTML = `<div class="alert alert-success alert-dismissible fade show" role="alert">
                                    ${result.message}
                                    <button type."button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                </div>`;
                                bootstrap.Modal.getInstance(editItemModal).hide();
                                setTimeout(() => { location.reload(); }, 1000);
                            } else {
                                // Error: Show message inside modal
                                editFormMsgModal.innerHTML = `<div class="alert alert-danger">${result.error || 'An unknown error occurred.'}</div>`;
                            }
                        } catch (error) {
                            console.error('Mark as Found error:', error);
                            editFormMsgModal.innerHTML = `<div class="alert alert-danger">Network error. Please try again.</div>`;
                        } finally {
                            saveEditItemBtn.disabled = false;
                            submitBtnSpinner.classList.add('d-none');
                        }
                    } else {
                        // User didn't select an action
                        editFormMsgModal.innerHTML = `<div class="alert alert-warning">Please select an action from the dropdown.</div>`;
                        saveEditItemBtn.disabled = false;
                        submitBtnSpinner.classList.add('d-none');
                    }

                } else {
                    // --- We are in "EDIT" mode (normal operation) ---
                    const formData = new FormData(editItemForm);
                    formData.append(csrfInputEdit.name, csrfInputEdit.value);

                    // If a new image was compressed, remove the old one and add the new one
                    if (processedEditImageBlob) {
                        formData.delete('item_image'); // Remove original file
                        formData.append('item_image', processedEditImageBlob, 'edit_image.jpg');
                    }

                    try {
                        const response = await fetch(editItemForm.action, {
                            method: 'POST',
                            body: formData,
                            headers: { 'X-Requested-With': 'XMLHttpRequest' }
                        });

                        updateCsrfTokens(response.headers.get('X-CSRF-TOKEN'));
                        const result = await response.json();

                        if (response.ok) {
                            // Success! Show message in the main page (not modal)
                            document.getElementById('editFormMessage').innerHTML = `<div class="alert alert-success alert-dismissible fade show" role="alert">
                                    ${result.message}
                                    <button type."button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                </div>`;

                            bootstrap.Modal.getInstance(editItemModal).hide();
                            setTimeout(() => { location.reload(); }, 1000); // Reload to see changes

                        } else {
                            // Validation or other errors
                            let errorHtml = '<strong>Please correct the following errors:</strong><ul>';
                            if (result.errors) {
                                for (const key in result.errors) {
                                    errorHtml += `<li>${result.errors[key]}</li>`;
                                }
                            } else {
                                errorHtml += `<li>${result.error || 'An unknown error occurred.'}</li>`;
                            }
                            errorHtml += '</ul>';
                            editFormMsgModal.innerHTML = `<div class="alert alert-danger alert-dismissible fade show" role="alert">
                                    ${errorHtml}
                                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                </div>`;
                        }
                    } catch (error) {
                        console.error('Edit form submission error:', error);
                        editFormMsgModal.innerHTML = `<div class="alert alert-danger">Network error. Please try again.</div>`;
                    } finally {
                        saveEditItemBtn.disabled = false;
                        submitBtnSpinner.classList.add('d-none');
                    }
                }
            });
        }

    });
    window.addEventListener('pageshow', function(event) {
        // event.persisted is true when the page is loaded from the BFcache
        if (event.persisted) {
            // Force a hard reload from the server
            window.location.reload();
        }
    });
</script>

</body>
</html>