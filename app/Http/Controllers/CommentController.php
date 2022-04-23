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

        return response()->json([
            'status' => 'comments validated',
            'bookmark' => $bookmark->getBookmarkDetails(),
        ]);

    }
}
