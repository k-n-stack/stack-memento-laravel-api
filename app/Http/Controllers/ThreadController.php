<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use App\Models\User;

class ThreadController extends Controller
{

    public function countAllOfAuth() {
        return Auth::user()->threads->count();
    }

    private function allFullByUser($user) {
        return $user->threads->map(function ($thread) {
            return !empty($thread->deleted_at) ? null : [
                "id" => $thread->id,
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

    public function allFullOfAuth() {
        return $this->allFullByUser(Auth::user());
    }

    public function allFullOfGlobal() {
        $global = User::where('email', 'global@stackmemento.com')->first();

        return array_values(array_filter($this->allFullByUser($global)->map(function ($thread) {
            return count($thread["bookmarks"]) === 0 ? null : $thread;
        })->toArray()));
    }

}
