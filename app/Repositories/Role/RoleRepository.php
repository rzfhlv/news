<?php

namespace App\Repositories\Role;

use Spatie\Permission\Models\Role;

class RoleRepository implements RoleRepositoryContract
{
    protected $role;

    public function __construct(Role $role)
    {
        $this->role = $role;
    }

    public function firstOrCreate(array $data)
    {
        return $this->role::firstOrCreate($data);
    }
}
