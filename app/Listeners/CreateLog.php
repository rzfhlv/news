<?php

namespace App\Listeners;

use App\Events\NewsTrigger;
use App\Repositories\Log\LogRepositoryContract;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;

class CreateLog
{
    protected $logRepository;

    /**
     * Create the event listener.
     */
    public function __construct(LogRepositoryContract $logRepository)
    {
        $this->logRepository = $logRepository;
    }

    /**
     * Handle the event.
     */
    public function handle(NewsTrigger $event): void
    {
        try {
            $this->logRepository->create($event->data);
        } catch (\Throwable $th) {
            Log::error($th->getMessage());
        }
    }
}
