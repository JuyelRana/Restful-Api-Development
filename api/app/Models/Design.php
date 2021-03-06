<?php

namespace App\Models;

use App\Models\Traits\Likeable;
use Cviebrock\EloquentTaggable\Taggable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;

class Design extends Model
{
    use HasFactory, SoftDeletes, Taggable, Likeable;

    protected $fillable = [
        'user_id',
        'team_id',
        'image',
        'title',
        'description',
        'slug',
        'close_to_comment',
        'is_live',
        'upload_successful',
        'disk'
    ];

    public function user(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function team()
    {
        return $this->belongsTo(Team::class);
    }

    public function comments()
    {
        return $this->morphMany(Comment::class, 'commentable')->orderBy('created_at', 'asc');
    }

    public function getImagesAttribute()
    {
        return [
            'original' => $this->getImagePath('original'),
            'large' => $this->getImagePath('large'),
            'thumbnail' => $this->getImagePath('thumbnail')
        ];
    }

    protected function getImagePath($size)
    {
        return Storage::disk($this->disk)->url("uploads/designs/{$size}/" . $this->image);
    }
}
