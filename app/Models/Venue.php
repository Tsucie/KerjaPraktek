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
        'vnu_tipe_waktu',
        'vnu_jam_pemakaian_siang',
        'vnu_jam_pemakaian_malam',
        'vnu_status_tersedia',
        'created_by',
        'updated_by'
    ];

    protected $casts = [
        'vnu_harga' => 'double'
    ];

    public function promo()
    {
        return $this->hasOne(Promo::class, 'prm_vnu_id', 'vnu_id');
    }

    public function photos()
    {
        return $this->hasMany(VenuePhoto::class, 'vp_vnu_id', 'vnu_id');
    }

    public function reviews()
    {
        return $this->hasMany(Feedback::class, 'fb_vnu_id', 'vnu_id');
    }
}
