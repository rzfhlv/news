<?php

namespace App\Console\Commands;

use App\Repositories\User\UserRepositoryContract;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class CreateUser extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:user {--email=} {--name=} {--password=}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create User';
    
    protected $registerRules = [
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

        $data = [
            'email' => $this->option('email'),
            'name' => $this->option('name'),
            'password' => $this->option('password'),
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
                    $user->syncRoles('public');
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
