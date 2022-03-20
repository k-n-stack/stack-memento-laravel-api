<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CommentController extends Controller
{
    public function allOfAuth() {
        return Auth::user()->comments;
    }

    public function countAllOfAuth() {
        return Auth::user()->comments->count();
    }
}
