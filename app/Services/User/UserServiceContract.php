<?php

namespace App\Services\User;

use Illuminate\Http\Request;

interface UserServiceContract
{
    public function login(array $data);
    public function register(array $data);
    public function logout(Request $request);
}
