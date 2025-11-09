<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Guard Dashboard</title>
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
        /* --- Scanner Styles --- */
        #scanner-container {
            width: 100%;
            max-width: 500px;
            margin: 1rem auto;
            border: 2px dashed #ccc;
            border-radius: 0.5rem;
            overflow: hidden;
        }
        #scanner-container video {
            width: 100% !important;
            height: auto !important;
        }
        /* --- Result Styles --- */
        #scan-result-container {
            display: none; /* Hidden by default */
            max-width: 500px;
            margin: 1rem auto;
        }
        .result-card {
            box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
            border: none;
        }
        .result-image-container {
            width: 100%;
            height: 250px;
            background-color: #eee;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
            border-top-left-radius: 0.5rem;
            border-top-right-radius: 0.5rem;
        }
        .result-image-container img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
        .status-badge {
            font-size: 1.25rem;
            font-weight: 600;
        }
        .status-badge.status-inside {
            color: var(--bs-success);
        }
        .status-badge.status-outside {
            color: var(--bs-danger);
        }
        .status-badge.status-pending {
            color: var(--bs-warning-text-emphasis);
        }
        .status-badge.status-error {
            color: var(--bs-danger);
        }

        /* --- Log Table Styles --- */
        .log-icon {
            font-size: 1.25rem;
        }
    </style>
</head>
<body>

<!-- Header (Fixed Top) -->
<header class="navbar navbar-expand navbar-light bg-white fixed-top shadow-sm">
    <div class="container-fluid">
        <!-- Logo -->
        <a class="navbar-brand" href="<?= base_url('guard/dashboard') ?>">
            <img src="<?= base_url('assets/images/logos/logo.svg') ?>" alt="Logo" height="30">
        </a>
        <!-- Centered Title -->
        <div class="header-title mx-auto">
            GUARD SCANNER
        </div>
        <!-- Right side elements - Profile Dropdown -->
        <ul class="navbar-nav ms-auto">
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                    <i class="ti ti-user-circle fs-3"></i>
                </a>
                <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                    <li class="dropdown-item-text">Hi, <?= esc($guardName) ?>!</li>
                    <li><hr class="dropdown-divider"></li>
                    <li><a class="dropdown-item" href="<?= route_to('guard_logout') ?>">Logout</a></li>
                </ul>
            </li>
        </ul>
    </div>
</header>

