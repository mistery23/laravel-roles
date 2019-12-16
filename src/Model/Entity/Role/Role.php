<?php

namespace Mistery23\LaravelRoles\Model\Entity\Role;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Mistery23\LaravelRoles\Contracts\RoleHasRelations as RoleHasRelationsContract;
use Mistery23\LaravelRoles\Traits\DatabaseTraits;
use Mistery23\LaravelRoles\Traits\RoleHasRelations;
use Mistery23\EloquentSmartPushRelations\SmartPushRelations;
use Staudenmeir\LaravelAdjacencyList\Eloquent\HasRecursiveRelationships;
use Webmozart\Assert\Assert;

/**
 * Class Role
 *
 * @property string  $id
 * @property string  $name
 * @property string  $slug
 * @property string  $description
 * @property integer $level
 * @property integer $parent_id
 * @property string  $created_at
 * @property string  $updated_at
 * @property string  $deleted_at
 */
class Role extends Model implements RoleHasRelationsContract
{
    use DatabaseTraits, RoleHasRelations, SmartPushRelations, SoftDeletes;
    use HasRecursiveRelationships;

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
        'level',
        'parent_id'
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
        'level'         => 'integer',
        'parent_id'     => 'string',
        'created_at'    => 'datetime',
        'updated_at'    => 'datetime',
        'deleted_at'    => 'datetime',
    ];

    /**
     * @var string
     */
    public $keyType = 'string';

    /**
     * @var integer
     */
    public static $defaultLevel = 1;


    /**
     * Create a new model instance.
     *
     * @param array $attributes
     */
    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);

        $this->table = config('roles.rolesTable');
    }

    /**
     * @param string       $id
     * @param string       $name
     * @param string       $slug
     * @param integer|null $level
     * @param string|null  $description
     *
     * @return self
     */
    public static function new(
        string $id,
        string $name,
        string $slug,
        ?int $level = null,
        ?string $description = null
    ): self {

        $model = new self();

        $model->id          = $id;
        $model->name        = $name;
        $model->slug        = $slug;
        $model->level       = $level ?: self::$defaultLevel;
        $model->description = $description;

        return $model;
    }

    /**
     * @param string $name
     * @param string $slug
     * @param int $level
     * @param string|null $description
     *
     * @return void
     */
    public function edit(
        string $name,
        string $slug,
        ?int $level = null,
        ?string $description = null
    ): void {

        $this->name        = $name;
        $this->slug        = $slug;

        if ($level) {
            $this->level = $level;
        }

        if ($level) {
            $this->description = $description;
        }
    }

    /**
     * @return void
     */
    public function remove(): void
    {
        Assert::null($this->deleted_at, 'Role already deleted');
    }

    /**
     * @param string $id
     * @param string $name
     * @param string $slug
     *
     * @return self
     */
    public function copy(string $id, string $name, string $slug): self
    {
        $role = self::new(
            $id,
            $name,
            $slug,
            self::$defaultLevel
        );

        $this->permissions()->allRelatedIds()->map(static function ($permId) use (&$role) {
            $role->attachPermission($permId);
        });

        if (null !== $this->parent_id) {
            $role->doChild($this->parent_id);
        }

        return $role;
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
