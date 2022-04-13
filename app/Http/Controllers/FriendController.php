<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Carbon;
use App\Models\User;

class FriendController extends Controller
{
    public function getFellows() {
      return Auth::user()->friends->map(function ($friend) {
        $user = User::find($friend->friend_id);
        return empty($friend->validated_at) ? null : [
          "alphanumeric_id" => $user->alphanumeric_id,
          "pseudonym" => $user->pseudonym,
          "image_url" => "ressource/avatars/$user->alphanumeric_id",
          "friend_since" => date('Y-m-d', strtotime($friend->validated_at)),
          "total_bookmarks" => $user->countBookmarks(),
          "total_threads" => $user->countThreads(),
          "threads" => $user->threads->map(function ($thread) {
            return $thread->visibility === 'private' ? null : $thread->getThreadDetails();
          }),
          "register_date" => date('Y-m-d', strtotime($user->email_verified_at)),
          "last_bookmark" => $user->threads->last()->create
        ];
      });
    }
}
