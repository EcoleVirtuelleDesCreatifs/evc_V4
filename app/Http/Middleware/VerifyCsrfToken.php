<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as Middleware;

class VerifyCsrfToken extends Middleware
{
    /**
     * The URIs that should be excluded from CSRF verification.
     *
     * @var array<int, string>
     */
    protected $except = [
        'auth/register-no-csrf',
        'auth/evc/login',
        'evc/app/admin/login',
        'pre-registration',
        'candidature',
        'evc/pre-registration',
        'evc/candidature',
    ];
}
