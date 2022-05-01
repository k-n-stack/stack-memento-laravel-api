<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class GroupController extends Controller
{
    public function getSubscribedGroups() {
      return Auth::user()->subscribedGroups->map(function ($group) {
        $group->owner;
        $group->threads;
        $group->subscribers;
        return $group;
      });

    }

    public function getOwnedGroups() {
      return Auth::user()->ownGroups->map(function ($group) {
        $group->owner;
        $group->threads;
        $group->subscribers;
        return $group;
      });
    }

}
