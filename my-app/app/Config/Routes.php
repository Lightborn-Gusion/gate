<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

// Admin Routes (existing)
$routes->get('/', 'Admin\Dashboard::index');
$routes->get('/reports/daily', 'Admin\DailyReports::index');
$routes->get('/management/visitors', 'Admin\PassManagement::index');
$routes->get('/management/blacklist', 'Admin\BlacklistManagement::index');


// --- Student Routes ---
$routes->group('student', static function ($routes) {
    // Login Routes
    $routes->get('login', 'Student\LoginController::index', ['as' => 'student_login']);
    $routes->post('login/process', 'Student\LoginController::process', ['as' => 'student_login_process']);
    $routes->get('logout', 'Student\LoginController::logout', ['as' => 'student_logout']);
    // Registration Routes
    $routes->get('register', 'Student\RegisterController::index', ['as' => 'student_register']);
    $routes->post('register/process', 'Student\RegisterController::process', ['as' => 'student_register_process']);

    // --- UPDATED: Password Reset Routes ---
    $routes->get('forgot-password', 'Student\PasswordController::forgot', ['as' => 'student_forgot_password']);
    $routes->post('password/forgot-process', 'Student\PasswordController::processForgot', ['as' => 'student_forgot_process']);
    // --- THIS LINE IS UPDATED: Removed (:hash) and /$1 ---
    $routes->get('password/reset', 'Student\PasswordController::reset', ['as' => 'student_reset_password']);
    $routes->post('password/reset-process', 'Student\PasswordController::processReset', ['as' => 'student_reset_process']);
    // --- END UPDATED ---

    // Dashboard Route
    $routes->get('dashboard', 'Student\DashboardController::index', ['as' => 'student_dashboard']);
    // Item Registration
    $routes->post('items/create', 'Student\ItemController::create', ['as' => 'student_item_create']);
    // Item Deletion
    $routes->post('items/delete', 'Student\ItemController::delete', ['as' => 'student_item_delete']);
    // Item Edit Routes
    $routes->get('items/get/(:num)', 'Student\ItemController::getItem/$1', ['as' => 'student_item_get']);
    $routes->post('items/update', 'Student\ItemController::update', ['as' => 'student_item_update']);
    // Item Report
    $routes->post('items/report', 'Student\ItemController::report', ['as' => 'student_item_report']);
    $routes->post('items/found', 'Student\ItemController::markAsFound', ['as' => 'student_item_found']);
    // Profile Routes
    $routes->get('profile', 'Student\ProfileController::index', ['as' => 'student_profile']);
    $routes->post('profile/update-details', 'Student\ProfileController::updateDetails', ['as' => 'student_profile_update_details']);
    $routes->post('profile/update-password', 'Student\ProfileController::updatePassword', ['as' => 'student_profile_update_password']);
});

// --- GUARD ROUTES ---
$routes->group('guard', static function ($routes) {
    // Dashboard Route
    $routes->get('dashboard', 'Guard\DashboardController::index', ['as' => 'guard_dashboard']);
    // Login Routes
    $routes->get('login', 'Guard\LoginController::index', ['as' => 'guard_login']);
    $routes->post('login/process', 'Guard\LoginController::process', ['as' => 'guard_login_process']);
    $routes->get('logout', 'Guard\LoginController::logout', ['as' => 'guard_logout']);
    // Registration Routes
    $routes->get('register', 'Guard\RegisterController::index', ['as' => 'guard_register']);
    $routes->post('register/process', 'Guard\RegisterController::process', ['as' => 'guard_register_process']);

    // --- UPDATED SCANNER ROUTES ---
    // Step 1: Scan the pass, fetch item details
    $routes->post('fetch-item', 'Guard\DashboardController::fetchItemDetails', ['as' => 'guard_fetch_item']);
    // Step 2: Guard confirms, log the item in/out
    $routes->post('confirm-scan', 'Guard\DashboardController::confirmScan', ['as' => 'guard_confirm_scan']);
    // --- END UPDATED ---
});
// --- END GUARD ROUTES ---