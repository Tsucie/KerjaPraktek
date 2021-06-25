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
        'vnu_nama',
        'vnu_desc',
        'vnu_fasilitas',
        'vnu_harga',
        'created_by',
        'updated_by'
    ];

    public function hasPhoto()
    {
        return $this->hasMany(VenuePhoto::class, 'vp_vnu_id', 'vnu_id');
    }
}
