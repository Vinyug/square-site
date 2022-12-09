<?php declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Post extends Model
{  
    protected $fillable = [
        'users_id', 'title', 'slug', 'body', 'reading_time', 'img',
    ];

    // récupérer les comments associé à un post
    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class)->orderBy('id', 'desc');
    }
}