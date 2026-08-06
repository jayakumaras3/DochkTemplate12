<?php

namespace Config;

use CodeIgniter\Config\BaseConfig;
use CodeIgniter\Filters\CSRF;
use CodeIgniter\Filters\DebugToolbar;
use CodeIgniter\Filters\Honeypot;
use CodeIgniter\Filters\InvalidChars;
use CodeIgniter\Filters\SecureHeaders;

class Filters extends BaseConfig
{
    /**
     * Configures aliases for Filter classes to
     * make reading things nicer and simpler.
     *
     * @var array<string, array<int, string>|string> [filter_name => classname]
     *                                               or [filter_name => [classname1, classname2, ...]]
     * @phpstan-var array<string, class-string|list<class-string>>
     */
    public array $aliases = [
        'csrf'          => CSRF::class,
        'toolbar'       => DebugToolbar::class,
        'honeypot'      => Honeypot::class,
        'invalidchars'  => InvalidChars::class,
        'secureheaders' => SecureHeaders::class,
        'auth' => \App\Filters\Auth::class,
        'noauth' => \App\Filters\Noauth::class,
        'logincheck' => \App\Filters\Logincheck::class,
        'role' => \App\Filters\RoleFilter::class,
        'csrf' => \CodeIgniter\Filters\CSRF::class,
        'sessionversion' => \App\Filters\SessionVersionFilter::class,
        'nocache' => \App\Filters\NoCache::class,

    ];

    /**
     * List of filter aliases that are always
     * applied before and after every request.
     *
     * @var array<string, array<string, array<string, string>>>|array<string, array<string>>
     * @phpstan-var array<string, list<string>>|array<string, array<string, array<string, string>>>
     */
    public array $globals = [
        'before' => [
            'auth' => ['except' => ['/', 'privacy', 'captcha', 'test', 'privacy/*', 'quickaccess', 'quickaccess/*', 'quickaccess/checkAccess', 'usergraph', 'Demo/demos', 'Demo/demos/*', 'project_manage', 'project_manage/*', 'category_schedule', 'meeting_agenda_client', 'userprojectplan', 'forgot_password', 'forgot_password/reset_password', 'forgot_password/reset_password/*', '/Emanual/emanual_link/*', 'API', '/API/*', 'API/APIaccess', 'Landing', 'Landing/*', '/login', 'Landing_dochek', 'Landing_dochek/*', 'Captcha', 'userprojectplan/getprojectplan', 'userprojectplan/*', '/usergraph/projectplanGraph', '/usergraph/*', '/Cron/Send_mail_cron', '/Others/Tour_user', '/Others/Tour_user/scores', '/Cron/Send_mail_cron/*','activate-account']],
            'logincheck',
            'sessionversion' => [
                'except' => [
                    'landing/*',
                    'Landing/login_register',
                    'Landing/authStatus',
                    'ang/login',
                    'login',
                    'login/*',
                    'ang/authentication/login',
                    'Landing_dochek/login_register',
                    'Open/Login_forgot_password/forgotpasswordVerify',
                    'PasswordReset/reset_password','forgot_password/reset_password/*',
                    'ang/authentication/login',
                    'api/*',
                    '/',
                    'ang/forgotpassword',
                     'API/*','forgot_password','/api/forgot_password','Landing_dochek,Landing/signup','activate-account'    // Allow API and home page without session version check
                ]
            ],
            // 'csrf' => ['except' => ['api/*', 'Landing/*','SCORM/Course_builder/review_course/launcher', 'save-time',
            // 'update-time']], // Enable CSRF globally except certain APIs 



        ],
        'after'  => [
            // 'auth' =>['except' => ['privacy', 'privacy/*']],
            'toolbar',
            //'honeypot'
            // Prevents the browser from replaying a stale cached response (e.g. the
            // "Authentication required"/expired JSON from sessionversion, or a page
            // rendered before session/session_version changed) on refresh or back/forward
            // navigation instead of hitting the server again.
            'nocache',
        ],
    ];
    public array $csrfExcept = [
        'stripewebhook'
    ];


    /**
     * List of filter aliases that works on a
     * particular HTTP method (GET, POST, etc.).
     *
     * Example:
     * 'post' => ['foo', 'bar']
     *
     * If you use this, you should disable auto-routing because auto-routing
     * permits any HTTP method to access a controller. Accessing the controller
     * with a method you don't expect could bypass the filter.
     */
    public array $methods = [];

    /**
     * List of filter aliases that should run on any
     * before or after URI patterns.
     *
     * Example:
     * 'isLoggedIn' => ['before' => ['account/*', 'profiles/*']]
     */
    public array $filters = [];
}
