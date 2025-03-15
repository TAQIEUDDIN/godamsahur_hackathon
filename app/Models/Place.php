<?php
// Step 1: Create a Model for Places
// app/Models/Place.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Place extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'type', // restaurant, mosque, hotel
        'latitude',
        'longitude',
        'address',
        'image_url',
        'rating',
        'contact_info',
    ];
}