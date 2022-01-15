<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Post extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * Each post belongs to an admin user
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'author_id');
    }

    /**
     * Each post morph many comments
     */
    public function comments()
    {
        return $this->morphMany(Comment::class, 'commentable');
    }

    protected $fillable = ['title', 'description', 'image', 'status', 'published_at', 'author_id', 'category_id', 'commentable'];
}
