<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Guide extends Model
{
    protected $table = 'guides';
    protected $fillable = [
        'title',
        'tags',
        'content',
        'upvotes',
        'downvotes',
        'isPublished',
        'isApproved',
        'isDeleted',
        'user_id'
    ];
    protected $guarded = [
        'id'
    ];
}
