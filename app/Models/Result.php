<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Result extends Model
{
    use HasFactory;

    protected $table = 'results';

    protected $fillable = [
        'Bib',
        'Location',
        'GunTime',
        'ChipTime',
        'Time',
        'Rank',
        'OverallRank',
        'GenderRank',
        'CategoryRank',
        'Status',
    ];
}
