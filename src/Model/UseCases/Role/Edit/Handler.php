<?php

declare(strict_types=1);

namespace Mistery23\LaravelRoles\Model\UseCases\Role\Edit;

use Illuminate\Database\ConnectionInterface;
use Illuminate\Support\Facades\DB;
use Mistery23\LaravelRoles\Model\Entity\Role;
use Mistery23\LaravelRoles\Model\ReadModels\RoleQueriesInterface;
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
     * @return void
     *
     * @throws \PDOException
     * @throws \RuntimeException
     */
    public function handle(Command $command): void
    {
        $role = $this->queries->getById($command->id);

        Assert::notNull($role, 'Role is not found.');

        $role->edit(
            $command->name,
            $command->slug,
            $command->level,
            $command->description
        );

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
