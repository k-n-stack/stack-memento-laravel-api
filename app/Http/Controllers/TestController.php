<?php

namespace App\Http\Controllers;

use App\Models\Bookmark;
use App\Models\Comment;
use App\Models\Friend;
use App\Models\Group;
use App\Models\Search;
use App\Models\Tag;
use App\Models\Thread;
use App\Models\User;

use Illuminate\Http\Request;

class TestController extends Controller
{
    public function test (Request $request) {

        return User::find(1)->friends->map(function ($friend) {

            if(empty($friend->validated_at)) {
                return null;
            }

            $user = User::find($friend->friend_id)->getFellowDetails($friend);
            $user->friend_since = date('Y-m-d', strtotime($friend->validated_at));
            $user->register_date = date('Y-m-d H:i', strtotime($user->email_verified_at));
            return $user;
            
        });
    }
}
