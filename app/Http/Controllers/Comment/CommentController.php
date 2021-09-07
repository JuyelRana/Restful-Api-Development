<?php

namespace App\Http\Controllers\Comment;

use App\Http\Controllers\Controller;
use App\Http\Resources\Comment\CommentResource;
use App\Repositories\Contracts\Comment\IComment;
use App\Repositories\Contracts\Design\IDesign;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CommentController extends Controller
{
    protected $comments, $designs;

    public function __construct(IComment $comments, IDesign $designs)
    {
        $this->comments = $comments;
        $this->designs = $designs;
    }

    public function store(Request $request, $designId)
    {
        $this->validate($request, [
            'body' => ['required']
        ]);

        $comment = $this->designs->addComment($designId, [
            'body' => $request->body,
            'user_id' => Auth::id()
        ]);

        return new CommentResource($comment);

    }

    public function update(Request $request, $id)
    {
        $comment = $this->comments->find($id);

        $this->authorize('update', $comment);

        $this->validate($request, [
            'body' => ['required']
        ]);

        $comment = $this->comments->update($id, [
            'body' => $request->body
        ]);

        return new CommentResource($comment);
    }

    public function destroy($id)
    {
        $comment = $this->comments->find($id);

        $this->authorize('update', $comment);

        $this->comments->delete($id);

        return response()->json(['message' => 'Item Deleted Successfully'], 200);
    }
}
