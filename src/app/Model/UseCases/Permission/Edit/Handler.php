<?php

declare(strict_types=1);

namespace Mistery23\LaravelRoles\Model\UseCases\Permission\Edit;

use Illuminate\Support\Facades\DB;
use Mistery23\LaravelRoles\Model\Entity\Permission;
use Mistery23\LaravelRoles\Model\ReadModels\PermissionQueriesInterface;

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

        $perm->edit(
            $command->name,
            $command->slug,
            $command->model,
            $command->model,
            $command->description
        );

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
