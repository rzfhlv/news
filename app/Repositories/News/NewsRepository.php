<?php

namespace App\Repositories\News;

use App\Models\News;

class NewsRepository implements NewsRepositoryContract
{
    protected $news;

    public function __construct(News $news)
    {
        $this->news = $news;
    }

    public function create(array $data)
    {
        return $this->news::create($data);
    }

    public function all()
    {
        return $this->news::orderBy('id', 'desc')->paginate(10);
    }

    public function get(int $id)
    {
        return $this->news::with('comments')->find($id);
    }

    public function update(array $data, array $condition)
    {
        return $this->news::where($condition)->update($data);
    }

    public function delete(int $id)
    {
        return $this->news::find($id)->delete();
    }
}
