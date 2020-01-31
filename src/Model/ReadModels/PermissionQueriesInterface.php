<?php

namespace Mistery23\LaravelRoles\Model\ReadModels;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Mistery23\LaravelRoles\Model\Entity\Permission\Permission as Model;

/**
 * Interface PermissionQueriesInterface
 */
interface PermissionQueriesInterface
{

    /**
     * @param string $id
     *
     * @return Model
     */
    public function getById(string $id): Model;

    /**
     * @param string $id
     *
     * @return Collection
     */
    public function getByIdWithChildren(string $id): Collection;

    /**
     * @param string $slug
     *
     * @return Model
     */
    public function getBySlug(string $slug): Model;

    /**
     * @param string $id
     *
     * @return boolean
     */
    public function exists(string $id): bool;

    /**
     * @param integer $perPage
     *
     * @return LengthAwarePaginator
     */
    public function getPermissionsRootWithChildren(int $perPage = 20): LengthAwarePaginator;

    /**
     * @param string  $permissionId
     * @param integer $perPage
     *
     * @return LengthAwarePaginator
     */
    public function getPermissionChildren(string $permissionId, int $perPage = 20): LengthAwarePaginator;

    /**
     * @param string $userId
     * @param string $permissionId
     *
     * @return boolean
     */
    public function hasRolePermission(string $userId, string $permissionId): bool;
}
