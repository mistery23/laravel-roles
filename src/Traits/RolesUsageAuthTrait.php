<?php

namespace jeremykenedy\LaravelRoles\Traits;

trait RolesUsageAuthTrait
{
    /**
     * Variable to hold if we are using built in Laravel authentication.
     */
    private $rolesGuiAuthEnabled;

    /**
     * Variable to hold if we are using roles/permissoins middleware for access.
     */
    private $rolesGuiMiddlewareEnabled;

    /**
     * Variable to hold what roles/permissions middleware we are using if enabled.
     */
    private $rolesGuiMiddleware;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->rolesGuiAuthEnabled = config('roles.rolesGuiAuthEnabled');
        $this->rolesGuiMiddlewareEnabled = config('roles.rolesGuiMiddlewareEnabled');
        $this->rolesGuiMiddleware = config('roles.rolesGuiMiddleware');

        if ($this->rolesGuiAuthEnabled) {
            $this->middleware('auth');
        }

        if ($this->rolesGuiMiddlewareEnabled) {
            $this->middleware($this->rolesGuiMiddleware);
        }
    }
}
