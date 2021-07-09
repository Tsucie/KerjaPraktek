<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @author Rizky A
 */
class Venue extends Model
{
    use HasFactory;

    protected $primaryKey = 'vnu_id';

    protected $fillable = [
        'vnu_id',
        'vnu_nama',
        'vnu_desc',
        'vnu_fasilitas',
        'vnu_harga',
        'vnu_status_tersedia',
        'created_by',
        'updated_by'
    ];

    protected $casts = [
        'vnu_harga' => 'double'
    ];
}
