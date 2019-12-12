<?php

namespace Mistery23\LaravelRoles\App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Mistery23\LaravelRoles\App\Http\Requests;
use Mistery23\LaravelRoles\Model\ReadModels\PermissionQueriesInterface;
use Mistery23\LaravelRoles\Model\UseCases\Permission;

class PermissionsController extends Controller
{

    /**
     * @var PermissionQueriesInterface
     */
    private $queries;


    /**
     * PermissionsController constructor.
     *
     * @param PermissionQueriesInterface $queries
     */
    public function __construct(PermissionQueriesInterface $queries)
    {
        $this->queries = $queries;
    }

    /**
     * Return all the roles, Permissions, and Users data.
     *
     * @return \Illuminate\Http\JsonResponse
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
     * @param Requests\Permission\CreatePermissionRequest $request
     * @param Permission\Create\Handler $handler
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Requests\Permission\CreatePermissionRequest $request, Permission\Create\Handler $handler)
    {
        $command = new Permission\Create\Command(
            $request->get('name'),
            $request->get('slug'),
            $request->get('model'),
            $request->get('parentId'),
            $request->get('description'),
        );

        try {
            $handler->handle($command);
        } catch (\RuntimeException $e) {
            return response()
                ->json(['error' => 'Data not valided'], 400);
        }

        $permission = $this->queries->getBySlug($command->slug);

        return response()->json([
            'code'      => 201,
            'status'    => 'created',
            'message'   => 'Success created new permission.',
            'data'      => [
                'id'          => $permission->id,
                'name'        => $permission->name,
                'slug'        => $permission->slug,
                'model'       => $permission->model,
                'parent_id'   => $permission->parent_id,
                'description' => $permission->description,
                'created_at'  => $permission->created_at,
                'updated_at'  => $permission->updated_at,
            ],
        ], 201);
    }


    /**
     * @param string $permissionId
     * @param Requests\Permission\UpdatePermissionRequest $request
     * @param Permission\Edit\Handler $handler
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function edit(
        string $permissionId,
        Requests\Permission\UpdatePermissionRequest $request,
        Permission\Edit\Handler $handler
    ) {
        $command = new Permission\Edit\Command(
            $permissionId,
            $request->get('name'),
            $request->get('slug'),
            $request->get('model'),
            $request->get('parent_id'),
            $request->get('description'),
        );

        try {
            $handler->handle($command);
        } catch (\RuntimeException $e) {
            return response()
                ->json(['error' => $e->getMessage()], 400);
        }

        $editedPermission = $this->queries->getById($command->id);

        return response()->json([
            'code'      => 200,
            'status'    => 'OK',
            'message'   => 'Success edited ' . $editedPermission->slug . ' permission.',
            'data'      => [
                'id'          => $editedPermission->id,
                'name'        => $editedPermission->name,
                'slug'        => $editedPermission->slug,
                'model'       => $editedPermission->model,
                'parent_id'   => $editedPermission->parent_id,
                'description' => $editedPermission->description,
                'created_at'  => $editedPermission->created_at,
                'updated_at'  => $editedPermission->updated_at,
            ],
        ], 200);
    }

    /**
     * Destroy a permission.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(string $permissionId, Permission\Remove\Handler $handler)
    {
        $command = new Permission\Remove\Command($permissionId);

        try {
            $handler->handle($command);
        } catch (\RuntimeException $e) {
            return response()
                ->json(['error' => $e->getMessage()], 400);
        }

        return response()->json([
            'code'      => 200,
            'status'    => 'OK',
            'message'   => 'Deleted success permission.',
        ], 200);
    }

    public function doChild(
        string $permissionId,
        Requests\Permission\DoChildPermissionRequest $request,
        Permission\DoChild\Handler $handler
    ) {
        $command = new Permission\DoChild\Command(
            $permissionId,
            $request->get('parent_id')
        );

        try {
            $handler->handle($command);
        } catch (\Exception $e) {
            return response()
                ->json(['error' => $e->getMessage()], 400);
        }

        return response()->json([
            'code'      => 200,
            'status'    => 'OK',
            'message'   => 'Permission was set childlike.',
        ], 200);
    }

    public function doRoot(
        string $permissionId,
        Permission\DoRoot\Handler $handler

    ) {
        $command = new Permission\DoRoot\Command(
            $permissionId,
        );

        try {
            $handler->handle($command);
        } catch (\Exception $e) {
            return response()
                ->json(['error' => $e->getMessage()], 400);
        }

        return response()->json([
            'code'      => 200,
            'status'    => 'OK',
            'message'   => 'Permission was set root.',
        ], 200);
    }
}
