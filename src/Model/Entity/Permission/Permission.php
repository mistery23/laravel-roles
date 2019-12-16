<?php

namespace Mistery23\LaravelRoles\Model\Entity\Permission;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Carbon;
use Mistery23\LaravelRoles\Contracts\PermissionHasRelations as PermissionHasRelationsContract;
use Mistery23\LaravelRoles\Traits\DatabaseTraits;
use Mistery23\LaravelRoles\Traits\PermissionHasRelations;
use Staudenmeir\LaravelAdjacencyList\Eloquent\HasRecursiveRelationships;
use Webmozart\Assert\Assert;

/**
 * Class Permission
 *
 * @property string $id
 * @property string $name
 * @property string $slug
 * @property string $description
 * @property string $model
 * @property string $parent_id
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property Carbon $deleted_at
 */
class Permission extends Model implements PermissionHasRelationsContract
{
    use DatabaseTraits, HasRecursiveRelationships, PermissionHasRelations, SoftDeletes;

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id',
        'name',
        'slug',
        'description',
        'model',
    ];

    /**
     * Typecast for protection.
     *
     * @var array
     */
    protected $casts = [
        'id'            => 'string',
        'name'          => 'string',
        'slug'          => 'string',
        'description'   => 'string',
        'model'         => 'string',
        'created_at'    => 'datetime',
        'updated_at'    => 'datetime',
        'deleted_at'    => 'datetime',
    ];

    /**
     * @var string
     */
    public $keyType = 'string';


    /**
     * Create a new model instance.
     *
     * @param array $attributes
     */
    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);

        $this->table = config('roles.permissionsTable');
    }

    /**
     * @param string $id
     * @param string $name
     * @param string $slug
     * @param string|null $entity
     * @param string|null $parentId
     * @param string|null $description
     *
     * @return self
     */
    public static function new(
        string $id,
        string $name,
        string $slug,
        ?string $entity = null,
        ?string $parentId = null,
        ?string $description = null
    ): self {

        $model = new self();

        $model->id          = $id;
        $model->name        = $name;
        $model->slug        = $slug;
        $model->model       = $entity;
        $model->parent_id   = $parentId;
        $model->description = $description;

        return $model;
    }

    /**
     * @param string $name
     * @param string $slug
     * @param string|null $entity
     * @param string|null $parentId
     * @param string|null $description
     *
     * @return void
     */
    public function edit(
        string $name,
        string $slug,
        ?string $entity = null,
        ?string $parentId = null,
        ?string $description = null
    ): void {

        $this->name = $name;
        $this->slug = $slug;

        if ($entity) {
            $this->model = $entity;
        }

        if ($parentId) {
            $this->parent_id = $parentId;
        }

        if ($description) {
            $this->description = $description;
        }
    }

    /**
     * @return void
     */
    public function remove(): void
    {
        Assert::null($this->deleted_at, 'Permission already deleted');

        $this->deleted_at = Carbon::now();
    }

    /**
     * @param string $parentId
     *
     * @return void
     */
    public function doChild(string $parentId): void
    {
        $this->parent_id = $parentId;
    }

    /**
     * @return void
     */
    public function doRoot(): void
    {
        $this->parent_id = null;
    }
}
