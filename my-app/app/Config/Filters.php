<?php

namespace Config;

use CodeIgniter\Config\Filters as BaseFilters;
use CodeIgniter\Filters\Cors;
use CodeIgniter\Filters\CSRF;
use CodeIgniter\Filters\DebugToolbar;
use CodeIgniter\Filters\ForceHTTPS;
use CodeIgniter\Filters\Honeypot;
use CodeIgniter\Filters\InvalidChars;
use CodeIgniter\Filters\PageCache;
use CodeIgniter\Filters\PerformanceMetrics;
use CodeIgniter\Filters\SecureHeaders;
// --- ADD SHIELD FILTERS (if you still use them for admin) ---
use CodeIgniter\Shield\Filters\AuthRates;
use CodeIgniter\Shield\Filters\ChainAuth;
use CodeIgniter\Shield\Filters\GuestFilter as ShieldGuest;
use CodeIgniter\Shield\Filters\SessionAuth;
use CodeIgniter\Shield\Filters\TokenAuth;
// --- ADD OUR CUSTOM FILTERS ---
use App\Filters\StudentAuth;
use App\Filters\GuardAuth;
use App\Filters\StudentGuest; // <-- ADD THIS
use App\Filters\GuardGuest;   // <-- ADD THIS

class Filters extends BaseFilters
{
    /**
     * Configures aliases for Filter classes.
     */
    public array $aliases = [
        'csrf'          => CSRF::class,
        'toolbar'       => DebugToolbar::class,
        'honeypot'      => Honeypot::class,
        'invalidchars'  => InvalidChars::class,
        'secureheaders' => SecureHeaders::class,
        'cors'          => Cors::class,
        'forcehttps'    => ForceHTTPS::class,
        'pagecache'     => PageCache::class,
        'performance'   => PerformanceMetrics::class,
        // --- SHIELD ALIASES ---
        'session'       => SessionAuth::class,
        'tokens'        => TokenAuth::class,
        'chain'         => ChainAuth::class,
        'auth-rates'    => AuthRates::class,
        'guest'         => ShieldGuest::class,
        // --- OUR CUSTOM AUTH ALIASES ---
        'student-auth'  => StudentAuth::class,
        'guard-auth'    => GuardAuth::class,
        // --- OUR NEW GUEST ALIASES ---
        'student-guest' => StudentGuest::class, // <-- ADD THIS
        'guard-guest'   => GuardGuest::class,   // <-- ADD THIS
    ];

    /**
     * List of special required filters.
     */
    public array $required = [
        'before' => [
            'forcehttps',
            'pagecache',
        ],
        'after' => [
            'pagecache',
            'performance',
            'toolbar',
        ],
    ];

    /**
     * List of filter aliases that are always
     * applied before and after every request.
     */
    public array $globals = [
        'before' => [
            // 'honeypot',
            // 'csrf',
            // 'invalidchars',
        ],
        'after' => [
            // 'honeypot',
            // 'secureheaders',
        ],
    ];

    /**
     * List of filter aliases that works on a
     * particular HTTP method (GET, POST, etc.).
     */
    public array $methods = [];

    /**
     * List of filter aliases that should run on any
     * before or after URI patterns.
     */
    public array $filters = [];
}