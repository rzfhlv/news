<?php

namespace App\Services\News;

use App\Repositories\News\NewsRepositoryContract;
use App\Repositories\Storage\StorageRepositoryContract;
use Illuminate\Support\Facades\Validator;
use InvalidArgumentException;
use Illuminate\Http\Request;
use App\Events\NewsTrigger;

class NewsService implements NewsServiceContract
{
    const PATH_IMAGE = 'public/images';
    const DISK_LOCAL = 'local';

    protected $createRules = [
        'title' => 'required',
        'content' => 'required',
        'image' => 'required',
        'author' => 'required',
        'is_publish' => 'required|boolean',
    ];

    protected $newsRepository;
    protected $storageRepository;

    public function __construct(
        NewsRepositoryContract $newsRepository,
        StorageRepositoryContract $storageRepository,
    ) {
        $this->newsRepository = $newsRepository;
        $this->storageRepository = $storageRepository;
    }

    public function create(Request $request)
    {
        $data = $request->all();
        $path = "";
        $disk = self::DISK_LOCAL;

        $validator = Validator::make($data, $this->createRules);

        if ($validator->fails()) {
            throw new InvalidArgumentException($validator->errors());
        }

        try {
            $image = $request->file('image');
            $path = $this->storageRepository->putFile(self::PATH_IMAGE, $image, $disk);
            $imageName = $this->storageRepository->url($path, $disk);
            $data['image'] = $imageName;
            $news = $this->newsRepository->create($data);

            $logData = [
                'type' => 'news',
                'action' => 'create',
                'ip_address' => $request->ip(),
                'user_agent' => $request->header('User-Agent'),
                'current_payload' => $news->toJson(),
                'changed_by' => auth()->user()->id,
            ];
            NewsTrigger::dispatch($logData);
        } catch (\Throwable $th) {
            $this->storageRepository->delete([$path], $disk);

            throw $th;
        }

        return $news;
    }

    public function all()
    {
        return $this->newsRepository->all();
    }

    public function get(int $id)
    {
        return $this->newsRepository->get($id);
    }

    public function update(Request $request, int $id)
    {
        $data = $request->all();
        $path = "";
        $disk = self::DISK_LOCAL;

        $validator = Validator::make($data, $this->createRules);

        if ($validator->fails()) {
            throw new InvalidArgumentException($validator->errors());
        }

        try {
            $image = $request->file('image');
            $path = $this->storageRepository->putFile(self::PATH_IMAGE, $image, $disk);
            $imageName = $this->storageRepository->url($path, $disk);
            $data['image'] = $imageName;
            $condition = ['id' => $id];
            $prev = $this->newsRepository->get($id);
            $news = $this->newsRepository->update($data, $condition);
            $current = $this->newsRepository->get($id);

            $logData = [
                'type' => 'news',
                'action' => 'update',
                'ip_address' => $request->ip(),
                'user_agent' => $request->header('User-Agent'),
                'previous_payload' => $prev->toJson(),
                'current_payload' => $current->toJson(),
                'changed_by' => auth()->user()->id,
            ];
            NewsTrigger::dispatch($logData);
        } catch (\Throwable $th) {
            $this->storageRepository->delete([$path], $disk);

            throw $th;
        }

        return $current;
    }

    public function delete(Request $request, int $id)
    {
        try {
            $prev = $this->newsRepository->get($id);
            $news = $this->newsRepository->delete($id);

            $logData = [
                'type' => 'news',
                'action' => 'delete',
                'ip_address' => $request->ip(),
                'user_agent' => $request->header('User-Agent'),
                'previous_payload' => $prev->toJson(),
                'changed_by' => auth()->user()->id,
            ];
            NewsTrigger::dispatch($logData);
        } catch (\Throwable $th) {
            throw $th;
        }
        return $news;
    }
}