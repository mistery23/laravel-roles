<?php

namespace jeremykenedy\LaravelRoles\App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use jeremykenedy\LaravelRoles\App\Http\Requests;
use jeremykenedy\LaravelRoles\App\Http\Requests\UpdateRoleRequest;
use jeremykenedy\LaravelRoles\App\Services\RoleFormFields;
use jeremykenedy\LaravelRoles\Model\ReadModels\RoleQueriesInterface;
use jeremykenedy\LaravelRoles\Model\UseCases\Role;
use jeremykenedy\LaravelRoles\Traits\RolesAndPermissionsHelpersTrait;

class LaravelRolesController extends Controller
{
    use RolesAndPermissionsHelpersTrait;
    //use RolesUsageAuthTrait;

    private $queries;

    public function __construct(RoleQueriesInterface $queries)
    {
        $this->queries = $queries;
    }

    /**
     * Show the roles and Permissions dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $roles = $this->queries->getAll();

        return view('laravelroles::laravelroles.role.index', compact('roles'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('laravelroles::laravelroles.role.create');
    }

    /**
     * Store a newly created role in storage.
     *
     * @param Requests\Role\CreateRoleRequest $request
     * @param Role\Create\Handler             $handler
     *
     * @return \Illuminate\Http\Response
     */
    public function store(Requests\Role\CreateRoleRequest $request, Role\Create\Handler $handler)
    {
        $command = new Role\Create\Command(
            $request->get('name'),
            $request->get('slug'),
            $request->get('level'),
            $request->get('description'),
        );

        $handler->handle($command);

        return redirect()
                    ->route('laravelroles::roles.index')
                    ->with('success', trans('laravelroles::laravelroles.flash-messages.role-create', ['role' => $command->name]));
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
        $item = $this->getRole($id);

        return view('laravelroles::laravelroles.crud.roles.show', compact('item'));
    }

    /**
     * Edit the specified resource.
     *
     * @param int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, $id)
    {
        $service = new RoleFormFields($id);
        $data = $service->handle();

        return view('laravelroles::laravelroles.crud.roles.edit', $data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \jeremykenedy\LaravelRoles\App\Http\Requests\UpdateRoleRequest $request
     * @param int                                                            $id
     *
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateRoleRequest $request, $id)
    {
        $roleData = $request->roleFillData();
        $rolePermissions = $request->get('permissions');
        $role = $this->updateRoleWithPermissions($id, $roleData, $rolePermissions);

        return redirect()->route('laravelroles::roles.index')
            ->with('success', trans('laravelroles::laravelroles.flash-messages.role-updated', ['role' => $role->name]));
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
        $role = $this->deleteRole($id);

        return redirect(route('laravelroles::roles.index'))
                    ->with('success', trans('laravelroles::laravelroles.flash-messages.successDeletedItem', ['type' => 'Role', 'item' => $role->name]));
    }
}
