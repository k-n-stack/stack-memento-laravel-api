<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

use App\Models\Thread;
use App\Models\Friend;
use App\Models\Comment;
use App\Models\Group;
use App\Models\Vote;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function threads() {
        return $this->hasMany(Thread::class);
    }

    public function friends() {
        return $this->hasMany(Friend::class);
    }

    public function comments() {
        return $this->hasMany(Comment::class);
    }

    public function subscribedGroups() {
        return $this->belongsToMany(Group::class);
    }

    public function ownGroups() {
        return $this->hasMany(Group::class);
    }

    public function votes() {
        return $this->hasMany(Vote::class);
    }
}
