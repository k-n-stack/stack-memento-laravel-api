<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ThreadController extends Controller
{
    public function allOfAuth() {
        return Auth::user()->threads;
    }

    public function countAllOfAuth() {
        return count($this->AllOfAuth());
    }
}
