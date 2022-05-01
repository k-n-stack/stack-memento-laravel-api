<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

use App\Models\User;
use App\Models\Bookmark;

class Comment extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'bookmark_id',
        'poster_id',
        'parent_id',
        'body',
        'validated_at',
    ];

    protected $hidden = [
        'poster_id',
        'bookmark_id',
        'updated_at',
        'deleted_at',
    ];

    public function parent() {
        return $this->belongsTo(Comment::class, 'parent_id');
    }

    public function childs() {
        return $this->hasMany(Comment::class, 'parent_id');
    }

    public function user() {
        return $this->belongsTo(User::class, 'poster_id');
    }

    public function bookmark() {
        return $this->belongsTo(Bookmark::class);
    }

    // !!!! RECURSIVE
    public function getNestedChilds() {
        $this->user;
        $this->childs = empty($this->childs) ? [] : $this->childs->map(function ($child) {
            return $child->getNestedChilds();
        });
        return $this;
    }

}
