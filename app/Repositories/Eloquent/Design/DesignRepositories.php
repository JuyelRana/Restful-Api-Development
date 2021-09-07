<?php

namespace App\Repositories\Eloquent\Design;

use App\Models\Design;
use App\Repositories\Contracts\Design\IDesign;
use App\Repositories\Eloquent\BaseRepository;
use Illuminate\Support\Facades\Auth;

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

    public function addComment($designId, array $data)
    {
        // Get the design for which we want to create a comment
        $design = $this->find($designId);

        // Create the comment for the design
        return $design->comments()->create($data);
    }

    public function like($id)
    {
        $design = $this->model->findOrFail($id);

        if ($design->isLikedByUser(Auth::id())) {
            $design->unlike();
        } else {
            $design->like();
        }
    }

    public function isLikedByUser($id)
    {
        $design = $this->model->findOrFail($id);
        return $design->isLikedByUser(Auth::id());
    }
}
