<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TopUpPackage extends Model
{
    protected $fillable = [
        'game_id',
        'title',
        'items_count',
        'price'
    ];
}
