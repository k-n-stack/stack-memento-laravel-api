<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Model\User;

class Group extends Model
{
    use HasFactory;

    public function subscribers() {
        return $this->hasMany(User::class);
    }

    public function owner() {
        return $this->belongsTo(User::class);
    }
}
