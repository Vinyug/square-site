<?php declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Post extends Model
{  
    protected $fillable = [
        'users_id', 'title', 'slug', 'body', 'reading_time', 'img',
    ];
}