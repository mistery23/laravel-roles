<?php

declare(strict_types=1);

namespace jeremykenedy\LaravelRoles\Model\UseCases\User\Attach\Role;

use Illuminate\Support\Facades\DB;
use jeremykenedy\LaravelRoles\Contracts\UserQueriesInterface;
use jeremykenedy\LaravelRoles\Contracts\UserRepositoryInterface;


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
     * Create a new command instance.
     *
     * @param UserRepositoryInterface $repository
     * @param UserQueriesInterface    $queries
     */
    public function __construct(
        UserRepositoryInterface $repository,
        UserQueriesInterface $queries
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
        $user = $this->queries->getById($command->userId);

        $user->attachRole($command->roleId);

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
