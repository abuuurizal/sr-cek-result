<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TimeRecord extends Model
{
    use HasFactory;

    protected $table = 'times';

    protected $fillable = [
        'Chip',
        'Location',
        'Time',
        'Lap',
    ];
}
