<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Chat extends Model
{
    use HasFactory;

    public function participants(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(User::class, 'participants');
    }

    public function messages(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Message::class);
    }

    // Helper
    public function getLatestMessageAttribute()
    {
        return $this->messages()->latest()->first();
    }

    public function isUnreadForUser($userId): bool
    {
        return (bool)$this->messages()
            ->whereNull('last_read_at')
            ->where('user_id', '<>', $userId)
            ->count();
    }

    public function markedAsReadForUser($userId)
    {
        $this->messages()
            ->whereNull('last_read_at')
            ->where('user_id', '<>', $userId)
            ->update([
                'last_read_at' => Carbon::now()
            ]);
    }
}
