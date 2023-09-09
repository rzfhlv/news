<?php

namespace App\Repositories\User;

use App\Models\User;
use Illuminate\Support\Facades\Auth;

class UserRepository implements UserRepositoryContract
{
    protected $user;
    protected $auth;

    public function __construct(User $user, Auth $auth)
    {
        $this->user = $user;
        $this->auth = $auth;
    }

    public function create(array $data)
    {
        return $this->user::create($data);
    }

    public function check(array $data)
    {
        return $this->user::where($data)->first();
    }

    public function attempt(array $data)
    {
        return $this->auth::attempt([
            'email' => $data['email'],
            'password' => $data['password'],
        ]);
    }
}