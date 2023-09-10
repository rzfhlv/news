<?php

namespace App\Repositories\User;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserRepository implements UserRepositoryContract
{
    const UNAUTHORIZED = 'Unauthorized';

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
        return $this->auth::check([
            'email' => $data['email'],
            'password' => $data['password'],
        ]);
    }

    public function logout(Request $request)
    {
        return $request->user()->token()->revoke();
    }
}