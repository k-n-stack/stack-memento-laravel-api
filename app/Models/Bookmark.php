<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Models\Thread;
use App\Models\Comment;
use App\Models\Vote;
use App\Models\Redirection;

class Bookmark extends Model
{
    use HasFactory;

    public function threads() {
        return $this->belongsToMany(Thread::class);
    }

    public function comments() {
        return $this->hasMany(Comment::class);
    }

    public function votes() {
        return $this->belongsToMany(User::class, 'votes');
    }

    public function user() {
        return $this->belongsToMany(User::class, 'redirections')->withPivot('count');
    }

}
