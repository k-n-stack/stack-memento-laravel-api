<?php

namespace App\Http\Controllers;

use Illuminate\Auth\Events\Registered;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Mail;
use App\Models\Bookmark;
use App\Models\Comment;
use App\Models\Friend;
use App\Models\Group;
use App\Models\Search;
use App\Models\Tag;
use App\Models\Thread;
use App\Models\User;

class TestController extends Controller
{
    public function test (Request $request) {
        return User::find(1)->subscribedGroups;

        return Thread::find(8)->groups;
    }
}