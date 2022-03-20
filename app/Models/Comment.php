<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Models\User;
use App\Models\Bookmark;

class Comment extends Model
{
    use HasFactory;

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
        return !empty($this->deleted_at) ? null : [
            "posted_at" => $this->validated_at,
            "poster_name" => $this->user->pseudonym,
            "poster_image_url" => $this->user->image_url,
            "body" => $this->body,
            "childs" => empty($this->childs) ? [] : $this->childs->map(function ($child) {
                return $child->getNestedChilds();
            })
        ];
    }

}
