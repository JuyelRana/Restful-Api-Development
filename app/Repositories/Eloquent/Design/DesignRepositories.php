<?php

namespace App\Repositories\Eloquent\Design;

use App\Models\Design;
use App\Repositories\Contracts\Design\IDesign;
use App\Repositories\Eloquent\BaseRepository;

class DesignRepositories extends BaseRepository implements IDesign
{
    public function model(): string
    {
        return Design::class;
    }


    public function applyTags($id, array $data)
    {
        $design = $this->find($id);

        $design->retag($data);
    }

    public function allLive()
    {
        return $this->model->where('is_live', true)->get();
    }
}
