<?php

declare(strict_types=1);

namespace Mistery23\LaravelRoles\Model\UseCases\User\Attach\Permission;

use Illuminate\Support\Facades\DB;
use Mistery23\LaravelRoles\Contracts\UserQueriesInterface;
use Mistery23\LaravelRoles\Contracts\UserRepositoryInterface;
use Mistery23\LaravelRoles\Model\ReadModels\PermissionQueriesInterface;
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
     * @var UserRepositoryInterface
     */
    private $repository;

    /**
     * @var UserQueriesInterface
     */
    private $queries;

    /**
     * @var PermissionQueriesInterface
     */
    private $permQueries;


    /**
     * Create a new command instance.
     *
     * @param UserRepositoryInterface    $repository
     * @param UserQueriesInterface       $queries
     * @param PermissionQueriesInterface $permQueries
     */
    public function __construct(
        UserRepositoryInterface $repository,
        UserQueriesInterface $queries,
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
        $user = $this->queries->getById($command->userId);

        Assert::notNull($user, 'User is not found!');

        $permission = $this->permQueries->getById($command->permissionId);

        Assert::notNull($permission, 'Permission is not found!');

        $user->attachPermission($permission->id);

        try {
            $this->db->beginTransaction();

            $this->repository->update($user);

            $this->db->commit();
        } catch (\PDOException $e) {
            $this->db->rollBack();

            throw new \RuntimeException($e->getMessage());
        }
    }
}
