<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Models\User;
use App\Models\Thread;

class Group extends Model
{
    use HasFactory;

    public function subscribers() {
        return $this->belongsToMany(User::class);
    }

    public function owner() {
        return $this->belongsTo(User::class);
    }

    public function threads() {
        return $this->belongsToMany(Thread::class);
    }
}
