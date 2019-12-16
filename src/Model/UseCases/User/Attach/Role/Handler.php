<?php

declare(strict_types=1);

namespace Mistery23\LaravelRoles\Model\UseCases\User\Attach\Role;

use Illuminate\Database\ConnectionInterface;
use Illuminate\Support\Facades\DB;
use Mistery23\LaravelRoles\Contracts\UserQueriesInterface;
use Mistery23\LaravelRoles\Contracts\UserRepositoryInterface;
use Webmozart\Assert\Assert;

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
     * @return void
     *
     * @throws \PDOException
     * @throws \RuntimeException
     */
    public function handle(Command $command): void
    {
        $user = $this->queries->getById($command->userId);

        Assert::notNull($user, 'User is not found!');

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
