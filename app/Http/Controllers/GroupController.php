<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class GroupController extends Controller
{
    public function getSubscribedGroups() {
      return Auth::user()->subscribedGroups->map(function ($group) {
        $owner = $group->owner;
        $owner->image_url = "http://localhost:8000/api/ressource/avatars/$owner->alphanumeric_id";
        // return [
        //   "alphanumeric_id" => $group->alphanumeric_id,
        //   "name" => $group->name,
        //   "image_url" => "ressource/groups/$group->alphanumeric_id",
        //   "owner" => $owner,
        //   "subscribers" => $group->subscribers->map(function ($subscriber) {
        //     $subscriber->image_url = "http://localhost:8000/api/ressource/avatars/$subscriber->alphanumeric_id";
        //     return $subscriber;
        //   }),
        //   "threads" => $group->threads,
        // ];

        $group->owner;
        $group->threads;
        return $group;
      });
    }

    public function getOwnedGroups() {
      return Auth::user()->ownGroups->map(function ($group) {
        $owner = $group->owner;
        $owner->image_url = "http://localhost:8000/api/ressource/avatars/$owner->alphanumeric_id";
        // return [
        //   "alphanumeric_id" => $group->alphanumeric_id,
        //   "name" => $group->name,
        //   "image_url" => "ressource/groups/$group->alphanumeric_id",
        //   "owner" => $owner,
        //   "subscribers" => $group->subscribers->map(function ($subscriber) {
        //     $subscriber->image_url = "http://localhost:8000/api/ressource/avatars/$subscriber->alphanumeric_id";
        //     return $subscriber;
        //   }),
        //   "threads" => $group->threads,
        // ];

        $group->owner;
        $group->threads;
        return $group;
      });
    }





    // public function getOwnedGroups() {
    //   return Auth::user()->ownGroups->map(function ($group) {
    //     return [
    //       "alphanumeric_id" => $group->alphanumeric_id,
    //       "name" => $group->name,
    //       "image_url" => "ressource/groups/$group->alphanumeric_id",
    //       "owner" => $group->owner,
    //       "subscribers" => $group->subscribers,
    //       "threads" => $group->threads,
    //     ];
    //   });
    // }
}
