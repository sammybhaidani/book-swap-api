<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Review extends Model
{
    protected $fillable = [
        'book_id',
        'reviewer_name',
        'review_text',
        'rating'
    ];

    public function book(): BelongsTo
    {
        return $this->belongsTo(Book::class);
    }
}
