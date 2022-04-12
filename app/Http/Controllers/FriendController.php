<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class FriendController extends Controller
{
    public function getFellows() {
      return Auth::user()->friends->map(function ($friend) {
        return User::find($friend->friend_id);
      });
    }
}
