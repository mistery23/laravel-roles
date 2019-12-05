<?php

namespace jeremykenedy\LaravelRoles\Model\Entity;

use Illuminate\Database\Eloquent\Model;
use Ramsey\Uuid\Uuid;

/**
 * Class PermissionUser.
 *
 * @property string $id
 * @property string $user_id
 * @property string $permission_id
 */
class PermissionUser extends Model
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
        'user_id',
        'permission_id'
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

        $this->table = config('roles.permissionsUserTable');
    }

    /**
     * @param string $userId
     * @param string $permissionId
     *
     * @return self
     */
    public static function new(string $userId, string $permissionId): self
    {
        $model = new self();

        $model->id            = Uuid::uuid4();
        $model->user_id       = $userId;
        $model->permission_id = $permissionId;

        return $model;
    }
}
