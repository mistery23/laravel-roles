<?php

namespace jeremykenedy\LaravelRoles\App\Http\Controllers;

use App\Http\Controllers\Controller;
use jeremykenedy\LaravelRoles\Traits\RolesAndPermissionsHelpersTrait;

class LaravelPermissionsController extends Controller
{
    use RolesAndPermissionsHelpersTrait;

    private $_rolesGuiAuthEnabled;
    private $_rolesGuiMiddlewareEnabled;
    private $_rolesGuiMiddleware;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->_rolesGuiAuthEnabled         = config('roles.rolesGuiAuthEnabled');
        $this->_rolesGuiMiddlewareEnabled   = config('roles.rolesGuiMiddlewareEnabled');
        $this->_rolesGuiMiddleware          = config('roles.rolesGuiMiddleware');

        if ($this->_rolesGuiAuthEnabled) {
            $this->middleware('auth');
        }

        if ($this->_rolesGuiMiddlewareEnabled) {
            $this->middleware($this->_rolesGuiMiddleware);
        }
    }

    /**
     * Show the roles and Permissions dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = $this->getDashboardData();

        return view($data['view'], $data['data']);
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $data = $this->getPermissionItemData($id);

        return view('laravelroles::laravelroles.crud.permissions.show', $data);
    }

    /**
     * Edit the specified resource.
     *
     * @param int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $data = $this->getPermissionItemData($id);

        return view('laravelroles::laravelroles.crud.permissions.edit', $data);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $permission = config('roles.models.permission')::findOrFail($id);
        $this->removeUsersAndRolesFromPermissions($permission);
        $permission->delete();

        return redirect(route('laravelroles::roles.index'))
                    ->with('success', trans('laravelroles::laravelroles.flash-messages.successDeletedItem', ['type' => 'Permission', 'item' => $permission->name]));
    }
}