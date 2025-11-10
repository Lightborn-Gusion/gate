<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

// Admin Routes (existing)
// NOTE: You should also apply auth/guest filters to these
$routes->get('/', 'Admin\Dashboard::index');
$routes->get('/reports/daily', 'Admin\DailyReports::index');
$routes->get('/management/visitors', 'Admin\PassManagement::index');
$routes->get('/management/blacklist', 'Admin\BlacklistManagement::index');


// --- Student Routes ---
$routes->group('student', static function ($routes) {

    // --- PUBLIC ROUTES (For Logged-OUT Students) ---
    // UPDATED: Apply the 'student-guest' filter to this group
    $routes->group('', ['filter' => 'student-guest'], static function ($routes) {
        $routes->get('login', 'Student\LoginController::index', ['as' => 'student_login']);
        $routes->post('login/process', 'Student\LoginController::process', ['as' => 'student_login_process']);
        $routes->get('register', 'Student\RegisterController::index', ['as' => 'student_register']);
        $routes->post('register/process', 'Student\RegisterController::process', ['as' => 'student_register_process']);
        $routes->get('forgot-password', 'Student\PasswordController::forgot', ['as' => 'student_forgot_password']);
        $routes->post('password/forgot-process', 'Student\PasswordController::processForgot', ['as' => 'student_forgot_process']);
        $routes->get('password/reset', 'Student\PasswordController::reset', ['as' => 'student_reset_password']);
        $routes->post('password/reset-process', 'Student\PasswordController::processReset', ['as' => 'student_reset_process']);
    });
    // --- END PUBLIC ROUTES ---


    // --- PROTECTED STUDENT ROUTES (For Logged-IN Students) ---
    $routes->group('', ['filter' => 'student-auth'], static function ($routes) {
        $routes->get('logout', 'Student\LoginController::logout', ['as' => 'student_logout']);
        $routes->get('dashboard', 'Student\DashboardController::index', ['as' => 'student_dashboard']);
        $routes->post('items/create', 'Student\ItemController::create', ['as' => 'student_item_create']);
        $routes->post('items/delete', 'Student\ItemController::delete', ['as' => 'student_item_delete']);
        $routes->get('items/get/(:num)', 'Student\ItemController::getItem/$1', ['as' => 'student_item_get']);
        $routes->post('items/update', 'Student\ItemController::update', ['as' => 'student_item_update']);
        $routes->post('items/report', 'Student\ItemController::report', ['as' => 'student_item_report']);
        $routes->post('items/found', 'Student\ItemController::markAsFound', ['as' => 'student_item_found']);
        $routes->get('profile', 'Student\ProfileController::index', ['as' => 'student_profile']);
        $routes->post('profile/update-details', 'Student\ProfileController::updateDetails', ['as' => 'student_profile_update_details']);
        $routes->post('profile/update-password', 'Student\ProfileController::updatePassword', ['as' => 'student_profile_update_password']);
    });
    // --- END PROTECTED ROUTES ---
});
// --- END Student Routes ---


// --- GUARD ROUTES ---
$routes->group('guard', static function ($routes) {

    // --- PUBLIC ROUTES (For Logged-OUT Guards) ---
    // UPDATED: Apply the 'guard-guest' filter to this group
    $routes->group('', ['filter' => 'guard-guest'], static function ($routes) {
        $routes->get('login', 'Guard\LoginController::index', ['as' => 'guard_login']);
        $routes->post('login/process', 'Guard\LoginController::process', ['as' => 'guard_login_process']);
        $routes->get('register', 'Guard\RegisterController::index', ['as' => 'guard_register']);
        $routes->post('register/process', 'Guard\RegisterController::process', ['as' => 'guard_register_process']);
    });
    // --- END PUBLIC ROUTES ---


    // --- PROTECTED GUARD ROUTES (For Logged-IN Guards) ---
    $routes->group('', ['filter' => 'guard-auth'], static function ($routes) {
        $routes->get('logout', 'Guard\LoginController::logout', ['as' => 'guard_logout']);
        $routes->get('dashboard', 'Guard\DashboardController::index', ['as' => 'guard_dashboard']);
        $routes->post('fetch-item', 'Guard\DashboardController::fetchItemDetails', ['as' => 'guard_fetch_item']);
        $routes->post('confirm-scan', 'Guard\DashboardController::confirmScan', ['as' => 'guard_confirm_scan']);
    });
    // --- END PROTECTED ROUTES ---
});
// --- END GUARD ROUTES ---