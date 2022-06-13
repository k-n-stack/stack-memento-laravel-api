<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

use App\Models\Comment;
use App\Models\Bookmark;

class CommentController extends Controller
{
    public function allOfAuth() {
        return Auth::user()->comments;
    }

    public function getAllInvalid () {

        $comments = [];

        $bookmarks = Auth::user()->getBookmarks();

        foreach ($bookmarks as $bookmark) {
            $bookmark->comments->each(function ($comment) use (&$comments) { 
                if ($comment->poster_id === Auth::id() || !is_null($comment->validated_at)) {
                    return null;
                }
                $comment->user;
                $comment->bookmark->threads;
                $comments[] = $comment;
            });
        }

        return $comments;
    }

    public function countAllOfAuth() {
        return Auth::user()->comments->count();
    }

    public function deleteComments (Request $request) {

        $validator = Validator::make($request->all(), [
            'comments' => ['required', 'array']
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors());
        }

        $comments = Comment::whereIn('id', $request->comments)->get();

        $bookmarks = $comments->map(function ($comment) {
            return $comment->bookmark;
        })->filter()->unique()->values();

        if (count($bookmarks) !== 1) {
            return response()->json(['status' => 'cannot delete comment in multilpe bookmarks']);
        }
        
        $bookmark = $bookmarks[0];
        
        if ($bookmark->getPosterId() !== Auth::id()) {
            return response()->json(['status' => 'cannot delete comment in bookmark owned by another user']);
        }

        $comments->map(function ($comment) {
            $comment->delete();
        });

        return response()->json([
            'status' => 'comments deleted',
            'bookmark' => $bookmark->getBookmarkDetails(),
        ]);
    }

    public function validateComments (Request $request) {

        $validator = Validator::make($request->all(), [
            'comments' => ['required', 'array'],
            'is_mobile' => ['boolean'],
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors());
        }

        $comments = Comment::whereIn('id', $request->comments)->get();

        $bookmarks = $comments->map(function ($comment) {
            return $comment->bookmark;
        })->filter()->unique()->values();

        if (count($bookmarks) !== 1) {
            return response()->json(['status' => 'cannot update comment in multilpe bookmarks']);
        }
        
        $bookmark = $bookmarks[0];
        
        if ($bookmark->getPosterId() !== Auth::id()) {
            return response()->json(['status' => 'cannot update comment in bookmark owned by another user']);
        }

        $comments->map(function ($comment) {
            $comment->validated_at = date('Y-m-d H:i:s');
            $comment->save();
        });

        $isMobile = $request->input('is_mobile');

        $response = $isMobile ? [
            'status' => 'comments validated',
            'bookmark' => $bookmark->getBookmarkDetails(),
            'comment' => reset($comments),
        ] : [
            'status' => 'comments validated',
            'bookmark' => $bookmark->getBookmarkDetails(),
        ];

        return response()->json($response);

    }

    public function postComment (Request $request) {

        $validator = Validator::make($request->all(), [
            'body' => ['required', 'string', 'max:512'],
            'bookmark_id'=> ['required', 'integer'],
            'parent_id' => ['integer'],
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors());
        }

        $bookmark = Bookmark::find($request->bookmark_id);

        $validated_at = date("Y-m-d H:i:s");
        $allFullPrivate = true;

        $bookmark->threads->each(function ($thread) use (&$allFullPrivate, &$validated_at) {
            if ($thread->visibility !== 'private') {
                $allFullPrivate = false;
            }
            if ($thread->visibility !== 'public') {
                $validated_at = null;
            }
        });

        if ($allFullPrivate) {
            return response()->json(['status' => 'cannot comment on bookmark that belong to full private threads only']);
        }

        $doParentExist = false;

        if (!empty($request->parent_id)) {
            $bookmark->comments->each(function ($comment) use (&$doParentExist, $request) {
                if ($comment->id === $request->parent_id) {
                    $doParentExist = true;
                }
            });

            if (!$doParentExist) {
                return response()->json(['status' => 'cannot have a parent comment in another bookmark']);
            }
        }

        $comment = Comment::create([
            'poster_id' => Auth::id(),
            'parent_id' => empty($request->parent_id) ? null : $request->parent_id,
            'bookmark_id' => $request->bookmark_id,
            'body' => $request->body,
            'validated_at' => $validated_at,
        ]);

        $bookmark = Bookmark::find($bookmark->id);

        return response()->json([
            'status' => 'comment added to bookmark',
            'bookmark' => $bookmark->getBookmarkDetails(),
        ]);
    }
}
