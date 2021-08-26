<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @author Rizky A
 */
class Product extends Model
{
    use HasFactory;

    protected $primaryKey = 'pdct_id';

    protected $fillable = [
        'pdct_id',
        'pdct_kode',
        'pdct_nama',
        'pdct_desc',
        'pdct_harga',
        'pdct_stock',
        'created_by',
        'updated_by'
    ];

    protected $casts = [
        'pdct_harga' => 'double'
    ];

    public function promo()
    {
        return $this->hasOne(Promo::class, 'prm_pdct_id', 'pdct_id');
    }

    public function photos()
    {
        return $this->hasMany(ProductPhoto::class, 'pp_pdct_id', 'pdct_id');
    }

    public function reviews()
    {
        return $this->hasMany(Feedback::class, 'fb_pdct_id', 'pdct_id');
    }
}
