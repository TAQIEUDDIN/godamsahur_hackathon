<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    protected $fillable = [
        'user_id',
        'place_id',
        'place_name',
        'place_type',
        'location',
        'rating',
        'review_text',
        'photos'
    ];

    protected $casts = [
        'photos' => 'array'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