<!-- Main Content Area -->
<main class="main-content container mt-4 mb-4">

    <!-- Container for Scanner -->
    <div id="scanner-wrapper" class="text-center">
        <h5 class="mb-3">Scan Student's Item Pass</h5>
        <div id="scanner-container"></div>
        <div id="scanner-status" class="text-muted small mt-2">Initializing scanner...</div>
    </div>

    <!-- Container for Scan Result (Now a Confirmation Screen) -->
    <div id="scan-result-container">
        <div class="card result-card">
            <div class="result-image-container">
                <img id="result-item-image" src="" alt="Item Image"
                     onerror="this.onerror=null; this.src='https://placehold.co/500x250/eee/ccc?text=Image+Not+Found';">
            </div>
            <div class="card-body p-4 text-center">
                <!-- Status Message (Success/Error) -->
                <div id="result-status-message" class="status-badge mb-3"></div>

                <h5 class="card-title" id="result-item-name">Item Name</h5>
                <p class="text-muted mb-3" id="result-serial-number">Serial: ...</p>

                <hr>

                <p class="mb-1"><strong>Owner Details:</strong></p>
                <h6 class="mb-0" id="result-student-name">Student Name</h6>
                <p class="text-muted" id="result-student-id">Student ID</p>

                <!-- NEW Confirmation Buttons -->
                <div id="confirmation-buttons" class="d-grid gap-2 mt-4">
                    <button class="btn btn-primary btn-lg" id="confirm-scan-btn">
                        <!-- Spinner is now here, but will be replaced by the JS -->
                        <span class="spinner-border spinner-border-sm d-none" role="status" aria-hidden="true"></span>
                        <i class="ti ti-check me-1"></i> Confirm
                    </button>
                    <button class="btn btn-secondary" id="cancel-scan-btn">
                        <i class="ti ti-x me-1"></i> Cancel
                    </button>
                </div>

                <!-- This will be shown after confirmation -->
                <div id="scan-next-wrapper" class="d-grid mt-4 d-none">
                    <button class="btn btn-primary" id="scan-next-btn">
                        <i class="ti ti-scan me-1"></i> Scan Next Item
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- NEW: Recent Activity Log Table -->
    <div class="card shadow-sm mt-5">
        <div class="card-header">
            <h5 class="mb-0">Recent Activity</h5>
        </div>
        <div class="table-responsive">
            <table class="table table-striped table-hover mb-0">
                <thead>
                <tr>
                    <th scope="col">Status</th>
                    <th scope="col">Item</th>
                    <th scope="col">Student</th>
                    <th scope="col">Guard</th>
                    <th scope="col">Time</th>
                </tr>
                </thead>
                <tbody>
                <?php if (! empty($latestLogs)): ?>
                    <?php foreach ($latestLogs as $log): ?>
                        <tr>
                            <td class="text-center">
                                <?php if ($log['log_type'] === 'entry'): ?>
                                    <i class="ti ti-arrow-right text-success log-icon" title="Entry"></i>
                                <?php else: ?>
                                    <i class="ti ti-arrow-left text-danger log-icon" title="Exit"></i>
                                <?php endif; ?>
                            </td>
                            <td>
                                <!-- "V" TYPO IS FIXED HERE -->
                                <strong><?= esc($log['item_name']) ?></strong>
                                <br>
                                <small class="text-muted"><?= esc($log['serial_number']) ?></small>
                            </td>
                            <td><?= esc($log['student_name']) ?></td>
                            <td><?= esc($log['guard_name']) ?></td>
                            <td><?= date('M d, h:i A', strtotime(esc($log['timestamp']))) ?></td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="5" class="text-center text-muted p-4">No recent activity found.</td>
                    </tr>
                <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

</main>

<!-- Bootstrap JS Bundle -->
<script src="<?= base_url('assets/libs/bootstrap/dist/js/bootstrap.bundle.min.js') ?>"></script>
<!-- QR Code Scanner Library -->
<script src="https://unpkg.com/html5-qrcode@2.3.8/html5-qrcode.min.js" type="text/javascript"></script>

