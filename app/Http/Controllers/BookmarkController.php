<?php

namespace App\Http\Controllers;

use App\Models\Bookmark;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BookmarkController extends Controller
{
    public function allOfAuth() {
        $bookmarks = [];
        $threads = Auth::user()->threads;
        foreach ($threads as $thread) {
            foreach ($thread->bookmarks as $bookmark) {
                array_push($bookmarks, $bookmark);
            }
        }
        return $bookmarks;
    }

    public function countAllOfAuth() {
        return count($this->allOfAuth());
    }
}
