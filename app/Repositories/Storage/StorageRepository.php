<?php

namespace App\Repositories\Storage;

use Illuminate\Support\Facades\Storage;
use Illuminate\Http\UploadedFile; 

class StorageRepository implements StorageRepositoryContract
{
    protected $storage;

    public function __construct(Storage $storage)
    {
        $this->storage = $storage;
    }

    public function put(string $path, $file, string $disk = 'local', string $type = 'public')
    {
        return $this->storage::disk($disk)->put($path, $file, $type); 
    }

    public function putFile(string $path, UploadedFile $file, string $disk = 'local', string $type = 'public')
    {
        return $this->storage::disk($disk)->putFile($path, $file, $type);
    }

    public function url(string $path, string $disk = 'local')
    {
        if ($disk == 's3') {
            return $this->storage::disk($disk)->url($path);
        } else {
            return asset($this->storage::url($path));
        }
    }

    public function delete(array $data, string $disk = 'local')
    {
        return $this->storage::disk($disk)->delete($data);
    }
}
