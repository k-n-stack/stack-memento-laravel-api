<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class RessourceController extends Controller
{
    public function getAvatar(Request $request, $image) {
        $avatar = Storage::get("avatars/$image.png");
        return response()->make($avatar, 200, ['content-type' => 'image/png']);
    }
}
