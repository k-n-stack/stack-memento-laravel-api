<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Models\Bookmark;
use App\Models\User;
use App\Models\Friend;
use App\Models\Group;

class Thread extends Model
{
    use HasFactory;

    protected $fillable = [
        'alphanumeric_id',
        'user_id',
        'title',
        'visibility',
        'image_url',
        'color',
    ];

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
        return 
        !empty($this->deleted_at) ? null :
        [
            "id" => $this->id,
            "alphanumeric_id" => $this->alphanumeric_id,
            "title" => $this->title,
            "color" => $this->color,
            "image_url" => $this->image_url,
            "visibility" => $this->visibility,
            "created_at" => $this->created_at->format('Y-m-d H:i:s'),
            "bookmarks" => $this->bookmarks->map(function ($bookmark) {
                return !empty($bookmark->deleted_at) ? null : [
                    "description" => $bookmark->description,
                    "url" => $bookmark->url,
                    "created_at" => $bookmark->created_at,
                    "redirection_count" => array_sum($bookmark->users->map(function ($redirection) {
                        return $redirection->pivot->count;
                    })->toArray()),
                    "vote_count" => $bookmark->votes->count(),
                    "comment_count" => $bookmark->comments->count(),
                    "tags" => $bookmark->tags->map(function ($tag) {
                        return $tag->name;
                    }),
                    "comments" => $bookmark->comments->map(function ($comment) {
                        return $comment->getNestedChilds();
                    })
                ];
            })
        ];
    }
}
