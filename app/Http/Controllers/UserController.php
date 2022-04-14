<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class UserController extends Controller
{
    public function postAvatar(Request $request)
    {
        $extension = ".".$request->avatar->extension();
        $path = $request->file('avatar')->storeAs('avatars', Auth::user()->alphanumeric_id.$extension);
 
        return $path;
    }

    public function test () {
      $user = User::where('id', 1)->first();

      return $user->threads;
    }
}
