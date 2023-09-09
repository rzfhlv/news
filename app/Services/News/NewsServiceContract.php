<?php

namespace App\Services\News;

use Illuminate\Http\Request;

interface NewsServiceContract
{
    public function create(Request $request);
    public function all();
    public function get(int $id);
    public function update(Request $request, int $id);
    public function delete(Request $request, int $id);
}
