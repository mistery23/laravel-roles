<?php

namespace jeremykenedy\LaravelRoles\Model\Entity\Role;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use jeremykenedy\LaravelRoles\Contracts\RoleHasRelations as RoleHasRelationsContract;
use jeremykenedy\LaravelRoles\Traits\DatabaseTraits;
use jeremykenedy\LaravelRoles\Traits\RoleHasRelations;
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
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = true;

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
     * @param string      $id
     * @param string      $name
     * @param string      $slug
     * @param int         $level
     * @param string|null $description
     *
     * @return self
     */
    public static function new(
        string $id,
        string $name,
        string $slug,
        int $level,
        ?string $description = null
    ): self {

        $model = new self();

        $model->id          = $id;
        $model->name        = $name;
        $model->slug        = $slug;
        $model->level       = $level;
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
        int $level,
        ?string $description
    ): void {

        $this->name        = $name;
        $this->slug        = $slug;
        $this->level       = $level;
        $this->description = $description;
    }

    /**
     * @return void
     */
    public function remove(): void
    {
        Assert::null($this->deleted_at, 'Role already deleted');
    }

    public function copy(string $id, string $name, string $slug): self
    {
        $role = Role::new(
            $id,
            $name,
            $slug,
            Role::$defaultLevel
        );

        $this->permissions()->allRelatedIds()->map(function ($permId) use (&$role) {
            $role->attachPermission($permId);
        });

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
