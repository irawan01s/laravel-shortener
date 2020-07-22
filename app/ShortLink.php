<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ShortLink extends Model
{
    protected $fillable = [
        'shortcode', 'url', 'redirect_count'
    ];
}
