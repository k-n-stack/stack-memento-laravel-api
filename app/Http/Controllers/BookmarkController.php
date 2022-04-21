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

        if (empty($request->thread_anids)) {
            return response()->json(['status' => 'at least one thread is required']);
        }

        $threads = Thread::whereIn('alphanumeric_id', $request->thread_anids)->get();
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
        
        foreach ($threads as $thread) {
            $thread_id = $thread->id;
            $bookmark->threads()->attach($thread_id);
        }

        if (!empty($request->tags)) {

            $tags = Tag::insertTags($request->tags);
            $bookmark->tags()->syncWithoutDetaching($tags);

        }

        if (!empty($request->comment)) {
            $comment = Comment::create([
                'poster_id' => Auth::user()->id,
                'bookmark_id' => $bookmark->id,
                'body' => $request->comment,
            ]);
        }

        return response()->json([
            'status' => 'bookmark added',
            'thread_anids' => $request->thread_anids,
            'bookmark' => $bookmark->getBookmarkDetails(),
        ]);
    }

    public function postBookmarkTags (Request $request) {

        $validator = Validator::make($request->all(), [
            'bookmark_id' => ['required', 'int'],
            'tags' => ['required', 'array'],
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors());
        }

        $bookmark = Bookmark::find($request->bookmark_id);

        if (empty($bookmark)) {
            return response()->json(['status' => 'no bookmark']); 
        }

        if ($bookmark->getPosterId() !== Auth::id()) {
            return response()->json(['status' => 'Bookmark owner error']); 
        }

        $tags = Tag::insertTags($request->tags);

        $bookmark->tags()->syncWithoutDetaching($tags);

        return response()->json([
            'status' => 'tags added to bookmark',
            'bookmark' => $bookmark->getBookmarkDetails(),
        ]); 

    }

    public function deleteBookmarkTags (Request $request) {

        $validator = Validator::make($request->all(), [
            'bookmark_id' => ['required', 'int'],
            'tags' => ['required', 'array'],
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors());
        }

        $bookmark = Bookmark::find($request->bookmark_id);

        if (empty($bookmark)) {
            return response()->json(['status' => 'no bookmark']); 
        }

        if ($bookmark->getPosterId() !== Auth::id()) {
            return response()->json(['status' => 'Bookmark owner error']); 
        }

        $tags = array_map(function ($tag) {
            $_tag = Tag::firstWhere('name', $tag);
            if (!empty($_tag)) {
                return $_tag->id;
            }
        }, $request->tags);

        $bookmark->tags()->detach($tags);

        return response()->json([
            'status' => 'tags removed from bookmark',
            'bookmark' => $bookmark->getBookmarkDetails(),
        ]);

    }

    public function updateBookmark (Request $request) {
        $bookmark = Bookmark::find($request->id);

        if ($bookmark->getPosterId() !== Auth::id()) {
            return response()->json(['status' => 'Bookmark owner error']);         
        }

        $validator = Validator::make($request->all(), [
            'description' => ['string', 'max:128'],
            'url' => ['url', 'string', 'max: 512'],
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors());
        }

        if (!empty($request->url)) {
            $bookmark->url = $request->url;
        }
        if (!empty($request->description)) {
            $bookmark->description = $request->description;
        }

        if (!$bookmark->isDirty()) {
            return response()->json(['status' => 'No attribute changes']);
        }

        $bookmark->save();
        
        return response()->json([
            'status' => 'Bookmark updated',
            'bookmark' => $bookmark->getBookmarkDetails(),
        ]);
    }

    public function test () {
        $bookmark = Bookmark::find(5);
        return $bookmark->getBookmarkDetails();
    }

    public function deactivateBookmark (Request $request) {

        $validator = Validator::make($request->all(), [
            'id' => ['required', 'int'],
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors());
        }

        $bookmark = Bookmark::find($request->id);

        if (empty($bookmark)) {
            return response()->json(['status' => 'no bookmark found']);
        }

        if (Auth::id() !== $bookmark->getPosterId()) {
            return response()->json(['status' => 'not authorized']);
        }

        $bookmark->delete();

        return response()->json([
            'status' => 'bookmark deleted',
            'bookmark' => $bookmark,
        ]);
    }
}
