<?php

declare(strict_types=1);

namespace Mistery23\LaravelRoles\Model\UseCases\Role\Create;

use Illuminate\Support\Facades\DB;
use Mistery23\LaravelRoles\Model\Entity\Role;
use Ramsey\Uuid\Uuid;

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
     * Create a new command instance.
     *
     * @param Role\Repository\RoleRepositoryInterface $repository
     */
    public function __construct(
        Role\Repository\RoleRepositoryInterface $repository
    ) {
        $this->db         = DB::connection(config('roles.connection'));
        $this->repository = $repository;
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
        $role = Role\Role::new(
            (string)Uuid::uuid4(),
            $command->name,
            $command->slug,
            $command->level,
            $command->description
        );

        try {
            $this->db->beginTransaction();

            $this->repository->add($role);

            $this->db->commit();
        } catch (\PDOException $e) {
            $this->db->rollBack();

            throw new \RuntimeException($e->getMessage());
        }
    }
}
