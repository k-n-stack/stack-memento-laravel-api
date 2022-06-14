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
use App\Models\User;


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
        'created_at' => 'datetime:Y-m-d H:i',
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

        $_bookmark = self::find($this->id);

        $this->redirection_count = array_sum($this->users->map(function ($redirection) {
            return $redirection->pivot->count;
        })->toArray());

        $this->vote_count = $_bookmark->votes->count();
        $this->comment_count = $_bookmark->comments->count();

        $this->comments = $_bookmark->comments->map(function ($comment) {
            return empty($comment->parent_id) ? $comment->getNestedChilds() : null;
        })->filter()->values();
 
        $this->user = User::find($this->getPosterId());

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
