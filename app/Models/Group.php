<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

use App\Models\User;
use App\Models\Thread;

class Group extends Model
{
    use HasFactory, SoftDeletes;

    protected $hidden = [
        'id',
        'deleted_at',
    ];

    protected $casts = [
        'created_at' => 'datetime:Y-m-d',
        'updated_at' => 'datetime:Y-m-d',
    ];

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
