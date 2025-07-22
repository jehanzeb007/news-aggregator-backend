<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserPreference extends Model
{
    protected $fillable = [
        'user_id', 'use_newsapi', 'use_gnews', 'use_mediastack'
    ];
}