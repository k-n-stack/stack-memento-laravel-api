<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

use App\Models\Thread;
use App\Models\Comment;
use App\Models\Vote;
use App\Models\Redirection;
use App\Models\Tag;


class Bookmark extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'description',
        'url',
    ];

    protected $hidden = [
        'pivot',
        'validated_at',
        'updated_at',
        'deleted_at',
        'users',
        'votes',
    ];

    protected $appends = [
        'redirection_count',
        'vote_count',
        'comment_count',
    ];

    protected $casts = [
        'created_at' => 'datetime:Y-m-d',
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

    public function threads() {
        return $this->belongsToMany(Thread::class);
    }

    public function comments() {
        return $this->hasMany(Comment::class);
    }

    public function votes() {
        return $this->belongsToMany(User::class, 'votes');
    }

    public function users() {
        return $this->belongsToMany(User::class, 'redirections')->withPivot('count');
    }

    public function tags() {
        return $this->belongsToMany(Tag::class);
    }

    public function getBookmarkDetails () {

        $this->redirection_count = array_sum($this->users->map(function ($redirection) {
            return $redirection->pivot->count;
        })->toArray());

        $this->vote_count = $this->votes->count();
        $this->comments_count = $this->comments->count();
        $this->comments = $this->comments->map(function ($comment) {
            return $comment->getNestedChilds();
        });

        $this->tags;

        return $this;
    }

    public function getPosterId() {
        $threads = $this->threads;
        $posterIds = $threads->map(function ($thread) {
            return $thread->user_id;
        });
        return ($posterIds->unique()->count() === 1) ? $posterIds[0] : false;
    }

}
