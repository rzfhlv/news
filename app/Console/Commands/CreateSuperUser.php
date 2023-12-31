<?php

namespace App\Console\Commands;

use App\Repositories\Role\RoleRepositoryContract;
use App\Repositories\User\UserRepositoryContract;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Spatie\Permission\Models\Role;

class CreateSuperUser extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:super-user';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create Super User';

    protected $registerRules = [
        'name' => 'required',
        'email' => 'required|email|unique:users',
        'password' => 'required',
    ];

    /**
     * Execute the console command.
     */
    public function handle(
        UserRepositoryContract $userRepository,
        RoleRepositoryContract $roleRepository,
    ) {
        $isSuccess = false;
        $message = "No Message";

        $name = $this->ask('Name for Super User');
        $email = $this->ask('Email for Super User');
        $password = $this->secret('Password for Super User');

        $data = [
            'name' => $name,
            'email' => $email,
            'password' => $password,
        ];

        $validator = Validator::make($data, $this->registerRules);

        if ($validator->fails()) {
            $isSuccess = false;
            $message = json_encode($validator->errors());
        } else {
            $data['password'] = bcrypt($data['password']);

            DB::beginTransaction();
            try {
                if ($user = $userRepository->create($data)) {
                    $role = $roleRepository->firstOrCreate(['name' => 'admin', 'guard_name' => 'api']);
                    $user->syncRoles($role->name);
                    $isSuccess = true;
                    $message = "Create User Success";
                    DB::commit();
                } else {
                    DB::rollBack();
                    $isSuccess = false;
                    $message = "Create User Failed";
                }
            } catch (\Throwable $th) {
                DB::rollBack();
                $isSuccess = false;
                $message = json_encode($th->getMessage());
            }
        }

        if ($isSuccess) {
            $this->info($message);
        } else {
            $this->error($message);
        }
    }
}
