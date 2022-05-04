<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Models\User;
use App\Models\Thread;

class Group extends Model
{
    use HasFactory;

    protected $casts = [
        'created_at' => 'datetime:Y-m-d H:i',
        'updated_at' => 'datetime:Y-m-d H:i',
    ];

    protected $appends = [
        'image_url',
    ];

    public function getImageUrlAttribute()
    {
        return 'ressource/groups/'.$this->alphanumeric_id;
    }

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
