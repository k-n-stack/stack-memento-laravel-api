<?php

namespace App\Http\Controllers;

use App\Models\Bookmark;
use App\Models\Comment;
use App\Models\Thread;
use App\Models\Tag;
use App\Models\User;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class BookmarkController extends Controller
{
    public function allOfAuth () {
        return Auth::user()->getBookmarks();
    }

    public function countAllOfAuth () {
        return count($this->allOfAuth());
    }

    public function postBookmark (Request $request) {

        if (empty($request->thread_ids)) {
            return response()->json(['status' => 'at least one thread is required']);
        }

        $threads = Thread::whereIn('id', $request->thread_ids)->get();
        foreach ($threads as $thread) {
            if ($thread->user_id !== Auth::id()) {
                return response()->json(['status' => 'error occured with thread id']);
            }
        }

        $protocols = [
            'http://', 
            'https://',
        ];

        $hasValidProtocol = false;

        foreach ($protocols as $protocol) {
            if (substr($request->url, 0, strlen($protocol)) === $protocol) {
                $hasValidProtocol = true;
            }
        }

        if (!$hasValidProtocol && !empty($request->protocol)) {
            $request->merge([
                'url' => $request->protocol.$request->url,
            ]);
        }

        $validator = Validator::make($request->all(), [
            'description' => ['required', 'string', 'max: 128'],
            'url' => ['required', 'url', 'string', 'max: 512'],
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors());
        }

        $bookmark = Bookmark::create([
            'description' => $request->description,
            'url' => $request->url,
        ]);
        
        foreach ($request->thread_ids as $thread_id) {
            $bookmark->threads()->attach($thread_id);
        }

        if (!empty($request->tags)) {

            $_tags = [];

            foreach ($request->tags as $tag) {
                $_tags[] = Tag::firstOrCreate([
                    'name' => $tag,
                ]);
            }

            $_tags = array_map(function ($tag) {
                return $tag->id;
            }, $_tags);
    
            foreach ($_tags as $tag_id) {
                $bookmark->tags()->attach($tag_id);
            }
        }

        if (!empty($request->comment)) {
            $comment = Comment::create([
                'poster_id' => Auth::user()->id,
                'bookmark_id' => $bookmark->id,
                'body' => $request->comment,
            ]);
        }

        return response()->json(['status' => 'bookmark added']);
    }
}
