<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ThreadController extends Controller
{

    private static $allOfAuth = null; // <---- !!!! set to null on thread NUMBER update

    public function allOfAuth() {
        return self::$allOfAuth = Auth::user()->threads->map(function ($thread) {
            return !empty($thread->deleted_at) ? null : [
                "title" => $thread->title,
                "color" => $thread->color,
                "image_url" => $thread->image_url,
                "visibility" => $thread->visibility,
                "created_at" => $thread->created_at,
            ];
        });
    }

    public function countAllOfAuth() {
        return count(self::$allOfAuth === null ? $this->allOfAuth() : self::$allOfAuth);
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
                        "comments" => $bookmark->comments->map(function ($comment) {
                            return $comment->getNestedChilds();
                        })
                    ];
                })
            ];
        });
    }

}
