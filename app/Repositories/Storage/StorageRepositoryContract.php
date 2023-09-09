<?php

namespace App\Repositories\Storage;

use Illuminate\Http\UploadedFile;

interface StorageRepositoryContract
{
    public function put(string $path, $file, string $disk = 'local', string $type = 'public');
    public function putFile(string $path, UploadedFile $file, string $disk = 'local', string $type = 'public');
    public function url(string $path, string $disk = 'local');
    public function delete(array $data, string $disk = 'local');
}
