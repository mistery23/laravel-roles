<?php

namespace jeremykenedy\LaravelRoles\App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use jeremykenedy\LaravelRoles\App\Http\Requests;
use jeremykenedy\LaravelRoles\Contracts\UserQueriesInterface;
use jeremykenedy\LaravelRoles\Model\UseCases\User;

class UsersController extends Controller
{

    private $queries;


    public function __construct(UserQueriesInterface $queries)
    {
        $this->queries = $queries;
    }

    /**
     * @param string $userId
     * @param Requests\User\AttachRoleRequest $request
     * @param User\Attach\Role\Handler $handler
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function attachRole(
        string $userId,
        Requests\User\AttachRoleRequest $request,
        User\Attach\Role\Handler $handler
    ) {

        $command = new User\Attach\Role\Command(
            $userId,
            $request->get('role_id')
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
            'message'   => 'Role attached.',
        ], 200);
    }

    /**
     * @param string $userId
     * @param Requests\User\DetachRoleRequest $request
     * @param User\Detach\Role\Handler $handler
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function detachRole(
        string $userId,
        Requests\User\DetachRoleRequest $request,
        User\Detach\Role\Handler $handler
    ) {

        $command = new User\Detach\Role\Command(
            $userId,
            $request->get('role_id')
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
            'message'   => 'Role detached.',
        ], 200);
    }

    /**
     * @param string $userId
     * @param Requests\User\AttachPermissionRequest $request
     * @param User\Attach\Permission\Handler $handler
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function attachPermission(
        string $userId,
        Requests\User\AttachPermissionRequest $request,
        User\Attach\Permission\Handler $handler
    ) {

        $command = new User\Attach\Permission\Command(
            $userId,
            $request->get('permission_id')
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
            'message'   => 'Permission attached.',
        ], 200);
    }

    /**
     * @param string $userId
     * @param Requests\User\DetachPermissionRequest $request
     * @param User\Detach\Permission\Handler $handler
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function detachPermission(
        string $userId,
        Requests\User\DetachPermissionRequest $request,
        User\Detach\Permission\Handler $handler
    ) {

        $command = new User\Detach\Permission\Command(
            $userId,
            $request->get('permission_id')
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
            'message'   => 'Permission detached.',
        ], 200);
    }
}
