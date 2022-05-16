<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

use App\Models\Bookmark;
use App\Models\User;
use App\Models\Friend;
use App\Models\Group;

class Thread extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'alphanumeric_id',
        'user_id',
        'title',
        'visibility',
        'color',
    ];

    protected $hidden = [
        'id',
        'user_id',
        'deleted_at',
    ];

    protected $appends = [
        'redirection_count',
        'vote_count',
        'comment_count',
    ];

    protected $casts = [
        'created_at' => 'datetime:Y-m-d H:i',
        'updated_at' => 'datetime:Y-m-d H:i',
    ];

    public function getRedirectionCountAttribute () {
        return isset($this->attributes['redirection_count']) ? 
            $this->attributes['redirection_count'] : 0;
    }

    public function getVoteCountAttribute () {
        return isset($this->attributes['vote_count']) ? 
            $this->attributes['vote_count'] : 0;
    }

    public function getCommentCountAttribute () {
        return isset($this->attributes['comment_count']) ? 
            $this->attributes['comment_count'] : 0;
    }

    public function bookmarks() {
        return $this->belongsToMany(Bookmark::class);
    }

    public function user() {
        return $this->belongsTo(User::class);
    }

    public function friends() {
        return $this->belongsToMany(Friend::class);
    }

    public function groups() {
        return $this->belongsToMany(Group::class);
    }

    public function pinnedThreads() {
        return $this->belongsToMany(User::class, 'pinned_threads');
    }

    public function getThreadDetails() {

        $bookmarks = $this->bookmarks->map(function ($bookmark) {
            return $bookmark->getBookmarkDetails();
        });

        $redirectionCount = $bookmarks->reduce(function ($carry, $item) {
            $carry += $item['redirection_count'];
            return $carry;
        }, 0);
        $voteCount = $bookmarks->reduce(function ($carry, $item) {
            $carry += $item['vote_count'];
            return $carry;
        }, 0);
        $commentCount = $bookmarks->reduce(function ($carry, $item) {
            $carry += $item['comment_count'];
            return $carry;
        }, 0);

        $this->user;
        $this->groups;
        $this->redirection_count = $redirectionCount;
        $this->vote_count = $voteCount;
        $this->comment_count = $commentCount;
        $this->bookmarks = $bookmarks;

        return $this;
        
    }

    public function getGlobalBookmarks () {

        $title = $this->title;

        $allNamedThreads = self::where('title', $title)
            ->whereNotIn('visibility', ['private', 'shareable'])
            ->get();

        $bookmarks = [];

        $allNamedThreads->map(function ($thread) use (&$bookmarks) {
            $thread->bookmarks->map(function ($bookmark) use (&$bookmarks, $thread) {
                $bookmark->user = $thread->user;
                $bookmarks[] = $bookmark->getBookmarkDetails();
            });
        });

        return $bookmarks;
    }
}
