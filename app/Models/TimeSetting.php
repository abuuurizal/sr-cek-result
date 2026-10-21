<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TimeSetting extends Model
{
    use HasFactory;

    protected $table = 'time_settings';

    protected $fillable = [
        'kategori',
        'gun_time',
        'fastest',
    ];

    protected $casts = [
        'gun_time' => 'datetime',
    ];
}
