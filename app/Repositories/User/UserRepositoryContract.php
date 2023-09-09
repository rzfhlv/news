<?php

namespace App\Repositories\User;

use Illuminate\Http\Request;

interface UserRepositoryContract
{
    public function create(array $data);
    public function check(array $data);
    public function attempt(array $data);
    public function logout(Request $request);
}
