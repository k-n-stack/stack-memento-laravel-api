<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Models\Bookmark;

class Tag extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
    ];

    public function bookmarks() {
        return $this->belongsToMany(Bookmark::class);
    }
}
