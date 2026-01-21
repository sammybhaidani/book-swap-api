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

    protected $hidden = ['reviewer_name', 'review_text'];
    protected $appends = ['name', 'review'];

    public function getNameAttribute(): string
    {
        return $this->reviewer_name;
    }

    public function getReviewAttribute(): string
    {
        return $this->review_text;
    }

    public function book(): BelongsTo
    {
        return $this->belongsTo(Book::class);
    }
}
