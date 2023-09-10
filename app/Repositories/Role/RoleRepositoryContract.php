<?php

namespace App\Repositories\Role;

interface RoleRepositoryContract
{
    public function firstOrCreate(array $data);
}
