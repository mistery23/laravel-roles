<?php

namespace Mistery23\LaravelRoles\Traits;

/**
 * Trait DatabaseTraits
 */
trait DatabaseTraits
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table;

    /**
     * The connection name for the model.
     *
     * @var string
     */
    protected $connection;

    /**
     * Create a new instance to set the table and connection.
     *
     * @return void
     */
    public function __construct($attributes = [])
    {
        parent::__construct($attributes);

        if ($connection = config('roles.connection')) {
            $this->connection = $connection;
        }
    }

    /**
     * Get the database connection.
     */
    public function getConnectionName(): ?string
    {
        return $this->connection;
    }

    /**
     * Get the database table.
     */
    public function getTableName(): string
    {
        return $this->table;
    }
}
