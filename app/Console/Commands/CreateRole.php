<?php

namespace App\Console\Commands;

use Spatie\Permission\Models\Role;
use Illuminate\Console\Command;

class CreateRole extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:role {name}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create Role';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $name = $this->argument('name');

        try {
            $role = Role::firstOrCreate(['name' => $name, 'guard_name' => 'api']);
            $this->info('Role success created with id = '.
                        $role->id . ' and role = ' . $role->name);
        } catch (\Throwable $th) {
            $this->error('Role failed to create, error ' . json_encode($th->getMessage()));
        }
    }
}
