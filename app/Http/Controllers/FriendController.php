<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FriendController extends Controller
{
    public function getFellows() {
      return Auth::user()->friends->map(function ($friend) {
        return [
          "alphanumeric_id" => $friend->user->alphanumeric_id,
          "pseudonym" => $friend->user->pseudonym,
          "image_url" => $friend->user->image_url,
        ];
      });
    }
}
