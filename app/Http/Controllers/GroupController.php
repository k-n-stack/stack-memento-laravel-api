<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class GroupController extends Controller
{
    public function getSubscribedGroups() {
      return Auth::user()->subscribedGroups->map(function ($group) {
        return [
          "alphanumeric_id" => $group->alphanumeric_id,
          "name" => $group->name,
          "image_url" => "groups/$group->image_url",
        ];
      });
    }

    public function getOwnedGroups() {
      return Auth::user()->ownGroups;
    }
}
