<?php

namespace App\Services\News;

use App\Repositories\News\NewsRepositoryContract;
use App\Repositories\Storage\StorageRepositoryContract;
use Illuminate\Support\Facades\Validator;
use InvalidArgumentException;
use Illuminate\Http\Request;

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
            $news = $this->newsRepository->update($data, $condition);
        } catch (\Throwable $th) {
            $this->storageRepository->delete([$path], $disk);

            throw $th;
        }

        return $news;
    }

    public function delete(int $id)
    {
        return $this->newsRepository->delete($id);
    }
}