<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ThreadController extends Controller
{

    // "id": 16,
    // "user_id": 8,
    // "title": "Retail Sales person",
    // "visibility": "shareable",
    // "image_url": "0123456.png",
    // "color": "962663",
    // "created_at": "2022-03-18T17:14:47.000000Z",
    // "updated_at": "2022-03-18T17:14:47.000000Z",
    // "deleted_at": null
    public function allOfAuth() {
        return Auth::user()->threads->map(function ($thread) {
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
        return count($this->AllOfAuth());
    }
}
