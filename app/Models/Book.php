<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Book extends Model
{
    protected $fillable = [
        'title',
        'author',
        'genre_id',
        'blurb',
        'image',
        'year',
        'page_count',
        'available',
        'claimed_by_name',
        'claimed_by_email',
    ];

    protected $casts = [
        'available' => 'boolean',
        'year' => 'integer',
        'page_count' => 'integer',
    ];

    public function genre(): BelongsTo
    {
        return $this->belongsTo(Genre::class);
    }

    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class);
    }
}
