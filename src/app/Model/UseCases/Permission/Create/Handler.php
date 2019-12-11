<?php

declare(strict_types=1);

namespace Mistery23\LaravelRoles\Model\UseCases\Permission\Create;

use Illuminate\Support\Facades\DB;
use Mistery23\LaravelRoles\Model\Entity\Permission;
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
     * @var Permission\Repository\PermissionRepository
     */
    private $repository;


    /**
     * Create a new command instance.
     *
     * @param Permission\Repository\PermissionRepositoryInterface $repository
     */
    public function __construct(
        Permission\Repository\PermissionRepositoryInterface $repository
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
        $perm = Permission\Permission::new(
            (string)Uuid::uuid4(),
            $command->name,
            $command->slug,
            $command->model,
            $command->parentId,
            $command->description
        );

        try {
            $this->db->beginTransaction();

            $this->repository->add($perm);

            $this->db->commit();
        } catch (\PDOException $e) {
            $this->db->rollBack();

            throw new \RuntimeException($e->getMessage());
        }
    }
}
