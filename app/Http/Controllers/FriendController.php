<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class FriendController extends Controller
{
    public function getFellows() {
      return Auth::user()->friends->map(function ($friend) {
        $user = User::find($friend->friend_id);
        return [
          "alphanumeric_id" => $user->alphanumeric_id,
          "pseudonym" => $user->pseudonym,
          "image_url" => "ressource/avatars/$user->alphanumeric_id",
        ];
      });
    }
}
