<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ThreadController extends Controller
{

    public function countAllOfAuth() {
        return Auth::user()->threads->count();
    }

    public function allFullOfAuth() {
        return Auth::user()->threads->map(function ($thread) {
            return !empty($thread->deleted_at) ? null : [
                "title" => $thread->title,
                "color" => $thread->color,
                "image_url" => $thread->image_url,
                "visibility" => $thread->visibility,
                "created_at" => $thread->created_at,
                "bookmarks" => $thread->bookmarks->map(function ($bookmark) {
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
        });
    }

}
