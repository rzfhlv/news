<?php

namespace App\Services\User;

use App\Repositories\User\UserRepositoryContract;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use InvalidArgumentException;
use Spatie\Permission\Models\Role;

class UserService implements UserServiceContract
{
    const PUBLIC = 'public';
    const MYAPP = 'MyApp';
    const UNAUTHORIZED = 'Unauthorized';

    protected $loginRules = [
        'email' => 'required|email',
        'password' => 'required',
    ];

    protected $registerRules = [
        'name' => 'required',
        'email' => 'required|email|unique:users',
        'password' => 'required',
    ];

    protected $userRepository;

    public function __construct(UserRepositoryContract $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function login(array $data)
    {
        $validator = Validator::make($data, $this->loginRules);

        if ($validator->fails()) {
            throw new InvalidArgumentException($validator->errors());
        }

        if ($this->userRepository->attempt($data)) {
            $check['email'] = $data['email'];
            $user = $this->userRepository->check($check);
            $token = $user->createToken(self::MYAPP)->accessToken;
        } else {
            throw new AuthenticationException(self::UNAUTHORIZED);
        }

        return collect(['token' => $token]);
    }

    public function register(array $data)
    {
        $validator = Validator::make($data, $this->registerRules);

        if ($validator->fails()) {
            throw new InvalidArgumentException($validator->errors());
        }

        $data['password'] = bcrypt($data['password']);

        DB::beginTransaction();
        try {
            $user = $this->userRepository->create($data);
            $role = Role::firstOrCreate(['name' => 'public', 'guard_name' => 'api']);
            $user->syncRoles($role->name);
            $token = $user->createToken(self::MYAPP)->accessToken;
            DB::commit();
        } catch (\Throwable $th) {
            DB::rollBack();
            throw $th;
        }

        return collect(['token' => $token]);
    }

    public function logout(Request $request)
    {
        return $this->userRepository->logout($request);
    }
}
