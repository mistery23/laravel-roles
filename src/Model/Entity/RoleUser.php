<?php

namespace jeremykenedy\LaravelRoles\Model\Entity;

use Illuminate\Database\Eloquent\Model;
use Ramsey\Uuid\Uuid;

/**
 * Class RolePermission.
 *
 * @property string $id
 * @property string $role_id
 * @property string $user_id
 */
class RoleUser extends Model
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
        'role_id'
    ];

    /**
     * Indicates if the model should be timestamped.
     *
     * @var boolean
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

        $this->table = config('roles.roleUserTable');
    }

    /**
     * @param string $userId
     * @param string $roleId
     *
     * @return self
     */
    public static function new(string $userId, string $roleId): self
    {
        $model = new self();

        $model->id      = Uuid::uuid4();
        $model->user_id = $userId;
        $model->role_id = $roleId;

        return $model;
    }
}
