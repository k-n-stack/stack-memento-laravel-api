<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RedirectionController extends Controller
{
    public function allOfAuth() {
        $redirections = Auth::user()->redirections;
        $_redirections = [];
        foreach ($redirections as $redirection) {
            array_push($_redirections, $redirection->pivot);
        }
        return $_redirections;
    }

    public function countAllOfAuth() {
        $redirections = $this->allOfAuth();

        if (empty($redirections)) {
            return 0;
        }

        $count = 0;
        foreach ($redirections as $redirection) {
            $count += $redirection->count;
        }

        return $count;
    }
}
