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
use App\Models\Bookmark;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'alphanumeric_id',
        'pseudonym',
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
        return $this->hasMany(Comment::class, 'poster_id');
    }

    public function subscribedGroups() {
        return $this->belongsToMany(Group::class);
    }

    public function ownGroups() {
        return $this->hasMany(Group::class, 'owner_id');
    }

    public function votes() {
        return $this->belongsToMany(Bookmark::class, 'votes');
    }

    public function redirections() {
        return $this->belongsToMany(Bookmark::class, 'redirections')->withPivot('count');
    }

    public function pinnedThreads() {
        return $this->belongsToMany(Thread::class, 'pinned_threads');
    }

    public function countThreads () {
        return count($this->threads);
    }

    public function countBookmarks () {
        return count($this->getBookmarks());
    }

    public function getBookmarks () {
        $bookmarks = [];
        $threads = $this->threads;
        foreach ($threads as $thread) {
            foreach ($thread->bookmarks as $bookmark) {
                array_push($bookmarks, $bookmark);
            }
        }
        return $bookmarks;
    }

    public function getLastBookmark () {
        $this->getBookmarks->last();
    }
}
