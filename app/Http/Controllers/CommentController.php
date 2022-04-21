<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CommentController extends Controller
{
    public function allOfAuth() {
        return Auth::user()->comments;
    }

    public function countAllOfAuth() {
        return Auth::user()->comments->count();
    }

    public function deactivateComments(Request $request) {
        $validator = Validator::make($request->all(), [
            'comments' => ['required', 'array'],
        ]);

        if ($validator->fails()) {
            return $response->json($validator->errors());
        }

        
    }
}
