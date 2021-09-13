<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;

class Team extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'owner_id',
        'slug'
    ];

    protected static function boot()
    {
        parent::boot();

        // When team is created, add current user as team member
        static::created(function ($team) {
            // Auth::user()->teams()->attach($team->id);
            $team->members()->attach(Auth::id());
        });

        static::deleting(function ($team) {
            $team->members()->sync([]);
        });
    }

    public function owner(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class, 'owner_id');
    }

    public function members(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(User::class)->withTimestamps();
    }

    public function designs(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Design::class);
    }

    public function hasUser(User $user): bool
    {
        return (bool)$this->members->where('user_id', $user->id)->first();
    }

    public function invitations(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Invitation::class);
    }

    public function hasPendingInvite($email): bool
    {
        return (bool)$this->invitations->where('recipient_email', $email)->count();
    }
}
