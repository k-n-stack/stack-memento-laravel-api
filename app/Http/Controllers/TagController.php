<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Tag;

class TagController extends Controller
{
    public function getAll() {
      $tags = Tag::whereNotNull('validated_at')->get()->map(function ($tag) {
        return $tag->name;
      });
      return response()->json($tags);
    }
}
