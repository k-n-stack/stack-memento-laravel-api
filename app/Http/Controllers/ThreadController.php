<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

use App\Models\Thread;
use App\Models\User;

class ThreadController extends Controller
{

    public function countAllOfAuth() {
        return Auth::user()->threads->count();
    }

    private function allFullByUser($user) {
        return $user->threads->map(function ($thread) {
            return 
            !empty($thread->deleted_at) ? null :
            [
                "id" => $thread->id,
                "alphanumeric_id" => $thread->alphanumeric_id,
                "title" => $thread->title,
                "color" => $thread->color,
                "image_url" => $thread->image_url,
                "visibility" => $thread->visibility,
                "created_at" => $thread->created_at->format('Y-m-d H:i:s'),
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

    // public function getFellowsThreads() {
    //     return Auth::user()->friends->map(function ($friend) {
    //         $fellow = User::find($friend->friend_id);
    //         return empty($friend->validated_at) ? null : [
    //             'fellow_anid' => $fellow->alphanumeric_id,
    //             'threads' => $fellow->threads->map(function ($thread) {
    //                 return $thread->visibility === "private" ? null : $thread;
    //             }),
    //         ];
    //     });
    // }

    public function allFullOfGlobal() {
        $global = User::where('email', 'global@stackmemento.com')->first();

        return array_values(array_filter($this->allFullByUser($global)->map(function ($thread) {
            return count($thread["bookmarks"]) === 0 ? null : $thread;
        })->toArray()));
    }

    public function pinnedOfAuth() {
        return Auth::user()->pinnedThreads;
    }

    public function postThread (Request $request) {

        $validator = Validator::make($request->all(), [
            'title' => ['required', 'string', 'max: 128'],
            'visibility' => ['required', 'string'],
            'color' => ['required', 'string', 'max: 6'],
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors());
        }

        $thread = Thread::create([
            'alphanumeric_id' => $this->generateANID(8),
            'user_id' => Auth::user()->id,
            'title' => $request->title,
            'visibility' => $request->visibility,
            'image_url' => '0123456.png',
            'color' => $request->color,
        ]);

        return response()->json(['status' => 'thread added']);
    }

    public function generateANID($length) {
        $alphaNumerics = "0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz";
        $random = "";
        for ($i = 0; $i < $length; $i++) {
            $random .= $alphaNumerics[rand(0, strlen($alphaNumerics) - 1)];
        }
        return $random;
    }

}
