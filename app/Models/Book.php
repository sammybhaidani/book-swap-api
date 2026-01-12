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
        'description',
        'image_url',
        'available',
        'claimed_by'
    ];

    protected $casts = [
        'available' => 'boolean',
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
