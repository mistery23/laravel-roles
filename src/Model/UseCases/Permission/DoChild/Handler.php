<?php

declare(strict_types=1);

namespace jeremykenedy\LaravelRoles\Model\UseCases\Permission\DoChild;

use Illuminate\Support\Facades\DB;
use jeremykenedy\LaravelRoles\Model\Entity\Permission;
use jeremykenedy\LaravelRoles\Model\ReadModels\PermissionQueriesInterface;
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
     * @var Permission\Repository\PermissionRepositoryInterface
     */
    private $repository;

    /**
     * @var PermissionQueriesInterface
     */
    private $queries;


    /**
     * Create a new command instance.
     *
     * @param Permission\Repository\PermissionRepositoryInterface $repository
     * @param PermissionQueriesInterface $queries
     */
    public function __construct(
        Permission\Repository\PermissionRepositoryInterface $repository,
        PermissionQueriesInterface $queries
    ) {
        $this->db         = DB::connection(config('roles.connection'));
        $this->repository = $repository;
        $this->queries    = $queries;
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
        $perm = $this->queries->getById($command->id);

        Assert::notEq($perm->parent_id, $command->parentId, 'Permission is already childlike by this parent.');

        $parent = $this->queries->exists($command->parentId);

        Assert::true($parent, 'Parent role isn\'t exists.');

        $perm->doChild($command->parentId);

        try {
            $this->db->beginTransaction();

            $this->repository->update($perm);

            $this->db->commit();
        } catch (\PDOException $e) {
            $this->db->rollBack();

            throw new \RuntimeException($e->getMessage());
        }
    }
}
