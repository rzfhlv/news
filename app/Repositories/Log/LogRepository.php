<?php

namespace App\Repositories\Log;

use App\Models\Log;

class LogRepository implements LogRepositoryContract
{
    protected $log;

    public function __construct(Log $log)
    {
        $this->log = $log;
    }

    public function create(array $data)
    {
        return $this->log::create($data);
    }
}
