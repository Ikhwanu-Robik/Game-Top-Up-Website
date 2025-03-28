<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TopUpTransaction extends Model
{
    protected $fillable = [
        'user_id',
        'package_id',
        'flip_link_id',
        'flip_link_url',
        'method',
        'status'
    ];
}
