<?php

namespace Config;

use CodeIgniter\Config\BaseConfig;
use CodeIgniter\Filters\CSRF;
use CodeIgniter\Filters\DebugToolbar;
use CodeIgniter\Filters\Honeypot;
use Modules\Auth\Filters\AuthFilter;
use Modules\Auth\Filters\AuthJwtFilter;
use Modules\Auth\Filters\CsrfFilter;
use Modules\Auth\Filters\IsSignInFilter;
use Modules\Auth\Filters\ThrottleFilter;
use Modules\Common\Filters\ContentNegotiationFilter;
use Modules\Common\Filters\CorsFilter;
use Modules\Common\Filters\DataInputFilter;
use Modules\Common\Filters\UrlFilter;

class Filters extends BaseConfig
{
    /**
     * Configures aliases for Filter classes to
     * make reading things nicer and simpler.
     *
     * @var array
     */
    public $aliases = [
        //'csrf'     => CSRF::class,
        'toolbar'  => DebugToolbar::class,
        'honeypot' => Honeypot::class,
        'csrf' => CsrfFilter::class,
        'cors' => CorsFilter::class,
        'auth' => AuthFilter::class,
        'authJwt' => AuthJwtFilter::class,
        'isSignIn' => IsSignInFilter::class,
        'url' => UrlFilter::class,
        'throttle' => ThrottleFilter::class,
        'contentNegotiation' => ContentNegotiationFilter::class,
        'dataInput' => DataInputFilter::class
    ];

    /**
     * List of filter aliases that are always
     * applied before and after every request.
     *
     * @var array
     */
    public $globals = [
        'before' => [
            // 'honeypot',
            // 'csrf',
            'cors',
            'url',
            'contentNegotiation'
        ],
        'after' => [
            'toolbar',
            // 'honeypot',
        ],
    ];

    /**
     * List of filter aliases that works on a
     * particular HTTP method (GET, POST, etc.).
     *
     * Example:
     * 'post' => ['csrf', 'throttle']
     *
     * @var array
     */
    public $methods = [

        //  'get' => ['csrf'],
        'post' => ['dataInput'],
        'put' => ['dataInput'],
        // 'delete' => ['csrf']

    ];

    /**
     * List of filter aliases that should run on any
     * before or after URI patterns.
     *
     * Example:
     * 'isLoggedIn' => ['before' => ['account/*', 'profiles/*']]
     *
     * @var array
     */
    public $filters = [];
}
