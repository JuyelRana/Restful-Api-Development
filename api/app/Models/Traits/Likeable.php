<?php

namespace App\Models\Traits;

use App\Models\Like;
use Illuminate\Support\Facades\Auth;

trait Likeable
{
    public static function bootLikeable()
    {
        static::deleting(function ($model){
            $model->removeLikes();
        });
    }

    // Delete likes when model is being deleted
    public function removeLikes()
    {
        if ($this->likes()->count()){
            $this->likes()->delete();
        }
    }

    public function likes()
    {
        return $this->morphMany(Like::class, 'likeable');
    }

    public function like()
    {
        if (!Auth::check()) return;

        // Check if the current user has already liked the model
        if ($this->isLikedByUser(Auth::id())) return;


        $this->likes()->create(['user_id' => Auth::id()]);
    }

    public function unlike()
    {
        if (!Auth::check()) return;

        if (!$this->isLikedByUser(Auth::id())) return;

        $this->likes()->where('user_id', Auth::id())->delete();
    }

    public function isLikedByUser($use_id): bool
    {
        return (bool)$this->likes()->where('user_id', $use_id)->count();
    }

}
