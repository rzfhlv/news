<?php

namespace App\Console\Commands;

use App\Repositories\User\UserRepositoryContract;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

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

    protected $registerRule = [
        'name' => 'required',
        'email' => 'required|email|unique:users',
        'password' => 'required',
    ];

    /**
     * Execute the console command.
     */
    public function handle(UserRepositoryContract $userRepository)
    {
        $isSuccess = false;
        $message = "No Message";

        $name = $this->ask('Name for Super User');
        $email = $this->ask('Email for Super User');
        $password = $this->ask('Password for Super User');

        $data = [
            'name' => $name,
            'email' => $email,
            'password' => $password,
        ];

        $validator = Validator::make($data, $this->registerRule);

        if ($validator->fails()) {
            $isSuccess = false;
            $message = json_decode($validator->errors());
        } else {
            $data['password'] = bcrypt($data['password']);

            DB::beginTransaction();
            try {
                if ($user = $userRepository->create($data)) {
                    $user->syncRoles('admin');
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
