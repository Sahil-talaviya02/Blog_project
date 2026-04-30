<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Cviebrock\EloquentSluggable\Sluggable;

class Category extends Model
{
    use HasFactory;
    use Sluggable;

    protected $fillable = [
        'name',
        'slug',
        'parent_id',
        'ordering'
    ];

    public function parentCategory()
    {
        return $this->belongsTo(ParentCategory::class, 'parent_id');
    }

    // optional (if posts exist)
    // public function posts()
    // {
    //     return $this->hasMany(Post::class);
    // }

    public function sluggable(): array
    {
        return [
            'slug' => [
                'source' => 'name'
            ]
        ];
    }
}
