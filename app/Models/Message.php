<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;

class Message extends Model
{
    use HasFactory, SoftDeletes;

    protected $touches = ['chat'];

    protected $fillable = [
        'user_id',
        'chat_id',
        'body',
        'last_read_at'
    ];

    public function getBodyAttribute($value)
    {
        if ($this->trashed()) {
            if (!Auth::check()) return null;
            return Auth::id() === $this->sender->id ? 'You deleted this message' : "{$this->sender->name} deleted this message";
        }
        return $value;
    }

    public function chat(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Chat::class);
    }

    public function sender(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

}
