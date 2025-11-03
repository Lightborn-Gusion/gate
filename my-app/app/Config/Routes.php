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

    // Forgot Password (Placeholders)
    $routes->get('forgot-password', 'Student\PasswordController::forgot', ['as' => 'student_forgot_password']);

    // Dashboard Route
    $routes->get('dashboard', 'Student\DashboardController::index', ['as' => 'student_dashboard']);

    // Item Registration
    $routes->post('items/create', 'Student\ItemController::create', ['as' => 'student_item_create']);

    // Item Deletion
    $routes->post('items/delete', 'Student\ItemController::delete', ['as' => 'student_item_delete']);

    // Item Edit Routes
    $routes->get('items/get/(:num)', 'Student\ItemController::getItem/$1', ['as' => 'student_item_get']);
    $routes->post('items/update', 'Student\ItemController::update', ['as' => 'student_item_update']);

    // --- NEW Profile Routes ---
    $routes->get('profile', 'Student\ProfileController::index', ['as' => 'student_profile']);
    $routes->post('profile/update-details', 'Student\ProfileController::updateDetails', ['as' => 'student_profile_update_details']);
    $routes->post('profile/update-password', 'Student\ProfileController::updatePassword', ['as' => 'student_profile_update_password']);
    // --- END NEW Profile Routes ---
});