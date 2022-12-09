<?php declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Comment extends Model
{  
    protected $fillable = [
        'post_id', 'user_id', 'body',
    ];

    // accéder à la table User depuis Comment
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}