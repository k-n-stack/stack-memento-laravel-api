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
