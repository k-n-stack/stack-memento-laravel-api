<?php

namespace App\Http\Controllers;

use App\Models\Bookmark;
use App\Models\Comment;
use App\Models\Thread;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class BookmarkController extends Controller
{
    public function allOfAuth () {
        $bookmarks = [];
        $threads = Auth::user()->threads;
        foreach ($threads as $thread) {
            foreach ($thread->bookmarks as $bookmark) {
                array_push($bookmarks, $bookmark);
            }
        }
        return $bookmarks;
    }

    public function countAllOfAuth () {
        return count($this->allOfAuth());
    }

    public function getAllByThreadId () {
        
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
            'comment' => ['string', 'max: 2048'],
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

        return response()->json(['status' => 'bookmark added']);
    }
}
