<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Models\Bookmark;
use App\Models\User;
use App\Models\Friend;
use App\Models\Group;

class Thread extends Model
{
    use HasFactory;

    public function bookmarks() {
        return $this->belongsToMany(Bookmark::class);
    }

    public function user() {
        return $this->belongsTo(User::class);
    }

    public function friends() {
        return $this->belongsToMany(Friend::class);
    }

    public function groups() {
        return $this->belongsToMany(Group::class);
    }

    public function pinnedThreads() {
        return $this->belongsToMany(User::class, 'pinned_threads');
    }
}
