<?php

namespace App\Repositories\Eloquent\Design;

use App\Models\Design;
use App\Repositories\Contracts\Design\IDesign;
use App\Repositories\Eloquent\BaseRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DesignRepository extends BaseRepository implements IDesign
{
    /**
     * @return string
     */
    public function model(): string
    {
        return Design::class;
    }


    /**
     * @param $id
     * @param array $data
     */
    public function applyTags($id, array $data)
    {
        $design = $this->find($id);

        $design->retag($data);
    }

    /**
     * @param $designId
     * @param array $data
     * @return mixed
     */
    public function addComment($designId, array $data)
    {
        // Get the design for which we want to create a comment
        $design = $this->find($designId);

        // Create the comment for the design
        return $design->comments()->create($data);
    }

    /**
     * @param $id
     */
    public function like($id)
    {
        $design = $this->model->findOrFail($id);

        if ($design->isLikedByUser(Auth::id())) {
            $design->unlike();
        } else {
            $design->like();
        }
    }

    /**
     * @param $id
     * @return mixed
     */
    public function isLikedByUser($id)
    {
        $design = $this->model->findOrFail($id);
        return $design->isLikedByUser(Auth::id());
    }

    /**
     * @param Request $request
     * @return mixed
     */
    public function search(Request $request)
    {
        $query = (new $this->model)->newQuery();

        $query->where('is_live', true);

        // Return only design with comments
        if ($request->has_comments) {
            $query->has('comments');
        }

        // Return only designs assigned to teams
        if ($request->has_team) {
            $query->has('team');
        }

        // Search title and description for provided string
        if ($request->q) {
            $query->where(function ($q) use ($request) {
                $q->where('title', 'like', '%' . $request->q . '%')
                    ->orWhere('description', 'like', '%' . $request->q . '%');
            });
        }

        // Order the query by likes or latest first
        if ($request->orderBy == 'likes') {
            $query->withCount('likes')->orderByDesc('likes_count');
        } else {
            $query->latest();
        }

        return $query->get();

    }
}
