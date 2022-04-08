<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function getUserImage () {
      // Auth::user()
    }

    public function postAvatar(Request $request)
    {
        $extension = ".".$request->avatar->extension();
        $path = $request->file('avatar')->storeAs('avatars', Auth::user()->alphanumeric_id.$extension);
 
        return $path;
    }

    public function test2(Request $request) {
      
    }
}
