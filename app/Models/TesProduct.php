<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TesProduct extends Model
{
    use HasFactory;

    protected $fillable = [
        'sku',
        'name',
        'description',
        'stock',
        'price',
        'category',
        'is_active',
        'published_at',
    ];

    protected function casts(): array
    {
        return [
            'price' => 'decimal:2',
            'is_active' => 'boolean',
            'published_at' => 'datetime',
        ];
    }
}