<script>
    document.addEventListener('DOMContentLoaded', function () {

        // --- Add CSRF variables ---
        const csrfTokenName = "<?= csrf_token() ?>"; // Gets the CSRF token NAME
        let csrfTokenHash = "<?= csrf_hash() ?>";   // Gets the initial CSRF HASH

        const scannerWrapper = document.getElementById('scanner-wrapper');
        const scannerStatus = document.getElementById('scanner-status');
        const resultContainer = document.getElementById('scan-result-container');

        const confirmBtn = document.getElementById('confirm-scan-btn');
        const cancelBtn = document.getElementById('cancel-scan-btn');
        const scanNextBtn = document.getElementById('scan-next-btn');
        const scanNextWrapper = document.getElementById('scan-next-wrapper');
        const confirmationButtons = document.getElementById('confirmation-buttons');

        let currentScannedItemId = null; // Store the item ID for confirmation

        // --- NEW: State variable based on your idea ---
        // This flag will control whether we process a scan or ignore it.
        // true = ignore scans, false = accept scans.
        let isProcessing = false;

        // --- Add CSRF update function ---
        function updateCsrfToken(newHash) {
            if (newHash) {
                csrfTokenHash = newHash;
            }
        }

        // --- Create the scanner ---
        const html5QrCode = new Html5Qrcode("scanner-container");

        const qrCodeSuccessCallback = (decodedText, decodedResult) => {

            // --- IMPLEMENTING YOUR IDEA ---
            // If we are already processing a scan, ignore all new ones.
            if (isProcessing) {
                return;
            }
            // --- END OF IDEA ---

            // --- 1. Lock the scanner ---
            isProcessing = true;

            // --- 2. We NO LONGER call .stop() or .clear() ---
            // The camera keeps running in the background. We just hide the UI.
            scannerStatus.innerHTML = `<strong>Scan successful.</strong> Fetching details...`;
            scannerWrapper.style.display = 'none';

            // --- 3. Show loading card ---
            showResultCard({
                message: "Fetching item details...",
                messageClass: "text-muted",
                itemName: "Loading...",
                serial: "Loading...",
                studentName: "Loading...",
                studentId: "Loading...",
                imageUrl: "https://placehold.co/500x250/eee/ccc?text=Loading"
            });

            // --- 4. Fetch details ---
            fetchItemDetails(decodedText);
        };

        const qrCodeErrorCallback = (errorMessage) => { /* Ignore errors */ };
        const config = { fps: 10, qrbox: { width: 250, height: 250 } };

        // --- NEW: This function just resets the UI and state ---
        // It no longer starts or stops the camera.
        function showScannerUI() {
            scannerWrapper.style.display = 'block';
            resultContainer.style.display = 'none';
            scannerStatus.innerHTML = 'Ready to scan.';
            currentScannedItemId = null;

            // --- "Unlock" the scanner so it can accept the next scan ---
            isProcessing = false;
        }

        // --- Step 1: Fetch Item Details ---
        async function fetchItemDetails(scannedData) {
            const formData = new FormData();
            formData.append('scanned_data', scannedData);
            formData.append(csrfTokenName, csrfTokenHash);

            try {
                const response = await fetch("<?= route_to('guard_fetch_item') ?>", {
                    method: 'POST',
                    body: formData,
                    headers: { 'X-Requested-With': 'XMLHttpRequest' }
                });

                let result;
                try {
                    result = await response.json();
                } catch (jsonError) {
                    console.error('Failed to parse JSON response:', jsonError, response);
                    showErrorResult("Critical Error: Server response was not valid. Please refresh.");
                    return; // Exit function
                }

                if (result.new_token) {
                    updateCsrfToken(result.new_token);
                }

                if (response.ok) {
                    currentScannedItemId = result.item.item_id; // Store item ID

                    let statusMsg = (result.item.current_status === 'outside')
                        ? 'Confirm Check-IN?'
                        : 'Confirm Check-OUT?';
                    let statusClass = 'status-pending';

                    showResultCard({
                        message: `<strong>${statusMsg}</strong>`,
                        messageClass: statusClass,
                        itemName: result.item.item_name,
                        serial: result.item.serial_number,
                        studentName: result.student.name,
                        studentId: result.student.school_id,
                        imageUrl: result.item.image_path
                    });

                    // --- THIS IS THE FIX ---
                    // We must re-create the spinner span AND the text.
                    confirmBtn.innerHTML = `
                        <span class="spinner-border spinner-border-sm d-none" role="status" aria-hidden="true"></span>
                        <i class="ti ti-check me-1"></i> ${statusMsg}
                    `;
                    // --- END OF FIX ---

                    // Show confirm/cancel buttons
                    confirmationButtons.classList.remove('d-none');
                    scanNextWrapper.classList.add('d-none');

                } else {
                    // Error from server (e.g., Not Found, Invalid Pass)
                    showErrorResult(result.message || "Item not found.");
                }

            } catch (error) {
                // This catches network errors (fetch itself failed)
                console.error('Fetch error:', error);
                showErrorResult("Network Error: Could not connect to server.");
            }
        }

        // --- Step 2: Confirm the Scan ---
        async function confirmScan() {
            if (!currentScannedItemId) return;

            const btnSpinner = confirmBtn.querySelector('.spinner-border');

            // --- Defensive check ---
            if (!btnSpinner) {
                console.error("Spinner not found in confirm button!");
                return; // Stop execution if spinner is missing
            }
            // --- End defensive check ---

            confirmBtn.disabled = true;
            cancelBtn.disabled = true;
            btnSpinner.classList.remove('d-none');

            const formData = new FormData();
            formData.append('item_id', currentScannedItemId);
            formData.append(csrfTokenName, csrfTokenHash);

            try {
                const response = await fetch("<?= route_to('guard_confirm_scan') ?>", {
                    method: 'POST',
                    body: formData,
                    headers: { 'X-Requested-With': 'XMLHttpRequest' }
                });

                let result;
                try {
                    result = await response.json();
                } catch (jsonError) {
                    console.error('Failed to parse JSON response:', jsonError, response);
                    document.getElementById('result-status-message').innerHTML = `<strong>Critical Error:</strong> Server response was not valid.`;
                    document.getElementById('result-status-message').className = 'status-badge status-error';
                    confirmationButtons.classList.add('d-none');
                    scanNextWrapper.classList.remove('d-none');
                    return; // Exit function
                }

                if (result.new_token) {
                    updateCsrfToken(result.new_token);
                }

                if (response.ok) {
                    // Success! Show final status
                    document.getElementById('result-status-message').innerHTML = `<strong>${result.message}</strong>`;
                    document.getElementById('result-status-message').className = 'status-badge ' + (result.new_status === 'inside' ? 'status-inside' : 'status-outside');
                } else {
                    // Error from server
                    document.getElementById('result-status-message').innerHTML = `<strong>Error:</strong> ${result.message}`;
                    document.getElementById('result-status-message').className = 'status-badge status-error';
                }

            } catch (error) {
                console.error('Confirm error:', error);
                document.getElementById('result-status-message').innerHTML = `<strong>Network Error:</strong> Could not confirm scan.`;
                document.getElementById('result-status-message').className = 'status-badge status-error';
            } finally {
                // This logic always runs, success or fail
                confirmationButtons.classList.add('d-none');
                scanNextWrapper.classList.remove('d-none');
                confirmBtn.disabled = false;
                cancelBtn.disabled = false;
                // We don't need to hide the spinner, as the whole button gets hidden
                currentScannedItemId = null; // Clear ID after attempt
            }
        }

        // --- Helper to show a full error card ---
        function showErrorResult(errorMessage) {
            showResultCard({
                message: `<strong>Error:</strong> ${errorMessage}`,
                messageClass: 'status-error',
                itemName: "Unknown Item",
                serial: "N/A",
                studentName: "N.A",
                studentId: "N/A",
                imageUrl: "https://placehold.co/500x250/dc3545/white?text=Error"
            });
            // Hide confirm/cancel, show "Scan Next"
            confirmationButtons.classList.add('d-none');
            scanNextWrapper.classList.remove('d-none');
        }

        // --- Function to populate the result card ---
        function showResultCard(data) {
            document.getElementById('result-status-message').innerHTML = data.message;
            document.getElementById('result-status-message').className = 'status-badge ' + data.messageClass;
            document.getElementById('result-item-name').textContent = data.itemName;
            document.getElementById('result-serial-number').textContent = `Serial: ${data.serial}`;
            document.getElementById('result-student-name').textContent = data.studentName;
            document.getElementById('result-student-id').textContent = data.studentId;
            document.getElementById('result-item-image').src = data.imageUrl;

            resultContainer.style.display = 'block';
        }

        // --- Button Listeners ---
        // Both "Cancel" and "Scan Next" now just reset the UI and "unlock" the scanner
        cancelBtn.addEventListener('click', showScannerUI);
        scanNextBtn.addEventListener('click', showScannerUI);

        confirmBtn.addEventListener('click', confirmScan);

        // --- NEW: Start the scanner ONCE on page load ---
        // We use an "async IIFE" (Immediately Invoked Function Expression)
        // to start the camera one time.
        (async () => {
            scannerStatus.innerHTML = 'Starting camera...';
            try {
                // This starts the camera and scanning.
                await html5QrCode.start(
                    { facingMode: "environment" },
                    config,
                    qrCodeSuccessCallback,
                    qrCodeErrorCallback
                );
                // Once it's successfully started, show the scanner UI.
                showScannerUI();
            } catch (err) {
                scannerStatus.innerHTML = `<strong>Error:</strong> Could not start camera. ${err}. Please refresh the page.`;
                console.error("Camera start error", err);
            }
        })();

        // We no longer call startScanner() here, as the function above handles it.

    });
</script>

</body>
</html>