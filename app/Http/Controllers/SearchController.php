<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class SearchController extends Controller
{
    public function test (Request $request) {
        return User::where('pseudonym', 'like', "%".$request->test."%")->get();
    }
}
