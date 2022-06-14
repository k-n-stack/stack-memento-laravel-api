<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class RessourceController extends Controller
{

    public function getAvatar ($type, $anid) {

        if (!in_array($type, ['avatars', 'groups', 'threads'])) {
            return response()->json(['status' => 'failed to load ressource']);
        }

        $filePaths = Storage::disk('local')->files("$type/$anid");

        if (!Storage::exists("$type/$anid") || empty($filePaths)) {
            return $this->getDefaultImage($type);
        }

        $avatar = Storage::get($filePaths[0]);
        return response()->make($avatar, 200, ['content-type' => 'image/png']);

    }

    public function getDefaultImage ($folder) {

        $ressources = Storage::get("$folder/default.png");
        return response()->make($ressources, 200, ['content-type' => 'image/png']);
        
    }

    public function getEmailImage () {
        $ressources = Storage::get("stmn-logo.png");
        return response()->make($ressources, 200, ['content-type' => 'image/png']);
    }
}
