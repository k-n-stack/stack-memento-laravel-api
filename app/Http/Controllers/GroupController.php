<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class GroupController extends Controller
{
    public function getSubscribedGroups() {
      return Auth::user()->subscribedGroups->map(function ($group) {
        $owner = $group->owner;
        return [
          "alphanumeric_id" => $group->alphanumeric_id,
          "name" => $group->name,
          "image_url" => "ressource/groups/$group->alphanumeric_id",
          "owner" => $owner,
          "subscribers" => $group->subscribers,
          "threads" => $group->threads,
        ];
      });
    }

    public function getOwnedGroups() {
      return Auth::user()->ownGroups->map(function ($group) {
        return [
          "alphanumeric_id" => $group->alphanumeric_id,
          "name" => $group->name,
          "image_url" => "ressource/groups/$group->alphanumeric_id",
          "owner" => $group->owner,
          "subscribers" => $group->subscribers,
          "threads" => $group->threads,
        ];
      });
    }
}
