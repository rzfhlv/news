<?php

namespace App\Repositories\News;

interface NewsRepositoryContract
{
    public function create(array $data);
    public function all();
    public function get(int $id);
    public function update(array $data, array $condition);
    public function delete(int $id);
}
