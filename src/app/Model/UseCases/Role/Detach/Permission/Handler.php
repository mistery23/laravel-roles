<?php

declare(strict_types=1);

namespace Mistery23\LaravelRoles\Model\UseCases\Role\Detach\Permission;

use Illuminate\Support\Facades\DB;
use Mistery23\LaravelRoles\Model\Entity\Role;
use Mistery23\LaravelRoles\Model\ReadModels\PermissionQueriesInterface;
use Mistery23\LaravelRoles\Model\ReadModels\RoleQueriesInterface;
use Webmozart\Assert\Assert;

/**
 * Class Handler
 */
class Handler
{

    /**
     * @var \Illuminate\Database\ConnectionInterface
     */
    private $db;

    /**
     * @var Role\Repository\RoleRepositoryInterface
     */
    private $repository;

    /**
     * @var RoleQueriesInterface
     */
    private $queries;

    /**
     * @var PermissionQueriesInterface
     */
    private $permQueries;


    /**
     * Create a new command instance.
     *
     * @param Role\Repository\RoleRepositoryInterface $repository
     * @param RoleQueriesInterface                    $queries
     * @param PermissionQueriesInterface              $permQueries
     */
    public function __construct(
        Role\Repository\RoleRepositoryInterface $repository,
        RoleQueriesInterface $queries,
        PermissionQueriesInterface $permQueries
    ) {
        $this->db          = DB::connection(config('roles.connection'));
        $this->repository  = $repository;
        $this->queries     = $queries;
        $this->permQueries = $permQueries;
    }


    /**
     * @param Command $command
     *
     * @throws \RuntimeException
     *
     * @return void
     */
    public function handle(Command $command): void
    {
        $role = $this->queries->getById($command->roleId);

        Assert::notNull($role, 'Role not found.');

        $permissions = $this->permQueries->getByIdWithChildren($command->permissionId);

        $role->detachPermissions($permissions);

        try {
            $this->db->beginTransaction();

            $this->repository->update($role);

            $this->db->commit();
        } catch (\PDOException $e) {
            $this->db->rollBack();

            throw new \RuntimeException($e->getMessage());
        }
    }
}
