<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Comment extends Model
{
    use HasFactory, SoftDeletes;

    public function commentable()
    {
        return $this->morphTo();
    }

    /**
     * Each comment belongs to a user
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'author_id');
    }

    /**
     * Each comment has a parent or is a parent
     */
    public function parent()
    {
        return $this->belongsTo($this, 'parent_id');
    }

    /**
     * Each comment has many answers
     */
    public function answers()
    {
        return $this->hasMany($this, 'parent_id');
    }

    protected $fillable = ['body', 'parent_id', 'author_id', 'commentable_id', 'commentable_type', 'approved', 'status'];
}
