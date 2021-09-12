<?php

namespace App\Http\Controllers\Designs;

use App\Http\Controllers\Controller;
use App\Http\Resources\Design\DesignResource;
use App\Repositories\Contracts\Design\IDesign;
use App\Repositories\Eloquent\Criteria\{EagerLoad, ForUser, IsLive, LatestFirst};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class DesignController extends Controller
{
    protected $designs;

    /**
     * @param IDesign $designs
     */
    public function __construct(IDesign $designs)
    {
        $this->designs = $designs;
    }

    /**
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function index()
    {
        $designs = $this->designs->withCriteria([
            new LatestFirst(),
            new IsLive(),
            new ForUser(1),
            new EagerLoad(['user', 'comments'])
        ])->all();

        return DesignResource::collection($designs);
    }

    /**
     * @param $id
     * @return DesignResource
     */
    public function findDesignById($id): DesignResource
    {
        $design = $this->designs->find($id);
        return new DesignResource($design);
    }

    /**
     * @param Request $request
     * @param $id
     * @return DesignResource
     * @throws \Illuminate\Auth\Access\AuthorizationException
     * @throws \Illuminate\Validation\ValidationException
     */
    public function update(Request $request, $id): DesignResource
    {
        $design = $this->designs->find($id);

        $this->authorize('update', $design);

        $this->validate($request, [
            'title' => ['required', 'unique:designs,title,' . $id],
            'description' => ['required', 'string', 'min:20', 'max:140'],
            'tags' => ['required'],
            'team' => ['required_if:assign_to_team,true']
        ]);

        $design = $this->designs->update($id, [
            'team_id' => $request->team,
            'title' => $request->title,
            'description' => $request->description,
            'slug' => Str::slug($request->title),
            'is_live' => !$design->upload_successful ? false : $request->is_live
        ]);

        // Apply the tags
        $this->designs->applyTags($id, $request->tags);

        return new DesignResource($design);
    }

    /**
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function destroy($id): \Illuminate\Http\JsonResponse
    {
        $design = $this->designs->find($id);

        $this->authorize('delete', $design);

        // Delete the files associated to the record
        foreach (['thumbnail', 'large', 'original'] as $size) {
            // Check if the file exists in the database
            if (Storage::disk($design->disk)->exists("uploads/designs/{$size}/" . $design->image)) {
                Storage::disk($design->disk)->delete("uploads/designs/{$size}/" . $design->image);
            }
        }

        // Now delete the design record also
        $this->designs->delete($id);

        return response()->json(['message' => 'Design deleted successfully'], 200);
    }

    /**
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function like($id): \Illuminate\Http\JsonResponse
    {
        $this->designs->like($id);

        return response()->json(['message' => 'Successful'], 200);
    }

    /**
     * @param $designId
     * @return \Illuminate\Http\JsonResponse
     */
    public function checkIfUserHasLiked($designId)
    {
        $isLiked = $this->designs->isLikedByUser($designId);
        return response()->json(['liked' => $isLiked], 200);
    }

    public function search(Request $request)
    {
        $designs = $this->designs->search($request);

        return DesignResource::collection($designs);
    }
}
