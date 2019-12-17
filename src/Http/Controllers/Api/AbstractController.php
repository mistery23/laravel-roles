<?php

namespace Mistery23\LaravelRoles\Http\Controllers\Api;

use App\Http\Controllers\Controller;

/**
 * Class AbstractController
 */
abstract class AbstractController extends Controller
{

    /**
     * @var boolean
     */
    protected $rolesAPIAuthEnabled;

    /**
     * @var boolean
     */
    protected $rolesAPIMiddlewareEnabled;


    /**
     * AbstractController constructor.
     */
    public function __construct()
    {
        $this->rolesAPIAuthEnabled       = config('roles.rolesAPIAuthEnabled');
        $this->rolesAPIMiddlewareEnabled = config('roles.rolesAPIMiddlewareEnabled');

        if ($this->rolesAPIAuthEnabled) {
            $this->middleware('auth:api');
        }

        if ($this->rolesAPIMiddlewareEnabled) {
            $this->middleware(config('roles.rolesAPIMiddleware'));
        }
    }
}
