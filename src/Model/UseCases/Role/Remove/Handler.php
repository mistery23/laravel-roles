<?php

declare(strict_types=1);

namespace jeremykenedy\LaravelRoles\Model\UseCases\Role\Remove;

use Illuminate\Support\Facades\DB;
use jeremykenedy\LaravelRoles\Model\Entity\Role;
use jeremykenedy\LaravelRoles\Model\ReadModels\RoleQueriesInterface;

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
     * Create a new command instance.
     *
     * @param Role\Repository\RoleRepositoryInterface $repository
     * @param RoleQueriesInterface $queries
     */
    public function __construct(
        Role\Repository\RoleRepositoryInterface $repository,
        RoleQueriesInterface $queries
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
        $role = $this->queries->getById($command->id);

        $role->remove();

        try {
            $this->db->beginTransaction();

            $this->repository->remove($role);

            $this->db->commit();
        } catch (\PDOException $e) {
            $this->db->rollBack();

            throw new \RuntimeException($e->getMessage());
        }
    }
}
