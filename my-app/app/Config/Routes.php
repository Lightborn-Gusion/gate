<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Admin\Dashboard::index');
$routes->get('/reports/daily', 'Admin\DailyReports::index');
$routes->get('/management/visitors', 'Admin\PassManagement::index');
$routes->get('/management/blacklist', 'Admin\BlacklistManagement::index');