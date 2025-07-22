<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class News extends Model
{
    protected $fillable = [
        'title',
        'description',
        'content',
        'source',
        'author',
        'published_at',
        'url',
        'urlToImage',
        'category',
        'api_source',
    ];
}
