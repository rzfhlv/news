<?php

namespace App\Repositories\User;

interface UserRepositoryContract
{
    public function create(array $data);
    public function check(array $data);
    public function attempt(array $data);
}
