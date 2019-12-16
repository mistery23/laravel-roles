<?php

declare(strict_types=1);

namespace Mistery23\LaravelRoles\Model\UseCases\Permission\Remove;

use Illuminate\Database\ConnectionInterface;
use Illuminate\Support\Facades\DB;
use Mistery23\LaravelRoles\Model\Entity\Permission;
use Mistery23\LaravelRoles\Model\ReadModels\PermissionQueriesInterface;

/**
 * Class Handler
 */
class Handler
{

    /**
     * @var ConnectionInterface
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
     * @throws \PDOException
     * @throws \RuntimeException
     *
     * @return void
     */
    public function handle(Command $command): void
    {
        $perm = $this->queries->getById($command->id);

        $perm->remove();

        try {
            $this->db->beginTransaction();

            $this->repository->remove($perm);

            $this->db->commit();
        } catch (\PDOException $e) {
            $this->db->rollBack();

            throw new \RuntimeException($e->getMessage());
        }
    }
}
