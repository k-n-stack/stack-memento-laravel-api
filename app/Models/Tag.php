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

    protected $hidden = [
        'id',
        'validated_at',
        'created_at',
        'updated_at',
        'pivot',
    ];

    public function bookmarks() {
        return $this->belongsToMany(Bookmark::class);
    }

    public static function insertTags ($tags) {
        if (empty($tags)) {
            return [];
        }

        $_tags = [];

        foreach ($tags as $tag) {
            $_tags[] = Tag::firstOrCreate([
                'name' => $tag,
            ]);
        }

        $_tags = array_map(function ($tag) {
            return $tag->id;
        }, $_tags);

        return $_tags;
    }
}
