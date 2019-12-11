<?php


namespace Mistery23\LaravelRoles\Model\Entity;

use Illuminate\Database\Eloquent\Model;
use Ramsey\Uuid\Uuid;

/**
 * Class RolePermission.
 *
 * @property string $id
 * @property string $role_id
 * @property string $permission_id
 */
class RolePermission extends Model
{

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
        'permission_id',
        'role_id'
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
     * Create a new model instance.
     *
     * @param array $attributes
     */
    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);

        $this->table = config('roles.permissionsRoleTable');
    }

    /**
     * @param string $roleId
     * @param string $permissionId
     *
     * @return self
     */
    public static function new(string $roleId, string $permissionId): self
    {
        $model = new self();

        $model->id            = Uuid::uuid4();
        $model->permission_id = $permissionId;
        $model->role_id       = $roleId;

        return $model;
    }
}
