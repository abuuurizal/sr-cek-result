<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Participant extends Model
{
    use HasFactory;

    /**
     * Nama tabel di database sesuai dengan skema MyLaps / local.
     */
    protected $table = 'paticipants';

    protected $fillable = [
        'mylaps_code',
        'chip_code',
        'no_bib',
        'nama_lengkap',
        'gender',
        'kategori',
        'sub_kategori',
        'status',
        'pace',
        'gun_time',
        'chip_time',
        'synced_at',
    ];

    protected $casts = [
        'synced_at' => 'datetime',
    ];

    /**
     * Scope untuk pencarian (BIB atau Nama).
     */
    public function scopeSearch($query, ?string $term)
    {
        if (empty($term)) {
            return $query;
        }

        return $query->where(function ($q) use ($term) {
            $q->where('no_bib', 'like', "%{$term}%")
                ->orWhere('nama_lengkap', 'like', "%{$term}%")
                ->orWhere('mylaps_code', 'like', "%{$term}%")
                ->orWhere('chip_code', 'like', "%{$term}%");
        });
    }

    /**
     * Scope untuk peserta yang sudah finish.
     */
    public function scopeFinished($query)
    {
        return $query->where('status', 'finished');
    }
}
