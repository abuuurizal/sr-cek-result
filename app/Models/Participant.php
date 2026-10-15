<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Participant extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'paticipants';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
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
    ];

    /**
     * Status constants.
     */
    public const STATUS_FINISHED = 'finished';
    public const STATUS_DNS = 'dns';
    public const STATUS_DNF = 'dnf';

    public const STATUS_OPTIONS = [
        self::STATUS_FINISHED => 'Finished',
        self::STATUS_DNS => 'DNS',
        self::STATUS_DNF => 'DNF',
    ];
}
