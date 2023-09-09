<?php

namespace App\Services\User;

use App\Repositories\User\UserRepositoryContract;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Support\Facades\Validator;
use InvalidArgumentException;

class UserService implements UserServiceContract
{
    protected $loginRule = [
        'email' => 'required|email',
        'password' => 'required',
    ];

    protected $userRepository;

    public function __construct(UserRepositoryContract $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function login(array $data)
    {
        $validator = Validator::make($data, $this->loginRule);

        if ($validator->fails()) {
            throw new InvalidArgumentException($validator->errors());
        }

        if ($this->userRepository->attempt($data)) {
            $check['email'] = $data['email'];
            $user = $this->userRepository->check($check);
            $token = $user->createToken('MyApp')->accessToken;
        } else {
            throw new AuthenticationException('Unauthorized');
        }

        return collect(["token" => $token])->toJson();
    }
}
