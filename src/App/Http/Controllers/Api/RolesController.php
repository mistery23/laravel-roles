<?php

namespace jeremykenedy\LaravelRoles\App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use jeremykenedy\LaravelRoles\App\Http\Requests;
use jeremykenedy\LaravelRoles\Model\ReadModels\RoleQueriesInterface;
use jeremykenedy\LaravelRoles\Model\UseCases\Role;

class RolesController extends Controller
{

    private $queries;


    public function __construct(RoleQueriesInterface $queries)
    {
        $this->queries = $queries;
    }

    /**
     * Return all the roles, Permissions, and Users data.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $roles = $this->queries->getAll();

        return response()->json([
            'code'      => 200,
            'status'    => 'success',
            'message'   => 'Success returning all roles',
            'data'      => $roles,
        ], 200);
    }

    /**
     * Creating a new role.
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

        try {
            $handler->handle($command);
        } catch (\RuntimeException $e) {
            return response()
                ->json(['error' => 'Data not valided'], 400);
        }

        $role = $this->queries->getBySlug($command->slug);

        return response()->json([
            'code'      => 201,
            'status'    => 'created',
            'message'   => 'Success created new role.',
            'data'      => [
                'id'          => $role->id,
                'name'        => $role->name,
                'slug'        => $role->slug,
                'level'       => $role->level,
                'description' => $role->description,
                'created_at'  => $role->created_at,
                'updated_at'  => $role->updated_at,
            ],
        ], 201);
    }

    /**
     * Edit a new role.
     *
     * @return \Illuminate\Http\Response
     */
    public function edit(string $roleId, Requests\Role\UpdateRoleRequest $request, Role\Edit\Handler $handler)
    {
        $command = new Role\Edit\Command(
            $roleId,
            $request->get('name'),
            $request->get('slug'),
            $request->get('level'),
            $request->get('description'),
        );

        try {
            $handler->handle($command);
        } catch (\RuntimeException $e) {
            return response()
                ->json(['error' => $e->getMessage()], 400);
        }

        $editedRole = $this->queries->getById($command->id);

        return response()->json([
            'code'      => 200,
            'status'    => 'OK',
            'message'   => 'Success edited ' . $editedRole->slug . ' role.',
            'data'      => [
                'id'          => $editedRole->id,
                'name'        => $editedRole->name,
                'slug'        => $editedRole->slug,
                'level'       => $editedRole->level,
                'description' => $editedRole->description,
                'created_at'  => $editedRole->created_at,
                'updated_at'  => $editedRole->updated_at,
            ],
        ], 200);
    }

    /**
     * Edit a new role.
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy(string $roleId, Role\Remove\Handler $handler)
    {
        $command = new Role\Remove\Command($roleId);

        try {
            $handler->handle($command);
        } catch (\RuntimeException $e) {
            return response()
                ->json(['error' => $e->getMessage()], 400);
        }

        return response()->json([
            'code'      => 200,
            'status'    => 'OK',
            'message'   => 'Deleted success role.',
        ], 200);
    }
}
